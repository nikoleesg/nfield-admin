<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleCollectionEndpoint
{
    public function all(string $surveyId): string;

    public function upload(string $surveyId, string $file): array;

    public function destroy(string $surveyId, array $sampleFilterModel): array;

    public function update(string $surveyId, array $surveyUpdateSampleRecordModel): array;

    public function block(string $surveyId, array $sampleFilterModel): array;

    public function create(string $surveyId, array $surveyCreateSampleColumnModel): array;

    public function reset(string $surveyId, array $sampleFilterModel): array;

    public function clear(string $surveyId, array $clearSurveySampleModel): array;

    public function download(string $surveyId, string $fileName): array;
}
