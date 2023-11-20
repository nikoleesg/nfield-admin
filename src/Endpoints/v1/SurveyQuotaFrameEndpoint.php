<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;
use Illuminate\Http\Client\Response;
use Nikoleesg\NfieldAdmin\HttpClient;
use Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame\SurveyQuotaFrameResponseData;
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
    public function index(): mixed
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $surveyQuotaFrame = json_decode($response->body(), true);

        return SurveyQuotaFrameResponseData::from($surveyQuotaFrame);
    }
}
