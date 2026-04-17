<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySettingsEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGeneralSettingsUpdateModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveySettingModel;
use Nikoleesg\NfieldAdmin\Enums\SurveySettingNameEnum;
use Spatie\LaravelData\DataCollection;

class SurveySettingsService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveySettingsEndpointInterface $surveySettingsEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function list(): DataCollection
    {
        $data = $this->surveySettingsEndpoint->listSettings($this->surveyId);

        return SurveySettingModel::collect($data, DataCollection::class);
    }

    public function set(string|SurveySettingNameEnum $name, string $value): SurveySettingModel
    {
        $setting = new SurveySettingModel(
            name: $name instanceof SurveySettingNameEnum ? $name->value : $name,
            value: $value,
        );

        $data = $this->surveySettingsEndpoint->addOrUpdateSetting($this->surveyId, $setting->toArray());

        return SurveySettingModel::from($data);
    }

    public function getGeneral(): SurveyGeneralSettingsModel
    {
        $data = $this->surveySettingsEndpoint->getGeneralSettings($this->surveyId);

        return SurveyGeneralSettingsModel::from($data);
    }

    public function updateGeneral(SurveyGeneralSettingsUpdateModel $model): void
    {
        $this->surveySettingsEndpoint->updateGeneralSettings($this->surveyId, $model->toArray());
    }
}
