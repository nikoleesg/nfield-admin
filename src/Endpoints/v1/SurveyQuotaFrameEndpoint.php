<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;
use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\SamplingPointData;
use Spatie\LaravelData\Exceptions\CannotCreateData;

class SurveyQuotaFrameEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/SurveyQuotaFrame';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    /**
     * Get a list of all sampling points for a survey.
     *
     * @return DataCollection
     */
    public function index(): DataCollection
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $samplingPoints = json_decode($response->body(), true);

        return SamplingPointData::collection($samplingPoints);
    }

    /**
     * Get the details of a specific sampling point.
     *
     * @param string $samplingPointId
     * @return SamplingPointData
     */
    public function show(string $samplingPointId): SamplingPointData
    {
        $resourcePath = $this->resourcePath . "/$samplingPointId";

        $response = $this->httpClient->get($resourcePath);

        $samplingPoint = json_decode($response->body(), true);

        try {
            $samplingPointData = SamplingPointData::from($samplingPoint);

        } catch (CannotCreateData $exception) {
            $samplingPointData = SamplingPointData::optional(null);
        }

        return $samplingPointData;
    }

    /**
     * Delete a specified sampling point.
     *
     * @param string $samplingPointId
     * @return bool
     */
    public function destroy(string $samplingPointId): bool
    {
        $resourcePath = $this->resourcePath . "/$samplingPointId";

        $response = $this->httpClient->delete($resourcePath);

        return empty($response->body());
    }

    public function update()
    {

    }

    /**
     * Create a new sampling point.
     *
     * @param SamplingPointData $samplingPointData
     * @return SamplingPointData
     */
    public function store(SamplingPointData $samplingPointData): SamplingPointData
    {
        $resourcePath = $this->resourcePath;

        // Transform to Nfield API SurveyModel
        $samplingPointCreationModel = array_change_key_casing($samplingPointData->toArray(), CASE_STUDLY);

        $response = $this->httpClient->post($resourcePath, true, $samplingPointCreationModel);

        $samplingPoint = json_decode($response->body(), true);

        try {
            $samplingPointData = SamplingPointData::from($samplingPoint);
        } catch (CannotCreateData $exception) {
            $samplingPointData = SamplingPointData::optional(null);
        }

        return $samplingPointData;
    }

    /**
     * Returns the number of samplingPoints of the survey.
     *
     * @return int
     */
    public function count(): int
    {
        $resourcePath = $this->resourcePath . "/Count";

        $response = $this->httpClient->get($resourcePath);

        $samplingPointCnt = json_decode($response->body(), true);

        return $samplingPointCnt;
    }
}
