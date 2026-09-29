<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Requests;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * A header a request configuration sends. The API assigns id and requestId,
 * so they are omitted from a request body unless set.
 *
 * Mirrors NfieldPublicApi.Models.Requests.RequestHeaderModel (and the
 * identical Nfield.Manager.Domains.Models.RequestHeader the PUT returns).
 */
final class RequestConfigurationHeaderModel extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $value = null,
        public bool $isObfuscated = false,
        public int|Optional $id = new Optional,
        public int|Optional $requestId = new Optional,
    ) {}
}
