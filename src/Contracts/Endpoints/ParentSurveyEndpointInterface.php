<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ParentSurveyEndpointInterface
{
    /**
     * Whether the parent survey checks the minimum successfuls before a wave auto-starts.
     *
     * @return array<string, mixed>
     */
    public function getCheckMinSuccessfulsBeforeAutoStart(string $parentSurveyId): array;

    /**
     * Set whether the parent survey checks the minimum successfuls before a wave auto-starts.
     *
     * @param  array<string, mixed>  $waveCheckMinSuccessfulsBeforeAutoStartModel
     */
    public function updateCheckMinSuccessfulsBeforeAutoStart(string $parentSurveyId, array $waveCheckMinSuccessfulsBeforeAutoStartModel): void;
}
