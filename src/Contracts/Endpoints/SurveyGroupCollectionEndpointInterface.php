<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyGroupCollectionEndpointInterface
{
    /**
     * All survey groups in the domain.
     *
     * @return list<array<string, mixed>>
     */
    public function list(): array;
}
