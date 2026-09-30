<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyInterviewQualityCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\InterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\ManagerInterviewDetailsModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\QualityNewStateChangeModel;
use Nikoleesg\NfieldAdmin\Traits\ScopedToSurvey;

/**
 * A survey's interview quality records, reached through `$survey->interviewQuality()`.
 *
 * Services mirror the endpoint naming: this pairs with
 * SurveyInterviewQualityCollectionEndpoint, and {@see SurveyInterviewQualityService}
 * with SurveyInterviewQualityEndpoint.
 */
class SurveyInterviewQualityCollectionService implements SurveyScopedInterface
{
    use ScopedToSurvey;

    public function __construct(
        protected SurveyInterviewQualityCollectionEndpointInterface $surveyInterviewQualityCollectionEndpoint,
    ) {}

    /** @return Collection<int, InterviewDetailsModel> */
    public function list(): Collection
    {
        return InterviewDetailsModel::collect(
            $this->surveyInterviewQualityCollectionEndpoint->list($this->getSurveyId()),
            Collection::class
        );
    }

    /**
     * Change the quality state of an interview. The API takes the interview
     * id in the body of a PUT to the collection.
     *
     * @param  array<string, mixed>|QualityNewStateChangeModel  $data
     */
    public function update(array|QualityNewStateChangeModel $data): ManagerInterviewDetailsModel
    {
        $payload = QualityNewStateChangeModel::from($data)->toArray();

        return ManagerInterviewDetailsModel::from(
            $this->surveyInterviewQualityCollectionEndpoint->update($this->getSurveyId(), $payload)
        );
    }

    /**
     * The quality record of one interview. The id is a string on these paths.
     */
    public function forInterview(string $interviewId): SurveyInterviewQualityService
    {
        return app(SurveyInterviewQualityService::class)
            ->setSurveyId($this->getSurveyId())
            ->setQualityInterviewId($interviewId);
    }
}
