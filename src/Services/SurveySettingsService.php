<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Nikoleesg\NfieldAdmin\Enums\SurveySettingNameEnum;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's named settings, reached through `$survey->settings()`.
 *
 * Services mirror the endpoint naming: this pairs with SurveySettingsEndpoint.
 * The general settings are {@see SurveyGeneralSettingsService}.
 */
class SurveySettingsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveySettingsEndpointInterface $surveySettingsEndpoint,
    ) {}

    /** @return Collection<int, SurveySettingModel> */
    public function list(): Collection
    {
        $data = $this->surveySettingsEndpoint->list($this->getSurveyId());

        return SurveySettingModel::collect($data, Collection::class);
    }

    public function set(string|SurveySettingNameEnum $name, string $value): SurveySettingModel
    {
        $setting = new SurveySettingModel(
            name: $name instanceof SurveySettingNameEnum ? $name->value : $name,
            value: $value,
        );

        $data = $this->surveySettingsEndpoint->set($this->getSurveyId(), $setting->toArray());

        return SurveySettingModel::from($data);
    }
}
