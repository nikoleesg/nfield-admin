<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

use Nikoleesg\NfieldAdmin\Contracts\Scoping\SamplingPointScopedInterface;
use Nikoleesg\NfieldAdmin\Contracts\Scoping\SurveyScopedInterface;

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

        return $key;
    }
}
