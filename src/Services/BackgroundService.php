<?php


namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Endpoints\v1;
use Nikoleesg\NfieldAdmin\Endpoints\v2;
use Nikoleesg\NfieldAdmin\Models\BackgroundActivity;

class BackgroundService
{
    protected v1\BackgroundActivityEndpoint $activityEndpoint;

    protected v1\BackgroundTasksEndpoint $tasksEndpoint;

    public function __construct()
    {
        $this->activityEndpoint = new v1\BackgroundActivityEndpoint();

        $this->tasksEndpoint = new v1\BackgroundTasksEndpoint();
    }

    public function getBackgroundActivity(string $activityId)
    {
        return $this->activityEndpoint->show($activityId);
    }

    public function getBackgroundTasks()
    {
        return $this->tasksEndpoint->index();
    }

    public function getBackgroundTask(string $taskId)
    {
        return $this->tasksEndpoint->show($taskId);
    }
}
