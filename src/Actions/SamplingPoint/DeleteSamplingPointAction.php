<?php

namespace Nikoleesg\NfieldAdmin\Actions\SamplingPoint;

use Spatie\QueueableAction\QueueableAction;
use Nikoleesg\NfieldAdmin\Services\SamplingPointService;

class DeleteSamplingPointAction
{
    use QueueableAction;

    protected SamplingPointService $svcSamplingPoint;

    /**
     * Create a new action instance.
     *
     */
    public function __construct()
    {
        $this->svcSamplingPoint = new SamplingPointService();
    }

    /**
     * Execute the action.
     *
     * @param string $surveyId
     * @param string $samplingPointId
     * @return mixed
     */
    public function execute(string $surveyId, string $samplingPointId)
    {
        $this->svcSamplingPoint->setSurvey($surveyId)->delete($samplingPointId);

    }
}
