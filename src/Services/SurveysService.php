<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Endpoints\v1\SurveysEndpoint;
use Nikoleesg\NfieldAdmin\Endpoints\v1\QuotaVersionsEndpoint;
use Nikoleesg\NfieldAdmin\Enums\ChannelEnum;
use Spatie\LaravelData\DataCollection;

class SurveysService
{
    protected SurveysEndpoint $surveysEndpoint;

    protected QuotaVersionsEndpoint $quotaVersionsEndpoint;

    protected ?string $surveyId;

    protected ?DataCollection $surveyCollection;

    public function __construct(?string $surveyId = null)
    {
        $this->surveyId = $surveyId;

        $this->surveysEndpoint = new SurveysEndpoint();

        if ($this->isSurveyConfigured()) {
            $this->initEndpoints();
        }
    }

    protected function initEndpoints(): self
    {
        $this->quotaVersionsEndpoint = new QuotaVersionsEndpoint($this->surveyId);

        return $this;
    }

    protected function isSurveyConfigured(): bool
    {
        return isset($this->surveyId);
    }

    public function setSurvey(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        $this->initEndpoints();

        return $this;
    }



    /**
     * Find Survey by survey_id
     * Supports multiple id providing by array
     */
    public function find(string|array $surveyId): DataCollection|SurveyData|null
    {
        $this->retrieveSurveys();

        $lookForId = is_string($surveyId) ? [$surveyId] : $surveyId;

        $this->whereIn('survey_id', $lookForId);

        return is_string($surveyId) ? $this->first() : $this->get();
    }

    /**
     * Find Survey by survey_name
     * Supports multiple name providing by array
     */
    public function findByName(string|array $surveyName): DataCollection|SurveyData|null
    {
        $this->retrieveSurveys();

        $lookForName = is_string($surveyName) ? [$surveyName] : $surveyName;

        $this->whereIn('survey_name', $lookForName);

        return is_string($surveyName) ? $this->first() : $this->get();
    }

    /**
     * Search Survey by a keyword
     */
    public function search(string $keyWord): DataCollection
    {
        $this->retrieveSurveys();

        $this->whereLike('survey_name', $keyWord);

        return $this->get();
    }

    /**
     * Retrieves all of the values for a given key
     */
    public function pluck(string $valueName, string $keyName = null): Collection
    {
        if (! isset($this->surveyCollection)) {
            $this->retrieveSurveys();
        }

        // TODO: handle verification of valueName and KeyName

        $plucked = collect();

        $this->surveyCollection
            ->each(function ($item, $key) use ($plucked, $valueName, $keyName) {

                if (! is_null($keyName)) {
                    $plucked->put($item->$keyName, $item->$valueName);
                } else {
                    $plucked->push($item->$valueName);
                }

            });

        return $plucked;
    }

    /**
     * Retrieve Surveys from Nfield
     *
     * @return $this
     */
    protected function retrieveSurveys(): self
    {
        $this->surveyCollection = $this->surveysEndpoint->index();

        return $this;
    }

    /**
     * @return $this
     */
    public function where(string $property, mixed $value): self
    {
        if (! isset($this->surveyCollection)) {
            $this->retrieveSurveys();
        }

        $this->surveyCollection = $this->surveyCollection
            ->filter(function (SurveyData $surveyData, int $key) use ($property, $value) {

                return $value == $surveyData->$property;

            });

        return $this;
    }

    /**
     * @param  mixed  $value
     * @return $this
     */
    public function whereLike(string $property, string $value): self
    {
        if (! isset($this->surveyCollection)) {
            $this->retrieveSurveys();
        }

        $this->surveyCollection = $this->surveyCollection
            ->filter(function (SurveyData $surveyData, int $key) use ($property, $value) {

                return Str::contains($surveyData->$property, $value);

            });

        return $this;
    }

    /**
     * @return $this
     */
    public function whereIn(string $property, array $values): self
    {
        if (! isset($this->surveyCollection)) {
            $this->retrieveSurveys();
        }

        $this->surveyCollection = $this->surveyCollection
            ->filter(function (SurveyData $surveyData, int $key) use ($property, $values) {

                return in_array($surveyData->$property, $values);

            });

        return $this;
    }

    /**
     * Return SurveyData
     */
    public function first(): ?SurveyData
    {
        if (! isset($this->surveyCollection)) {
            $this->retrieveSurveys();
        }

        $result = $this->surveyCollection->first();

        unset($this->surveyCollection);

        return $result;
    }

    /**
     * Return SurveyData Collection
     */
    public function get(): DataCollection
    {
        if (! isset($this->surveyCollection)) {
            $this->retrieveSurveys();
        }

        $results = $this->surveyCollection->values();

        unset($this->surveyCollection);

        return $results;
    }

    /**
     * Create Survey
     */
    public function createSurvey(SurveyData $surveyData): SurveyData
    {
        return $this->surveysEndpoint->store($surveyData);
    }

    /**
     * Create Survey (with Survey Name and Channel)
     */
    public function quickCreate(string $surveyName, ChannelEnum|string $channel = ''): SurveyData
    {
        $surveyData = SurveyData::from($surveyName, $channel);

        return $this->createSurvey($surveyData);
    }

    /**
     * Delete a Survey
     */
    public function deleteSurvey(SurveyData|string $survey): bool
    {
        if (is_string($survey)) {
            return $this->deleteSurvey($this->find($survey));
        }

        return $this->surveysEndpoint->destroy($survey->survey_id);
    }

    /**
     * Delete Survey from retrieved Collection
     */
    public function delete(): bool
    {
        if (! isset($this->surveyCollection) || $this->surveyCollection->count() == 0) {
            return false;
        }

        foreach ($this->surveyCollection as $survey) {
            $this->deleteSurvey($survey);
        }

        unset($this->surveyCollection);

        return true;
    }

    /**
     * |------------------------------------------------------------------------
     * | Quota Versions
     * |------------------------------------------------------------------------
     */
    public function getQuotaVersions()
    {
        $this->quotaVersionsEndpoint->index();
    }
}
