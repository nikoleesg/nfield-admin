<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyDataRetentionSettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\GetDataRetentionSettingsModel;
use Nikoleesg\NfieldAdmin\Data\UpdateDataRetentionSettingsModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's data retention settings, reached through
 * `$survey->dataRetentionSettings()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveyDataRetentionSettingsEndpoint.
 */
class SurveyDataRetentionSettingsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyDataRetentionSettingsEndpointInterface $surveyDataRetentionSettingsEndpoint,
    ) {}

    public function get(): GetDataRetentionSettingsModel
    {
        return GetDataRetentionSettingsModel::from(
            $this->surveyDataRetentionSettingsEndpoint->get($this->getSurveyId())
        );
    }

    /**
     * Set the retention period, in days; it must be one of get()->possibleValues.
     *
     * @param  int|array<string, mixed>|UpdateDataRetentionSettingsModel  $data
     */
    public function update(int|array|UpdateDataRetentionSettingsModel $data): void
    {
        $payload = UpdateDataRetentionSettingsModel::from(
            is_int($data) ? ['retentionPeriod' => $data] : $data
        )->toArray();

        $this->surveyDataRetentionSettingsEndpoint->update($this->getSurveyId(), $payload);
    }
}
