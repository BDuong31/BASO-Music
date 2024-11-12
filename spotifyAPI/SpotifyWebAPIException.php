<?php declare(strict_types=1);
require_once('SpotifyWebAPIAuthException.php');
require_once('SpotifyWebAPI.php');
require_once('Session.php');
require_once('Request.php');

class SpotifyWebAPIException extends \Exception
{
    public const TOKEN_EXPIRED = 'The access token expired';
    public const RATE_LIMIT_STATUS = 429;

    private string $reason = '';

    public function getReason(): string
    {
        return $this->reason;
    }

    public function hasExpiredToken(): bool
    {
        return $this->getMessage() === self::TOKEN_EXPIRED;
    }

    public function isRateLimited(): bool
    {
        return $this->getCode() === self::RATE_LIMIT_STATUS;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
}
