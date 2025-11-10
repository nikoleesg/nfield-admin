<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpoint as SurveySampleEndpointInterface;

final class SurveySampleResource
{
    public function __construct(
        protected SurveySampleEndpointInterface $endpoint,
        protected string $surveyId,
        protected string $interviewId
    ) {}

    public function get(): array
    {
        $sampleData = $this->endpoint->get($this->surveyId, $this->interviewId);

        $encodedSampleData = mb_convert_encoding($sampleData, "UTF-8", "UTF-16LE");

        $removeBOMSampleData = preg_replace('/^\x{FEFF}/u', '', $encodedSampleData);

        $lines = preg_split('/\r\n|\r|\n/', $removeBOMSampleData);

        $header = str_getcsv(array_shift($lines), "\t");

        return collect($lines)
            ->reject(function ($item) {
                return trim($item) === '';
            })
            ->map(function ($item) use ($header) {
                return array_combine($header, str_getcsv($item, "\t"));
            })
            ->first();
    }

    public function delete()
    {

    }

    public function update()
    {

    }

}
