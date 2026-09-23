<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyInterviewQualityCollectionEndpointInterface
{
    public function get(string $surveyId): array;

    public function updateQuality(string $surveyId, array $qualityNewStateChangeModel): array;
}
