<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\Waves;

use Spatie\LaravelData\Data;

/**
 * The minimum number of successfuls required before a wave auto-starts.
 *
 * Mirrors NfieldPublicApi.Models.Surveys.Waves.WaveMinSuccessfulsBeforeAutoStartModel.
 */
final class WaveMinSuccessfulsBeforeAutoStartModel extends Data
{
    public function __construct(
        public ?int $minSuccessfulsBeforeAutoStart = null,
    ) {}
}
