<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;


use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;

final class SurveySampleEndpoint extends BaseEndpoint implements SurveySampleEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    /**
     * @param string $surveyId
     * @param array $sampleFilterModel
     * @return array
     */
    public function destroy(string $surveyId, array $sampleFilterModel): array
    {
        $uri = $this->subResourcePath($surveyId, 'sample');

        return $this->httpClient->destroy($uri, $sampleFilterModel)->json();
    }

    /**
     * @param string $surveyId
     * @param int $interviewId
     * @return string
     */
    public function get(string $surveyId, int $interviewId): string
    {
        $uri = $this->subResourceItemPath($surveyId, 'sample', $interviewId);

        return $this->httpClient->get($uri)->body();
    }

    /**
     * @param string $surveyId
     * @param array $surveyUpdateSampleRecordModel
     * @return array
     */
    public function update(string $surveyId, array $surveyUpdateSampleRecordModel): array
    {
        $uri = $this->subResourceActionPath($surveyId, 'sample', 'update');

        return $this->httpClient->put($uri, $surveyUpdateSampleRecordModel)->json();
    }
}
