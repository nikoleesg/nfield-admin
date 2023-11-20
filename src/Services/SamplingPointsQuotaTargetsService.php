<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\AddressData;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\Endpoints\v1\SamplingPointsQuotaTargetsEndpoint as QuotaTargetsEndpoint;

class SamplingPointsQuotaTargetsService
{
    protected QuotaTargetsEndpoint $quotaTargetsEndpoint;

    protected ?string $surveyId;

    protected ?string $samplingPointId;

    public function __construct(?string $surveyId = null, ?string $samplingPointId = null)
    {
        $this->surveyId = $surveyId;

        $this->samplingPointId = $samplingPointId;

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            $this->initEndpoint();
        }
    }

    protected function initEndpoint(): self
    {
        $this->quotaTargetsEndpoint = new QuotaTargetsEndpoint($this->surveyId, $this->samplingPointId);

        return $this;
    }

    public function isSurveyConfigured(): bool
    {
        return isset($this->surveyId);
    }

    public function isSamplingPointConfigured(): bool
    {
        return isset($this->samplingPointId);
    }

    public function setSurvey(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            $this->initEndpoint();
        }

        return $this;
    }

    public function setSamplingPoint(string $samplingPointId): self
    {
        $this->samplingPointId = $samplingPointId;

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            $this->initEndpoint();
        }

        return $this;
    }

    public function get(?string $quotaLevelId = null)
    {
        if (!is_null($quotaLevelId)) {
            return $this->getQuotaTarget($quotaLevelId);
        }

        return $this->quotaTargetsEndpoint->index();
    }

    public function getQuotaTarget(string $quotaLevelId)
    {
        return $this->quotaTargetsEndpoint->show($quotaLevelId);
    }

    public function setQuotaTarget(string $quotaLevelId, int $target)
    {
        return $this->quotaTargetsEndpoint->update($quotaLevelId, $target);
    }
}
