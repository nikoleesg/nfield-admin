<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\SamplingPointData;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\Endpoints\v1\SurveyQuotaFrameEndpoint;

class SurveyQuotaFrameService
{
    protected SurveyQuotaFrameEndpoint $surveyQuotaFrameEndpoint;

    protected ?string $surveyId;

    public function __construct(?string $surveyId = null)
    {
        $this->surveyId = $surveyId;

        if ($this->isSurveyConfigured()) {
            $this->initEndpoint();
        }
    }

    protected function initEndpoint(): self
    {
        $this->surveyQuotaFrameEndpoint = new SurveyQuotaFrameEndpoint($this->surveyId);

        return $this;
    }

    public function isSurveyConfigured(): bool
    {
        return isset($this->surveyId);
    }

    public function setSurvey(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        $this->initEndpoint();

        return $this;
    }

    public function get(?string $samplingPointId)
    {
        if (!is_null($samplingPointId)) {
            return $this->getSamplingPoint($samplingPointId);
        }

        return $this->samplingPointsEndpoint->index();
    }

    public function getSamplingPoint(string $samplingPointId)
    {
        return $this->samplingPointsEndpoint->show($samplingPointId);
    }

    public function create(SamplingPointData $samplingPointData): SamplingPointData
    {
        return $this->samplingPointsEndpoint->store($samplingPointData);
    }

    public function add(string $name, bool $spare = false): SamplingPointData
    {
        $samplingPointData = SamplingPointData::from([
            'name' => $name,
            'kind' => !$spare ? SamplingPointKindEnum::Regular : SamplingPointKindEnum::Spare
        ]);

        return $this->create($samplingPointData);
    }

    public function delete(string|SamplingPointData $samplingPoint): bool
    {
        if ($samplingPoint instanceof SamplingPointData) {
            $samplingPointId = $samplingPoint->id;
        } else {
            $samplingPointId = $samplingPoint;
        }

        return $this->samplingPointsEndpoint->destroy($samplingPointId);
    }

    public function count(): int
    {
        return $this->samplingPointsEndpoint->count();
    }
}
