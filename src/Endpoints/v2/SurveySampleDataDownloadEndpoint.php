<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleDataDownloadEndpointInterface;

final class SurveySampleDataDownloadEndpoint extends BaseEndpoint implements SurveySampleDataDownloadEndpointInterface
{
    protected function buildPath(): string
    {
        return "/{$this->version}/surveys";
    }

    public function requestDownload(string $surveyId, string $fileName): array
    {
        $uri = $this->subResourceItemPath($surveyId, 'sampleDataDownload', $fileName);

        return $this->httpClient->post($uri, [])->json();
    }
}
