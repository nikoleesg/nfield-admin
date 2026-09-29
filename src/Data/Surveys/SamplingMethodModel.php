<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * Supported values: FreeIntercept, JointTargets, IndividualTargets,
 * SamplingPoints, Addresses, AddressesWithQuota.
 */
final class SamplingMethodModel extends Data
{
    public function __construct(
        public string|Optional|null $samplingMethod = new Optional,
    ) {}
}
