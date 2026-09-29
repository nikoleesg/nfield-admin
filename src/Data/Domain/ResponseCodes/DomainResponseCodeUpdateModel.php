<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Domain\ResponseCodes;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * Request body for PATCH /v2/responseCodes/{responseCodeId}.
 *
 * Every field defaults to Optional, so only the fields a caller sets are
 * sent; an explicit null is still sent to clear a field (#48).
 *
 * Mirrors NipoSoftware.Nfield.Manager.Api.Models.DomainResponseCodeUpdateModel.
 */
final class DomainResponseCodeUpdateModel extends Data
{
    public function __construct(
        public string|Optional|null $description = new Optional,
        public string|Optional|null $url = new Optional,
        public bool|Optional|null $isDefinite = new Optional,
        public bool|Optional|null $isSelectable = new Optional,
        public bool|Optional|null $allowAppointment = new Optional,
        public bool|Optional|null $channelCapi = new Optional,
        public bool|Optional|null $channelCati = new Optional,
        public bool|Optional|null $channelOnline = new Optional,
    ) {}
}
