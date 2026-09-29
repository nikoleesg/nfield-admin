<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\ResponseCodeScopedInterface;
use Nikoleesg\NfieldAdmin\Exceptions\MissingScopeException;

/**
 * Implements {@see ResponseCodeScopedInterface}.
 */
trait ScopedToResponseCode
{
    protected ?int $responseCode = null;

    public function setResponseCode(int $responseCode): static
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode ?? throw MissingScopeException::for(static::class, 'responseCode');
    }
}
