<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Exceptions;

use LogicException;

/**
 * A scoped service or resource was used before its scope was set.
 *
 * Before #41 the scope was a required constructor argument, so it could not be
 * missing. Setter-based scoping trades that guarantee for a loud failure at
 * first use instead of a request to `/v2/surveys//sample`.
 */
final class MissingScopeException extends LogicException
{
    public static function for(string $class, string $scope): self
    {
        return new self(sprintf(
            '%s was used before its %s was set. Call set%s() first.',
            $class,
            $scope,
            ucfirst($scope)
        ));
    }
}
