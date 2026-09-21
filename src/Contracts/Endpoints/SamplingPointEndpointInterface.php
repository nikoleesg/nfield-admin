<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointEndpointInterface
{
    /**
     * Get the details of a specific sampling point.
     */
    public function get(string $surveyId, string $samplingPointId): array;

    /**
     * Delete a specified sampling point.
     */
    public function delete(string $surveyId, string $samplingPointId);

    /**
     * Update a sampling point with the specified fields.
     */
    public function update(string $surveyId, string $samplingPointId, array $samplingPointUpdateRequestModel): array;

    /**
     * Activate a spare sampling point so it can be assigned.
     */
    public function activate(string $surveyId, string $samplingPointId): void;

    /**
     * Replaces an active sampling point with a spare one.
     */
    public function replace(string $surveyId, string $samplingPointId): void;
}
