<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Endpoints\v1\SurveysEndpoint;
use Spatie\LaravelData\DataCollection;

class SurveyService
{
    private ?string $surveyId;

    protected SurveysEndpoint $surveyEndpoint;

    public function __construct(string $surveyId = null)
    {
        $this->surveyId = $surveyId;

        $this->surveyEndpoint = new SurveysEndpoint();
    }

    /**
     * Set SurveyId
     *
     * @return $this
     */
    public function setSurvey(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    /**
     * Get all surveys from Nfield
     */
    public function getRemoteSurveys(): DataCollection
    {
        return $this->surveyEndpoint->index();
    }

    /**
     * Get survey from Nfield
     */
    public function getRemoteSurvey(string $surveyId = null): ?SurveyData
    {
        $survey_id = $surveyId ?? $this->surveyId;

        return ! is_null($survey_id) ? $this->surveyEndpoint->show($survey_id) : SurveyData::optional(null);
    }
}
