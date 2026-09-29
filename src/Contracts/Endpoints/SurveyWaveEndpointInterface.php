<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SurveyWaveEndpointInterface
{
    /**
     * A wave's minimum successfuls before auto-start.
     *
     * @return array<string, mixed>
     */
    public function getMinSuccessfulsBeforeAutoStart(string $waveId): array;

    /**
     * Set a wave's minimum successfuls before auto-start.
     *
     * @param  array<string, mixed>  $waveMinSuccessfulsBeforeAutoStartModel
     */
    public function updateMinSuccessfulsBeforeAutoStart(string $waveId, array $waveMinSuccessfulsBeforeAutoStartModel): void;

    /**
     * Remove a wave's minimum successfuls before auto-start.
     */
    public function deleteMinSuccessfulsBeforeAutoStart(string $waveId): void;

    /**
     * A wave's start date.
     *
     * @return array<string, mixed>
     */
    public function getStartDate(string $waveId): array;

    /**
     * Set a wave's start date.
     *
     * @param  array<string, mixed>  $waveStartDateModel
     */
    public function updateStartDate(string $waveId, array $waveStartDateModel): void;

    /**
     * A wave's stop date.
     *
     * @return array<string, mixed>
     */
    public function getStopDate(string $waveId): array;

    /**
     * Set a wave's stop date.
     *
     * @param  array<string, mixed>  $waveStopDateModel
     */
    public function updateStopDate(string $waveId, array $waveStopDateModel): void;
}
