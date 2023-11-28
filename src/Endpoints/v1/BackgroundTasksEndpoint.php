<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Nikoleesg\NfieldAdmin\Data\BackgroundTaskDTO;

class BackgroundTasksEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/BackgroundTasks';

    public function index()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        $backgroundTasks = json_decode($response->body(), true);

        return BackgroundTaskDTO::collection($backgroundTasks);
    }

    public function show(string $taskId): ?BackgroundTaskDTO
    {
        $resourcePath = $this->resourcePath . "/$taskId";

        $response = $this->httpClient->get($resourcePath);

        $backgroundTask = json_decode($response->body(), true);

        try {
            $backgroundTaskData = BackgroundTaskDTO::from($backgroundTask);
        } catch (CannotCreateData $exception) {
            $backgroundTaskData = BackgroundTaskDTO::optional(null);
        }

        return $backgroundTaskData;
    }


}
