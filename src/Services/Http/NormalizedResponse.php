<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services\Http;

use Illuminate\Http\Client\Response;

/**
 * A response whose decoded JSON has been normalized to camelCase keys.
 *
 * Everything else — status, headers, the raw body — behaves exactly as the
 * underlying response does. Only {@see json()} (and the `object()`, `collect()`
 * and `fluent()` helpers built on it) sees normalized keys.
 */
final class NormalizedResponse extends Response
{
    public static function wrap(Response $response): self
    {
        $wrapped = new self($response->toPsrResponse());

        $wrapped->cookies = $response->cookies;
        $wrapped->transferStats = $response->transferStats;

        return $wrapped;
    }

    /**
     * {@inheritDoc}
     */
    public function json($key = null, $default = null)
    {
        if (! $this->decoded) {
            $this->decoded = ResponseKeyNormalizer::normalize(json_decode($this->body(), true));
        }

        if (is_null($key)) {
            return $this->decoded;
        }

        return data_get($this->decoded, $key, $default);
    }
}
