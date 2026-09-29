<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\ParentSurveyEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ParentSurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Data\Surveys\Waves\WaveCheckMinSuccessfulsBeforeAutoStartModel;
use Nikoleesg\NfieldAdmin\Resources\ParentSurveyResource;
use Nikoleesg\NfieldAdmin\Traits\ScopedToParentSurvey;

/**
 * Operations on one parent survey, exposed through {@see ParentSurveyResource}.
 *
 * Services mirror the endpoint naming: this pairs with ParentSurveyEndpoint,
 * and {@see ParentSurveyCollectionService} with ParentSurveyCollectionEndpoint.
 */
class ParentSurveyService implements ParentSurveyScopedInterface
{
    use ScopedToParentSurvey;

    public function __construct(
        protected ParentSurveyEndpointInterface $parentSurveyEndpoint,
    ) {}

    public function checkMinSuccessfulsBeforeAutoStart(): WaveCheckMinSuccessfulsBeforeAutoStartModel
    {
        return WaveCheckMinSuccessfulsBeforeAutoStartModel::from(
            $this->parentSurveyEndpoint->getCheckMinSuccessfulsBeforeAutoStart($this->getParentSurveyId())
        );
    }

    /**
     * @param  bool|array<string, mixed>|WaveCheckMinSuccessfulsBeforeAutoStartModel  $data
     */
    public function updateCheckMinSuccessfulsBeforeAutoStart(bool|array|WaveCheckMinSuccessfulsBeforeAutoStartModel $data): void
    {
        $payload = WaveCheckMinSuccessfulsBeforeAutoStartModel::from(
            is_bool($data) ? ['checkMinSuccessfulsBeforeAutoStart' => $data] : $data
        )->toArray();

        $this->parentSurveyEndpoint->updateCheckMinSuccessfulsBeforeAutoStart($this->getParentSurveyId(), $payload);
    }
}
