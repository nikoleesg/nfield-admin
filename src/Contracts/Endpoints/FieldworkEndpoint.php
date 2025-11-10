<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface FieldworkEndpoint
{
    public function start(string $surveyId): void;

    public function status(string $surveyId): int;

    public function counts(string $surveyId): array;

    public function stop(string $surveyId, array $surveysFieldworkStopRequestModel): void;

}
