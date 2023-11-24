<?php

namespace Nikoleesg\NfieldAdmin\Actions\SamplingPoint;

use Lorisleiva\Actions\Concerns\AsAction;
use Nikoleesg\NfieldAdmin\Facades\SamplingPoint;

class DeleteSamplingPointAction
{
    use AsAction;

    /**
     * Handle the action.
     *
     * @param string $surveyId
     * @param string $samplingPointId
     */
    public function handle(string $surveyId, string $samplingPointId)
    {
        SamplingPoint::setSurvey($surveyId)->delete($samplingPointId);
    }
}
