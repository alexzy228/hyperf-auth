<?php
declare(strict_types=1);

namespace Alexzy\HyperfAuth\Exception;

use Throwable;

class UnauthorizedException extends AuthException
{
    protected $statusCode = 401;

    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

}