<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use Nikoleesg\NfieldAdmin\Services\NfieldManagerService;

/**
 * @method static \Nikoleesg\NfieldAdmin\Services\SurveyService surveys()
 * @method static \Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityResponseModel getBackgroundActivity(string $activityId)
 * @method static \Nikoleesg\NfieldAdmin\Services\CapiInterviewerService capiInterviewers()
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
