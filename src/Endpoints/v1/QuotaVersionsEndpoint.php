<?php


namespace Nikoleesg\NfieldAdmin\Endpoints\v1;


use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\HttpClient;

class QuotaVersionsEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/QuotaVersions';

    protected HttpClient $httpClient;

    public function __construct(string $surveyId)
    {
        $this->resourcePath = Str::replace('{surveyId}', $surveyId, $this->resourcePath);

        $this->httpClient = new HttpClient();
    }

    public function index()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $quotaVersions = json_decode($response->body(), true);

        return $resourcePath;
    }
}
