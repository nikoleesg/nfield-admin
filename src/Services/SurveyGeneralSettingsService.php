<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGeneralSettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsUpdateModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's general settings (name, client, description, owner, cleanup
 * exclusion), reached through `$survey->generalSettings()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveyGeneralSettingsEndpoint. The named settings list is
 * {@see SurveySettingsService}.
 */
class SurveyGeneralSettingsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyGeneralSettingsEndpointInterface $surveyGeneralSettingsEndpoint,
    ) {}

    public function get(): SurveyGeneralSettingsModel
    {
        return SurveyGeneralSettingsModel::from($this->surveyGeneralSettingsEndpoint->get($this->getSurveyId()));
    }

    /**
     * Update the given fields; fields not set on the model are not sent.
     *
     * @param  array<string, mixed>|SurveyGeneralSettingsUpdateModel  $data
     */
    public function update(array|SurveyGeneralSettingsUpdateModel $data): void
    {
        $payload = SurveyGeneralSettingsUpdateModel::from($data)->toArray();

        $this->surveyGeneralSettingsEndpoint->update($this->getSurveyId(), $payload);
    }
}
