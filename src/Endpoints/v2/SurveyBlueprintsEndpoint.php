<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveyBlueprintsEndpointInterface;

final class SurveyBlueprintsEndpoint extends BaseEndpoint implements SurveyBlueprintsEndpointInterface
{
    private string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/surveyBlueprints";
    }

    public function update(string $blueprintId, array $updateBlueprintModel): void
    {
        $uri = $this->resourceActionPath($blueprintId, 'update');

        $this->httpClient->put($uri, $updateBlueprintModel);
    }
}
