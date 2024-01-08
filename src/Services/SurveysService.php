<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\SurveyData;
use Nikoleesg\NfieldAdmin\Endpoints\v1;
use Nikoleesg\NfieldAdmin\Enums\ChannelEnum;
use Nikoleesg\NfieldAdmin\Models\BackgroundActivity;
use Nikoleesg\NfieldAdmin\Data\SurveyDataRequestDTO;
use Spatie\LaravelData\DataCollection;

class SurveysService
{
    protected v1\SurveysEndpoint $surveysEndpoint;

    protected v1\QuotaVersionsEndpoint $quotaVersionsEndpoint;

    protected v1\SurveyQuotaFrameEndpoint $surveyQuotaFrameEndpoint;

    protected v1\SurveysQuotaTargetsEndpoint $quotaTargetsEndpoint;

    protected v1\SurveyResponseCodesEndpoint $responseCodesEndpoint;

    protected v1\SurveyDataEndpoint $dataEndpoint;

    protected v1\SurveySampleEndpoint $sampleEndpoint;

    protected ?string $surveyId;

    protected ?DataCollection $surveyCollection;

    public function __construct(?string $surveyId = null)
    {
        $this->surveyId = $surveyId;

        $this->surveysEndpoint = new v1\SurveysEndpoint();

        if ($this->isSurveyConfigured()) {
            $this->initEndpoints();
        }
    }

    protected function initEndpoints(): self
    {
        $this->quotaVersionsEndpoint = new v1\QuotaVersionsEndpoint($this->surveyId);

        $this->surveyQuotaFrameEndpoint = new v1\SurveyQuotaFrameEndpoint($this->surveyId);

        $this->quotaTargetsEndpoint = new v1\SurveysQuotaTargetsEndpoint($this->surveyId);

        $this->responseCodesEndpoint = new v1\SurveyResponseCodesEndpoint($this->surveyId);

        $this->dataEndpoint = new v1\SurveyDataEndpoint($this->surveyId);

        $this->sampleEndpoint = new v1\SurveySampleEndpoint($this->surveyId);

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
     * | Survey Quota Frame
     * |------------------------------------------------------------------------
     */
    public function getSurveyQuotaFrame()
    {
        return $this->surveyQuotaFrameEndpoint->index();
    }

    public function getSurveyQuotaVariableDefinition()
    {
        $variableDefinitions = $this->getSurveyQuotaFrame()->variable_definitions;

        return collect($variableDefinitions->toArray())
            ->mapWithKeys(function ($item, $key) {
                return [$item['id'] => $item['name']];
            })
            ->toArray();
    }

    public function getSurveyQuotaLevelDefinitionModel(?string $quotaVariableId = null)
    {
        $variableDefinitions = $this->getSurveyQuotaFrame()->variable_definitions;

        return collect($variableDefinitions->toArray())
            ->filter(function ($item) use ($quotaVariableId) {
                return is_null($quotaVariableId) || $item['id'] === $quotaVariableId;
            })
            ->mapWithKeys(function ($item, $key) {
                return [$item['id'] => collect($item['levels'])->mapWithKeys(fn($item) => [$item['id'] => $item['name']])];
            })
            ->when(!is_null($quotaVariableId), function (Collection $collection, int $value) {
                return $collection->first();
            })
            ->toArray();
    }

    public function getSurveyQuotaFrameVariable()
    {
        $frameVariables = $this->getSurveyQuotaFrame()->frame_variables;

        return collect($frameVariables)
            ->mapWithKeys(function ($item, $key) {
                return [$item['definition_id'] => $item['levels']];
            })
            ->toArray();
    }



    /**
     * |------------------------------------------------------------------------
     * | Quota Versions
     * |------------------------------------------------------------------------
     */
    public function getQuotaVersions()
    {
        return $this->quotaVersionsEndpoint->index();
    }

    public function getQuotaFrame(string $eTag)
    {
        return $this->quotaVersionsEndpoint->show($eTag);
    }

    /**
     * |------------------------------------------------------------------------
     * | Quota Targets
     * |------------------------------------------------------------------------
     */
    public function getQuotaTargets()
    {
        return $this->quotaTargetsEndpoint->index();

    }

    /**
     * |------------------------------------------------------------------------
     * | Response Codes
     * |------------------------------------------------------------------------
     */
    public function getResponseCodes()
    {
        return $this->responseCodesEndpoint->index();
    }

    public function getResponseCode(int $responseCode)
    {
        return $this->responseCodesEndpoint->show($responseCode);
    }

    /**
     * |------------------------------------------------------------------------
     * | Survey Data
     * |------------------------------------------------------------------------
     */
    public function requestDownloadData(?SurveyDataRequestDTO $surveyDataRequestDTO = null)
    {
        $surveyDataRequest = !is_null($surveyDataRequestDTO) ? $surveyDataRequestDTO : $this->defaultSurveyDataRequestModel();

        return $this->dataEndpoint->store($surveyDataRequest);
    }

    public function requestDownloadInterviewData(int $interviewId)
    {
        $surveyDataRequest = SurveyDataRequestDTO::from([
            'FileName' => 'PA_' . Str::padLeft($interviewId, 8, '0'),
        ]);

        return $this->dataEndpoint->store($surveyDataRequest, $interviewId);
    }

    protected function defaultSurveyDataRequestModel(): SurveyDataRequestDTO
    {
        return SurveyDataRequestDTO::from([
            'FileName' => 'PA_SurveyData_'.Carbon::now()->format('Ymd_His'),
            'StartDate' => Carbon::parse('6 days ago', 'Asia/Singapore')->startOfDay()->tz('UTC'),
            'EndDate' => Carbon::today('Asia/Singapore')->endOfDay()->tz('UTC'),
            'SurveyVersion' => null,
            'IncludeSuccessful' => true,
            'IncludeScreenOut' => false,
            'IncludeDroppedOut' => false,
            'IncludeRejected' => false,
            'IncludeTestData' => false,
            'IncludeClosedAnswers' => true,
            'IncludeOpenAnswers' => true,
            'IncludeParaData' => true,
            'IncludeCapturedMediaFiles' => false,
            'IncludeVarFile' => false,
            'IncludeQuestionnaireScript' => false,
            'IncludeAuditLog' => false,
            'CustomColumnName' => null,
            'CustomColumnValue' => null
        ]);
    }


    /**
     * |------------------------------------------------------------------------
     * | Survey Sample
     * |------------------------------------------------------------------------
     */
    public function getSurveySamples()
    {
        return $this->sampleEndpoint->index();
    }


}
