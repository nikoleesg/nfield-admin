<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveySampleDataDownloadEndpointInterface
{
    /**
     * Create a request for a sample data download
     *
     * @return array<string, mixed>
     */
    public function requestDownload(string $surveyId, string $fileName): array;
}
