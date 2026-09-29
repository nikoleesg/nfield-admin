<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;

/**
 * A survey's data retention period and the periods it may be set to, in days.
 *
 * Mirrors NfieldPublicApi.Models.GetDataRetentionSettingsModel.
 */
final class GetDataRetentionSettingsModel extends Data
{
    /**
     * @param  list<int>|null  $possibleValues
     */
    public function __construct(
        public int $retentionPeriod,
        public ?array $possibleValues = null,
    ) {}
}
