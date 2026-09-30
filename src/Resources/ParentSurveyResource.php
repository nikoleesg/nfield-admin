<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\ParentSurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveCheckMinSuccessfulsBeforeAutoStartModel;
use Nikoleesg\NfieldAdmin\Services\ParentSurveyService;
use Nikoleesg\NfieldAdmin\Services\ParentSurveyWaveCollectionService;
use Nikoleesg\NfieldAdmin\Traits\ResolvesScopedServices;
use Nikoleesg\NfieldAdmin\Traits\ScopedToParentSurvey;

/**
 * One parent survey, reached through
 * `NfieldManager::parentSurveys()->forParentSurvey($id)`: its waves, and its
 * own setting, which it hands to {@see ParentSurveyService}.
 */
class ParentSurveyResource implements ParentSurveyScopedInterface
{
    use ResolvesScopedServices;
    use ScopedToParentSurvey;

    public function waves(): ParentSurveyWaveCollectionService
    {
        return $this->resolveService(ParentSurveyWaveCollectionService::class);
    }

    public function checkMinSuccessfulsBeforeAutoStart(): WaveCheckMinSuccessfulsBeforeAutoStartModel
    {
        return $this->item()->checkMinSuccessfulsBeforeAutoStart();
    }

    /**
     * @param  bool|array<string, mixed>|WaveCheckMinSuccessfulsBeforeAutoStartModel  $data
     */
    public function updateCheckMinSuccessfulsBeforeAutoStart(bool|array|WaveCheckMinSuccessfulsBeforeAutoStartModel $data): void
    {
        $this->item()->updateCheckMinSuccessfulsBeforeAutoStart($data);
    }

    private function item(): ParentSurveyService
    {
        return $this->resolveService(ParentSurveyService::class);
    }
}
