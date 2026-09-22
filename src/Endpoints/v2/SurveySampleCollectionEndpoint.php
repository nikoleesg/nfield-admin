<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;

final class SurveySampleCollectionEndpoint extends BaseEndpoint implements SurveySampleCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/$this->version/surveys";
    }

    public function download(string $surveyId): string
    {
        $uri = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->get($uri)->body();
    }

    public function upload(string $surveyId, string $sampleData, string $fileName = 'sample.csv'): array
    {
        $uri = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->postMultipart($uri, 'File', $sampleData, $fileName)->json();
    }

    public function block(string $surveyId, array $sampleFilterModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'block');

        return $this->httpClient->put($uri, $sampleFilterModel)->json();
    }

    public function create(string $surveyId, array $surveyCreateSampleColumnModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'create');

        return $this->httpClient->post($uri, $surveyCreateSampleColumnModel)->json();
    }

    public function reset(string $surveyId, array $sampleFilterModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'reset');

        return $this->httpClient->put($uri, $sampleFilterModel)->json();
    }

    public function clear(string $surveyId, array $clearSurveySampleModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'clear');

        return $this->httpClient->put($uri, $clearSurveySampleModel)->json();
    }

    public function requestDownload(string $surveyId, string $fileName): array
    {
        $uri = $this->resourceActionPath($surveyId, "sampleDataDownload/$fileName");

        return $this->httpClient->post($uri, [])->json();
    }
}
