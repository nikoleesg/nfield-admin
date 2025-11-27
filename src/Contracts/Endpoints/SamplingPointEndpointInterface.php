<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface SamplingPointEndpointInterface
{
    /**
     * Get the details of a specific sampling point.
     * @param string $surveyId
     * @param string $samplingPointId
     * @return array
     */
    public function get(string $surveyId, string $samplingPointId): array;

    /**
     * Delete a specified sampling point.
     * @param string $surveyId
     * @param string $samplingPointId
     */
    public function delete(string $surveyId, string $samplingPointId);

    /**
     * Update a sampling point with the specified fields.
     * @param string $surveyId
     * @param string $samplingPointId
     * @param array $samplingPointUpdateRequestModel
     * @return array
     */
    public function update(string $surveyId, string $samplingPointId, array $samplingPointUpdateRequestModel): array;

    /**
     * Activate a spare sampling point so it can be assigned.
     * @param string $surveyId
     * @param string $samplingPointId
     * @return void
     */
    public function activate(string $surveyId, string $samplingPointId): void;

    /**
     * Replaces an active sampling point with a spare one.
     * @param string $surveyId
     * @param string $samplingPointId
     * @return void
     */
    public function replace(string $surveyId, string $samplingPointId): void;
}
