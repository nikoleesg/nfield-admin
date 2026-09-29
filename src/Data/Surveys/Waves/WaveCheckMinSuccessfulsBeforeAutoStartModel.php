<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Waves;

use Spatie\LaravelData\Data;

/**
 * Whether a parent survey checks the minimum number of successfuls before a
 * wave auto-starts.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.Waves.WaveCheckMinSuccessfulsBeforeAutoStartModel.
 */
final class WaveCheckMinSuccessfulsBeforeAutoStartModel extends Data
{
    public function __construct(
        public ?bool $checkMinSuccessfulsBeforeAutoStart = null,
    ) {}
}
