<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyInterviewQualityCollectionEndpointInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function get(string $surveyId): array;

    /**
     * @param  array<string, mixed>  $qualityNewStateChangeModel
     * @return array<string, mixed>
     */
    public function updateQuality(string $surveyId, array $qualityNewStateChangeModel): array;
}
