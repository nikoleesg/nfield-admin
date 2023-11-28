<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivityDTO;

class BackgroundActivityEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/BackgroundActivities/{activityId}';

    public function show(string $activityId): ?BackgroundActivityDTO
    {
        $this->resourcePath = Str::replace('{activityId}', $activityId, $this->resourcePath);

        $response = $this->httpClient->get($this->resourcePath);

        $backgroundActivity = json_decode($response->body(), true);

        try {
            $backgroundActivityData = BackgroundActivityDTO::from($backgroundActivity);
        } catch (CannotCreateData $exception) {
            $backgroundActivityData = BackgroundActivityDTO::optional(null);
        }

        return $backgroundActivityData;
    }


}
