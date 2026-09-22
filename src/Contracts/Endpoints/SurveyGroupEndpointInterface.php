<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGroupEndpointInterface
{
    public function list(): array;
}
