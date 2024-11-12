<?php declare(strict_types=1);
require_once('SpotifyWebAPIAuthException.php');
require_once('SpotifyWebAPI.php');
require_once('SpotifyWebAPIException.php');
require_once('Session.php');

class Request
{
    public const ACCOUNT_URL = 'https://accounts.spotify.com';
    public const API_URL = 'https://api.spotify.com';

    protected array $lastResponse = [];
    protected array $options = [
        'curl_options' => [],
        'return_assoc' => false,
    ];

    public function __construct(array|object $options = [])
    {
        $this->setOptions($options);
    }

    protected function handleResponseError(string $body, int $status): void
    {
        $parsedBody = json_decode($body);
        $error = $parsedBody->error ?? null;

        if (isset($error->message) && isset($error->status)) {
            // It's an API call error
            $exception = new SpotifyWebAPIException($error->message, $error->status);

            if (isset($error->reason)) {
                $exception->setReason($error->reason);
            }

            throw $exception;
        } elseif (isset($parsedBody->error_description)) {
            throw new SpotifyWebAPIAuthException($parsedBody->error_description, $status);
        } elseif ($body) {
            throw new SpotifyWebAPIException($body, $status);
        } else {
            throw new SpotifyWebAPIException('An unknown error occurred.', $status);
        }
    }
    protected function parseBody(string $body): mixed
    {
        return json_decode($body, $this->options['return_assoc']);
    }
    protected function parseHeaders(string $headers): array
    {
        $headers = explode("\n", $headers);

        array_shift($headers);

        $parsedHeaders = [];
        foreach ($headers as $header) {
            [$key, $value] = explode(':', $header, 2);

            $key = strtolower($key);
            $parsedHeaders[$key] = trim($value);
        }

        return $parsedHeaders;
    }

    public function account(string $method, string $uri, string|array $parameters = [], array $headers = []): array
    {
        return $this->send($method, self::ACCOUNT_URL . $uri, $parameters, $headers);
    }

    public function api(string $method, string $uri, string|array $parameters = [], array $headers = []): array
    {
        return $this->send($method, self::API_URL . $uri, $parameters, $headers);
    }

    public function getLastResponse(): array
    {
        return $this->lastResponse;
    }

    public function send(string $method, string $url, string|array|object $parameters = [], array $headers = []): array
    {
        $this->lastResponse = [];

        if (is_array($parameters) || is_object($parameters)) {
            $parameters = http_build_query($parameters, '', '&');
        }

        $options = [
            CURLOPT_CAINFO => __DIR__ . '/cacert.pem',
            CURLOPT_ENCODING => '',
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => [],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => rtrim($url, '/'),
        ];

        foreach ($headers as $key => $val) {
            $options[CURLOPT_HTTPHEADER][] = "$key: $val";
        }

        $method = strtoupper($method);

        switch ($method) {
            case 'DELETE': 
            case 'PUT':
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                $options[CURLOPT_POSTFIELDS] = $parameters;

                break;
            case 'POST':
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $parameters;

                break;
            default:
                $options[CURLOPT_CUSTOMREQUEST] = $method;

                if ($parameters) {
                    $options[CURLOPT_URL] .= '/?' . $parameters;
                }

                break;
        }

        $ch = curl_init();

        curl_setopt_array($ch, array_replace($options, $this->options['curl_options']));

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            throw new SpotifyWebAPIException('cURL transport error: ' . $errno . ' ' . $error);
        }

        [$headers, $body] = $this->splitResponse($response);

        $parsedBody = $this->parseBody($body);
        $parsedHeaders = $this->parseHeaders($headers);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        $this->lastResponse = [
            'body' => $parsedBody,
            'headers' => $parsedHeaders,
            'status' => $status,
            'url' => $url,
        ];

        curl_close($ch);

        if ($status >= 400) {
            $this->handleResponseError($body, $status);
        }

        return $this->lastResponse;
    }

    public function setOptions(array|object $options): self
    {
        $this->options = array_merge($this->options, (array) $options);

        return $this;
    }

    protected function splitResponse(string $response): array
    {
        $response = str_replace("\r\n", "\n", $response);
        $parts = explode("\n\n", $response, 3);

        if (
            preg_match('/^HTTP\/1.\d 100 Continue/', $parts[0]) ||
            preg_match('/^HTTP\/1.\d 200 Connection established/', $parts[0]) ||
            preg_match('/^HTTP\/1.\d 200 Tunnel established/', $parts[0])
        ) {
            return [
                $parts[1],
                $parts[2],
            ];
        }

        return [
            $parts[0],
            $parts[1],
        ];
    }
}
