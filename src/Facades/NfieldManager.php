<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

/**
 * @method static \Illuminate\Support\Collection<\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel> listSurveys()
 * @method static \Illuminate\Support\Collection<\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel> findSurveys(array $filter)
 * @method static \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel createSurvey(\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel $surveyModel)
 * @method static \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel createSurveyFromBlueprint(\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFromBlueprintModel $model)
 * @method static \Illuminate\Support\Collection<\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel> searchRespondent(string $value)
 * @method static \Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel getBackgroundActivity(string $activityId)
 * @method static \Illuminate\Support\Collection listCapiInterviewers()
 * @method static \Illuminate\Support\Collection findCapiInterviewers(array $filter = [])
 * @method static \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseData createCapiInterviewer(\Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestData $data)
 * @method static \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerData getByClientId(string $clientInterviewerId)
 * @method static \Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource withCapiInterviewer(string $interviewerId)
 * @method static \Nikoleesg\NfieldAdmin\Resources\SurveyResource withSurvey(string $surveyId)
 * @method static \Nikoleesg\NfieldAdmin\Resources\BlueprintSurveyResource withBlueprintSurvey(string $blueprintId)
 * @method static \Nikoleesg\NfieldAdmin\Resources\SamplingPointResource withSurveySamplingPoint(string $surveyId, string $samplingPoint)
 *
 * @see NfieldManagerService
 */
class NfieldManager extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'nfield-manager';
    }
}
