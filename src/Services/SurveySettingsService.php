<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyGeneralSettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Nikoleesg\NfieldAdmin\Enums\SurveySettingNameEnum;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveySettingsService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveySettingsEndpointInterface $surveySettingsEndpoint,
        protected SurveyGeneralSettingsEndpointInterface $surveyGeneralSettingsEndpoint,
    ) {}

    /** @return Collection<int, SurveySettingModel> */
    public function list(): Collection
    {
        $data = $this->surveySettingsEndpoint->listSettings($this->getSurveyId());

        return SurveySettingModel::collect($data, Collection::class);
    }

    public function set(string|SurveySettingNameEnum $name, string $value): SurveySettingModel
    {
        $setting = new SurveySettingModel(
            name: $name instanceof SurveySettingNameEnum ? $name->value : $name,
            value: $value,
        );

        $data = $this->surveySettingsEndpoint->addOrUpdateSetting($this->getSurveyId(), $setting->toArray());

        return SurveySettingModel::from($data);
    }

    public function getGeneral(): SurveyGeneralSettingsModel
    {
        $data = $this->surveyGeneralSettingsEndpoint->getGeneralSettings($this->getSurveyId());

        return SurveyGeneralSettingsModel::from($data);
    }

    public function updateGeneral(array|SurveyGeneralSettingsUpdateModel $data): void
    {
        $payload = SurveyGeneralSettingsUpdateModel::from($data)->toArray();

        $this->surveyGeneralSettingsEndpoint->updateGeneralSettings($this->getSurveyId(), $payload);
    }
}
