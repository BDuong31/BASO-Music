<?php

declare(strict_types=1);
require_once('Request.php');
require_once('SpotifyWebAPIAuthException.php');
require_once('SpotifyWebAPI.php');
require_once('SpotifyWebAPIException.php');

class Session
{
    protected string $accessToken = '';
    protected string $clientId = '';
    protected string $clientSecret = '';
    protected int $expirationTime = 0;
    protected string $redirectUri = '';
    protected string $refreshToken = '';
    protected string $scope = '';
    protected ?Request $request = null;

    public function __construct(
        string $clientId,
        string $clientSecret = '',
        string $redirectUri = '',
        ?Request $request = null
    ) {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setRedirectUri($redirectUri);

        $this->request = $request ?? new Request();
    }

    public function generateCodeChallenge(string $codeVerifier, string $hashAlgo = 'sha256'): string
    {
        $challenge = hash($hashAlgo, $codeVerifier, true);
        $challenge = base64_encode($challenge);
        $challenge = strtr($challenge, '+/', '-_');
        $challenge = rtrim($challenge, '=');

        return $challenge;
    }

    public function generateCodeVerifier(int $length = 128): string
    {
        return $this->generateState($length);
    }

    public function generateState(int $length = 16): string
    {
        // Length will be doubled when converting to hex
        return bin2hex(
            random_bytes($length / 2)
        );
    }

    public function getAuthorizeUrl(array|object $options = []): string
    {
        $options = (array) $options;

        $parameters = [
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUri(),
            'response_type' => 'code',
            'scope' => isset($options['scope']) ? implode(' ', $options['scope']) : null,
            'show_dialog' => !empty($options['show_dialog']) ? 'true' : null,
            'state' => $options['state'] ?? null,
        ];

        // Set some extra parameters for PKCE flows
        if (isset($options['code_challenge'])) {
            $parameters['code_challenge'] = $options['code_challenge'];
            $parameters['code_challenge_method'] = $options['code_challenge_method'] ?? 'S256';
        }

        return Request::ACCOUNT_URL . '/authorize?' . http_build_query($parameters, '', '&');
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Get the client secret.
     *
     * @return string The client secret.
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getTokenExpiration(): int
    {
        return $this->expirationTime;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getScope(): array
    {
        return explode(' ', $this->scope);
    }

    public function refreshAccessToken(?string $refreshToken = null): bool
    {
        $parameters = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken ?? $this->refreshToken,
        ];

        $headers = [];
        if ($this->getClientSecret()) {
            $payload = base64_encode($this->getClientId() . ':' . $this->getClientSecret());

            $headers = [
                'Authorization' => 'Basic ' . $payload,
            ];
        }

        ['body' => $response] = $this->request->account('POST', '/api/token', $parameters, $headers);

        if (isset($response->access_token)) {
            $this->accessToken = $response->access_token;
            $this->expirationTime = time() + $response->expires_in;
            $this->scope = $response->scope ?? $this->scope;

            if (isset($response->refresh_token)) {
                $this->refreshToken = $response->refresh_token;
            } elseif (empty($this->refreshToken)) {
                $this->refreshToken = $refreshToken;
            }

            return true;
        }

        return false;
    }

    public function requestAccessToken(string $authorizationCode, string $codeVerifier = ''): bool
    {
        $parameters = [
            'client_id' => $this->getClientId(),
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
        ];

        // Send a code verifier when PKCE, client secret otherwise
        if ($codeVerifier) {
            $parameters['code_verifier'] = $codeVerifier;
        } else {
            $parameters['client_secret'] = $this->getClientSecret();
        }

        ['body' => $response] = $this->request->account('POST', '/api/token', $parameters, []);

        if (isset($response->refresh_token) && isset($response->access_token)) {
            $this->refreshToken = $response->refresh_token;
            $this->accessToken = $response->access_token;
            $this->expirationTime = time() + $response->expires_in;
            $this->scope = $response->scope ?? $this->scope;

            return true;
        }

        return false;
    }

    public function requestCredentialsToken(): bool
    {
        $payload = base64_encode($this->getClientId() . ':' . $this->getClientSecret());

        $parameters = [
            'grant_type' => 'client_credentials',
        ];

        $headers = [
            'Authorization' => 'Basic ' . $payload,
        ];

        ['body' => $response] = $this->request->account('POST', '/api/token', $parameters, $headers);

        if (isset($response->access_token)) {
            $this->accessToken = $response->access_token;
            $this->expirationTime = time() + $response->expires_in;
            $this->scope = $response->scope ?? $this->scope;

            return true;
        }

        return false;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function setRedirectUri(string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}
