<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes;

use Spatie\LaravelData\Data;

/**
 * Request body for POST /v2/responseCodes. `id` is the response code itself
 * (e.g. 210).
 *
 * Mirrors NipoSoftware.Nfield.Manager.Api.Models.DomainResponseCodeCreateModel.
 */
final class DomainResponseCodeCreateModel extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?string $description = null,
        public ?string $url = null,
        public ?bool $isDefinite = null,
        public ?bool $isSelectable = null,
        public ?bool $allowAppointment = null,
        public ?bool $channelCapi = null,
        public ?bool $channelCati = null,
        public ?bool $channelOnline = null,
    ) {}
}
