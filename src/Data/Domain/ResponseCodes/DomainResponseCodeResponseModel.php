<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes;

use Spatie\LaravelData\Data;

/**
 * A domain (tenant-wide) response code.
 *
 * Mirrors NipoSoftware.Nfield.Manager.Api.Models.DomainResponseCodeResponseModel,
 * placed with the other models the spec tags "Domain - …".
 */
final class DomainResponseCodeResponseModel extends Data
{
    public function __construct(
        public int $id,
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
