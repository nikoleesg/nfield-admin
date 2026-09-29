<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

/**
 * @method static \Nikoleesg\NfieldAdmin\Services\SurveyCollectionService surveys()
 * @method static \Nikoleesg\NfieldAdmin\Services\SurveyResourceUsageService surveyResourceUsage()
 * @method static \Nikoleesg\NfieldAdmin\Services\BackgroundActivitiesService backgroundActivities()
 * @method static \Nikoleesg\NfieldAdmin\Services\CapiInterviewerCollectionService capiInterviewers()
 * @method static \Nikoleesg\NfieldAdmin\Services\EventSubscriptionCollectionService eventSubscriptions()
 * @method static \Nikoleesg\NfieldAdmin\Services\InterviewersWorklogService interviewersWorklog()
 * @method static \Nikoleesg\NfieldAdmin\Services\RoleService roles()
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
