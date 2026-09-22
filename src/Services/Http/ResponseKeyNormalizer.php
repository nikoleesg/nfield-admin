<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services\Http;

/**
 * Normalizes NField response keys to camelCase.
 *
 * The API returns PascalCase (`SurveyId`, `AccessToken`) even though the OpenAPI
 * document declares camelCase. Rather than compensating per DTO, every decoded
 * response passes through here once, so the rest of the package only ever sees
 * camelCase.
 *
 * Only keys are touched; values — including strings that happen to look like
 * keys — are returned untouched.
 */
final class ResponseKeyNormalizer
{
    /**
     * Recursively lower-case the first character of every string key.
     */
    public static function normalize(mixed $data): mixed
    {
        if (! is_array($data)) {
            return $data;
        }

        $normalized = [];

        foreach ($data as $key => $value) {
            $normalized[is_string($key) ? lcfirst($key) : $key] = self::normalize($value);
        }

        return $normalized;
    }
}
