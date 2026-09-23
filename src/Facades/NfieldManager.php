<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

/**
 * @method static \Nikoleesg\NfieldAdmin\Services\SurveyService surveys()
 * @method static \Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel getBackgroundActivity(string $activityId)
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel> listCapiInterviewers()
 * @method static \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel> findCapiInterviewers(array $filter = [])
 * @method static \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerResponseModel createCapiInterviewer(array|\Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestModel $data)
 * @method static \Nikoleesg\NfieldAdmin\Data\CapiInterviewers\CapiInterviewerModel getByClientId(string $clientInterviewerId)
 * @method static \Nikoleesg\NfieldAdmin\Resources\CapiInterviewerResource withCapiInterviewer(string $interviewerId)
 * @method static \Nikoleesg\NfieldAdmin\Data\Roles\UserRoleModel getUserRole()
 * @method static \Illuminate\Support\Collection<string, \Illuminate\Support\Collection<int, \Nikoleesg\NfieldAdmin\Data\Roles\PermissionModel>> listRoles()
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
