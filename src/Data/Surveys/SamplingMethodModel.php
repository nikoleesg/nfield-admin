<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

/**
 * Supported values: FreeIntercept, JointTargets, IndividualTargets,
 * SamplingPoints, Addresses, AddressesWithQuota.
 */
final class SamplingMethodModel extends Data
{
    public function __construct(
        public ?string $samplingMethod = null,
    ) {}
}
