<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyFieldworkEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveyFieldworkCountsResponseModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFieldwork\SurveysFieldworkStopRequestModel;
use Nikoleesg\NfieldAdmin\Enums\InterviewingRestrictionTypeEnum;
use Nikoleesg\NfieldAdmin\Enums\SurveyFieldworkStatusEnum;

class SurveyFieldworkService
{
    public function __construct(
        protected SurveyFieldworkEndpointInterface $surveyFieldworkEndpoint,
        protected readonly string $surveyId,
    ) {}

    public function start(): void
    {
        $this->surveyFieldworkEndpoint->start($this->surveyId);
    }

    /**
     * The fieldwork status as an enum.
     *
     * `tryFrom` rather than `from`: the API documents no enumeration for this
     * endpoint and `2` has never been observed, so an unmapped value returns
     * null instead of throwing. Use {@see statusCode()} for the raw integer.
     */
    public function status(): ?SurveyFieldworkStatusEnum
    {
        return SurveyFieldworkStatusEnum::tryFrom($this->statusCode());
    }

    /**
     * The raw fieldwork status code, including values the enum does not map.
     */
    public function statusCode(): int
    {
        return $this->surveyFieldworkEndpoint->status($this->surveyId);
    }

    public function counts(): SurveyFieldworkCountsResponseModel
    {
        return SurveyFieldworkCountsResponseModel::from(
            $this->surveyFieldworkEndpoint->counts($this->surveyId)
        );
    }

    public function stop(
        array|SurveysFieldworkStopRequestModel|InterviewingRestrictionTypeEnum $data = InterviewingRestrictionTypeEnum::BlockEverything
    ): void {
        if ($data instanceof InterviewingRestrictionTypeEnum) {
            $data = new SurveysFieldworkStopRequestModel($data);
        }

        $payload = SurveysFieldworkStopRequestModel::from($data)->toArray();

        $this->surveyFieldworkEndpoint->stop($this->surveyId, $payload);
    }
}
