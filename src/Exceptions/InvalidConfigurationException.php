<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Exceptions;

use RuntimeException;

final class InvalidConfigurationException extends RuntimeException
{
    /**
     * @param  list<string>  $missing
     */
    public static function missingCredentials(array $missing): self
    {
        $envVars = array_map(static fn (string $key): string => match ($key) {
            'domain' => 'NFIELD_DOMAIN',
            'username' => 'NFIELD_USERNAME',
            'password' => 'NFIELD_PASSWORD',
            default => 'NFIELD_'.strtoupper($key),
        }, $missing);

        return new self(sprintf(
            'Nfield credentials are not configured. Missing: %s. Set the %s environment variable%s in your .env file.',
            implode(', ', $missing),
            implode(', ', $envVars),
            count($missing) > 1 ? 's' : ''
        ));
    }
}
