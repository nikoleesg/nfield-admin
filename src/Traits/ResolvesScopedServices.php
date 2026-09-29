<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\AddressScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\BlueprintScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\CapiInterviewerScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\EventSubscriptionScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\InterviewScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\QuotaVersionScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\RequestConfigurationScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\ResponseCodeScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyGroupScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyVersionScopedInterface;

/**
 * Resolves a scoped service from the container and hands it this resource's scope.
 *
 * #41: the two copies of this method were byte-identical and passed the scope
 * as container arguments keyed by the service's constructor parameter names.
 * The scope now travels through the `*ScopedInterface` setters, so renaming a
 * constructor parameter cannot break it.
 */
trait ResolvesScopedServices
{
    /** @var array<string, object> */
    protected array $resolvedServices = [];

    /**
     * Every `*ScopedInterface` must be handed on here and in
     * {@see scopedServiceKey()}; a scope left out is silently dropped.
     *
     * @template TService of object
     *
     * @param  class-string<TService>  $serviceClass
     * @return TService
     */
    protected function resolveService(string $serviceClass): object
    {
        $key = $this->scopedServiceKey($serviceClass);

        if (isset($this->resolvedServices[$key])) {
            /** @var TService */
            return $this->resolvedServices[$key];
        }

        $service = app($serviceClass);

        if ($service instanceof SurveyScopedInterface && $this instanceof SurveyScopedInterface) {
            $service->setSurveyId($this->getSurveyId());
        }

        if ($service instanceof SamplingPointScopedInterface && $this instanceof SamplingPointScopedInterface) {
            $service->setSamplingPointId($this->getSamplingPointId());
        }

        if ($service instanceof AddressScopedInterface && $this instanceof AddressScopedInterface) {
            $service->setAddressId($this->getAddressId());
        }

        if ($service instanceof InterviewScopedInterface && $this instanceof InterviewScopedInterface) {
            $service->setInterviewId($this->getInterviewId());
        }

        if ($service instanceof QuotaVersionScopedInterface && $this instanceof QuotaVersionScopedInterface) {
            $service->setQuotaVersion($this->getQuotaVersion());
        }

        if ($service instanceof ResponseCodeScopedInterface && $this instanceof ResponseCodeScopedInterface) {
            $service->setResponseCode($this->getResponseCode());
        }

        if ($service instanceof SurveyGroupScopedInterface && $this instanceof SurveyGroupScopedInterface) {
            $service->setSurveyGroupId($this->getSurveyGroupId());
        }

        if ($service instanceof RequestConfigurationScopedInterface && $this instanceof RequestConfigurationScopedInterface) {
            $service->setRequestConfigurationId($this->getRequestConfigurationId());
        }

        if ($service instanceof SurveyVersionScopedInterface && $this instanceof SurveyVersionScopedInterface) {
            $service->setSurveyVersion($this->getSurveyVersion());
        }

        if ($service instanceof BlueprintScopedInterface && $this instanceof BlueprintScopedInterface) {
            $service->setBlueprintId($this->getBlueprintId());
        }

        if ($service instanceof CapiInterviewerScopedInterface && $this instanceof CapiInterviewerScopedInterface) {
            $service->setInterviewerId($this->getInterviewerId());
        }

        if ($service instanceof EventSubscriptionScopedInterface && $this instanceof EventSubscriptionScopedInterface) {
            $service->setSubscriptionName($this->getSubscriptionName());
        }

        $this->resolvedServices[$key] = $service;

        return $service;
    }

    /**
     * The cache is keyed by the scope as well as the class, so a resource whose
     * scope changes hands back a correctly scoped service without the setters
     * having to remember to flush anything.
     */
    protected function scopedServiceKey(string $serviceClass): string
    {
        $key = $serviceClass;

        if ($this instanceof SurveyScopedInterface) {
            $key .= '|'.$this->getSurveyId();
        }

        if ($this instanceof SamplingPointScopedInterface) {
            $key .= '|'.$this->getSamplingPointId();
        }

        if ($this instanceof AddressScopedInterface) {
            $key .= '|'.$this->getAddressId();
        }

        if ($this instanceof InterviewScopedInterface) {
            $key .= '|'.$this->getInterviewId();
        }

        if ($this instanceof QuotaVersionScopedInterface) {
            $key .= '|'.$this->getQuotaVersion();
        }

        if ($this instanceof ResponseCodeScopedInterface) {
            $key .= '|'.$this->getResponseCode();
        }

        if ($this instanceof SurveyGroupScopedInterface) {
            $key .= '|'.$this->getSurveyGroupId();
        }

        if ($this instanceof RequestConfigurationScopedInterface) {
            $key .= '|'.$this->getRequestConfigurationId();
        }

        if ($this instanceof SurveyVersionScopedInterface) {
            $key .= '|'.$this->getSurveyVersion();
        }

        if ($this instanceof BlueprintScopedInterface) {
            $key .= '|'.$this->getBlueprintId();
        }

        if ($this instanceof CapiInterviewerScopedInterface) {
            $key .= '|'.$this->getInterviewerId();
        }

        if ($this instanceof EventSubscriptionScopedInterface) {
            $key .= '|'.$this->getSubscriptionName();
        }

        return $key;
    }
}
