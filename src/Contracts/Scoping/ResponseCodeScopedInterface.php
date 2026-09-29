<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on one response code.
 *
 * Domain response codes stand alone; survey response codes are also survey
 * scoped, so a survey-level service implements this alongside
 * {@see SurveyScopedInterface}. The code is an int32 on every path.
 */
interface ResponseCodeScopedInterface
{
    public function setResponseCode(int $responseCode): static;

    public function getResponseCode(): int;
}
