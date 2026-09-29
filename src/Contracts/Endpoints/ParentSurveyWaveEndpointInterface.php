<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface ParentSurveyWaveEndpointInterface
{
    /**
     * Create a new Online wave by copying an existing wave of the same parent survey.
     *
     * @param  array<string, mixed>  $parentSurveyWaveCopyRequestModel
     * @return array<string, mixed>
     */
    public function copy(string $parentSurveyId, string $waveId, array $parentSurveyWaveCopyRequestModel): array;
}
