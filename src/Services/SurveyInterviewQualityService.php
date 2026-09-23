<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\InterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\ManagerInterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\QualityNewStateChangeModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

class SurveyInterviewQualityService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        private readonly SurveyInterviewQualityCollectionEndpointInterface $collectionEndpoint,
        private readonly SurveyInterviewQualityEndpointInterface $itemEndpoint
    ) {}

    /** @return Collection<int, InterviewDetailsModel> */
    public function getInterviews(): Collection
    {
        return InterviewDetailsModel::collect($this->collectionEndpoint->get($this->getSurveyId()), Collection::class);
    }

    public function getInterview(string $interviewId): InterviewDetailsModel
    {
        return InterviewDetailsModel::from($this->itemEndpoint->get($this->getSurveyId(), $interviewId));
    }

    public function updateQuality(array|QualityNewStateChangeModel $data): ManagerInterviewDetailsModel
    {
        $payload = QualityNewStateChangeModel::from($data)->toArray();

        return ManagerInterviewDetailsModel::from($this->collectionEndpoint->updateQuality($this->getSurveyId(), $payload));
    }
}
