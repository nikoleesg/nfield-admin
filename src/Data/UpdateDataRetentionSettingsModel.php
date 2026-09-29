<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data;

use Spatie\LaravelData\Data;

/**
 * Request body for PUT /v2/surveys/{surveyId}/dataRetentionSettings; the
 * period is in days and must be one of the possible values GET returns.
 *
 * Mirrors NfieldPublicApi.Models.UpdateDataRetentionSettingsModel.
 */
final class UpdateDataRetentionSettingsModel extends Data
{
    public function __construct(
        public int $retentionPeriod,
    ) {}
}
