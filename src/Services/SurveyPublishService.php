<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyPublishEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyPublishStateModel;
use Nikoleesg\NfieldAdmin\Enums\SurveyPackageTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyPublishForceUpgradeEnum;

class SurveyPublishService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveyPublishEndpointInterface $surveyPublishEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function getState(): SurveyPublishStateModel
    {
        return SurveyPublishStateModel::from(
            $this->surveyPublishEndpoint->getPublishState($this->surveyId)
        );
    }

    public function publish(SurveyPackageTypeEnum $packageType, SurveyPublishForceUpgradeEnum $forceUpgrade): bool
    {
        return $this->surveyPublishEndpoint->publish($this->surveyId, [
            'packageType' => $packageType->value,
            'forceUpgrade' => $forceUpgrade->value,
        ]);
    }

    public function start(SurveyPackageTypeEnum $packageType, SurveyPublishForceUpgradeEnum $forceUpgrade): BackgroundActivityStatus
    {
        return BackgroundActivityStatus::from(
            $this->surveyPublishEndpoint->startPublish($this->surveyId, [
                'packageType' => $packageType->value,
                'forceUpgrade' => $forceUpgrade->value,
            ])
        );
    }

    public function publishLive(): bool
    {
        return $this->publish(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::NoUpgrade);
    }

    public function forcePublishLive(): bool
    {
        return $this->publish(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::ForceUpgrade);
    }

    public function publishTest(): bool
    {
        return $this->publish(SurveyPackageTypeEnum::Test, SurveyPublishForceUpgradeEnum::NoUpgrade);
    }

    public function startPublishLive(): BackgroundActivityStatus
    {
        return $this->start(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::NoUpgrade);
    }

    public function startForcePublishLive(): BackgroundActivityStatus
    {
        return $this->start(SurveyPackageTypeEnum::Live, SurveyPublishForceUpgradeEnum::ForceUpgrade);
    }
}
