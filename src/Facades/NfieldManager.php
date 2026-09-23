<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

/**
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel> listSurveys()
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel> findSurveys(array $filter)
 * @method static \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel createSurvey(array|\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyCreateModel $data)
 * @method static \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyModel createSurveyFromBlueprint(array|\Nikoleesg\NfieldAdmin\Data\Surveys\SurveyFromBlueprintModel $data)
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\Surveys\SurveyBaseModel> searchRespondent(string $value)
 * @method static \Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel getBackgroundActivity(string $activityId)
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel> listCapiInterviewers()
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel> findCapiInterviewers(array $filter = [])
 * @method static \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel createCapiInterviewer(array|\Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel $data)
 * @method static \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel getByClientId(string $clientInterviewerId)
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
