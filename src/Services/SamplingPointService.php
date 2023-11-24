<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Data\SamplingPointData;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\Endpoints\v1;
use Nikoleesg\NfieldAdmin\Data;
use Nikoleesg\NfieldAdmin\Enums\SamplingPointKindEnum;

class SamplingPointService
{
    protected v1\SamplingPointsEndpoint $samplingPointsEndpoint;
    protected v1\SamplingPointsQuotaTargetsEndpoint $quotaTargetsEndpoint;
    protected v1\AddressesEndpoint $addressesEndpoint;
    protected v1\SamplingPointsInterviewerAssignmentsEndpoint $interviewerAssignmentsEndpoint;
    protected v1\SamplingPointsAssignmentsEndpoint $assignmentsEndpoint;

    protected ?string $surveyId;

    protected ?string $samplingPointId;

    public function __construct(?string $surveyId = null, ?string $samplingPointId = null)
    {
        $this->surveyId = $surveyId;

        $this->samplingPointId = $samplingPointId;

        $this->initEndpoints();
    }

    protected function initEndpoints(): self
    {
        if ($this->isSurveyConfigured()) {
            $this->samplingPointsEndpoint = new v1\SamplingPointsEndpoint($this->surveyId);
            $this->assignmentsEndpoint = new v1\SamplingPointsAssignmentsEndpoint($this->surveyId);
        }

        if ($this->isSurveyConfigured() && $this->isSamplingPointConfigured()) {
            // initials other endpoints
            $this->quotaTargetsEndpoint = new v1\SamplingPointsQuotaTargetsEndpoint($this->surveyId, $this->samplingPointId);
            $this->addressesEndpoint = new v1\AddressesEndpoint($this->surveyId, $this->samplingPointId);
            $this->interviewerAssignmentsEndpoint = new v1\SamplingPointsInterviewerAssignmentsEndpoint($this->surveyId, $this->samplingPointId);
        }

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

        return $this->initEndpoints();
    }

    public function setSamplingPoint(string $samplingPointId): self
    {
        $this->samplingPointId = $samplingPointId;

        return $this->initEndpoints();
    }

    public function get(?string $samplingPointId = null)
    {
        if (!is_null($samplingPointId)) {
            return $this->getSamplingPoint($samplingPointId);
        }

        return $this->samplingPointsEndpoint->index();
    }

    public function getSamplingPoint(string $samplingPointId)
    {
        return $this->samplingPointsEndpoint->show($samplingPointId);
    }

    public function create(SamplingPointData $samplingPointData): SamplingPointData
    {
        return $this->samplingPointsEndpoint->store($samplingPointData);
    }

    public function add(string $name, bool $spare = false): SamplingPointData
    {
        $samplingPointData = SamplingPointData::from([
            'name' => $name,
            'kind' => !$spare ? SamplingPointKindEnum::Regular : SamplingPointKindEnum::Spare
        ]);

        return $this->create($samplingPointData);
    }

    public function delete(string|SamplingPointData $samplingPoint): bool
    {
        if ($samplingPoint instanceof SamplingPointData) {
            $samplingPointId = $samplingPoint->sampling_point_id;
        } else {
            $samplingPointId = $samplingPoint;
        }

        return $this->samplingPointsEndpoint->destroy($samplingPointId);
    }

    public function count(): int
    {
        return $this->samplingPointsEndpoint->count();
    }


    /**
     * |------------------------------------------------------------------------
     * | Addresses
     * |------------------------------------------------------------------------
     */
    public function getAddresses(): DataCollection
    {
        return $this->addressesEndpoint->index();
    }

    public function getAddress(string $addressId): Data\AddressDTO
    {
        return $this->addressesEndpoint->show($addressId);
    }

    public function createAddress(Data\AddressDTO $addressData): Data\AddressDTO
    {
        return $this->addressesEndpoint->store($addressData);
    }

    public function addAddress(string $details, ?string $addressId = null): Data\AddressDTO
    {
        $addressData = Data\AddressDTO::from([
            'address_id' => $addressId,
            'details' => $details
        ]);

        return $this->createAddress($addressData);
    }

    public function deleteAddress(string|Data\AddressDTO $address): bool
    {
        if ($address instanceof Data\AddressDTO) {
            $addressId = $address->address_id;
        } else {
            $addressId = $address;
        }

        return $this->addressesEndpoint->destroy($addressId);
    }

    public function addressesCount(): int
    {
        return $this->addressesEndpoint->count();
    }

    /**
     * |------------------------------------------------------------------------
     * | Interviewers
     * |------------------------------------------------------------------------
     */
    public function getInterviewerAssignments()
    {
        return $this->interviewerAssignmentsEndpoint->index();
    }

    public function assignInterviewer(string|Data\InterviewerDTO $interviewer): ?Data\SamplingPointInterviewerAssignmentsData
    {
        if ($interviewer instanceof Data\InterviewerDTO) {
            $interviewerId = $interviewer->interviewer_id;
        } else {
            $interviewerId = $interviewer;
        }

        return $this->interviewerAssignmentsEndpoint->store($interviewerId);
    }

    public function unassignInterviewer(string|Data\InterviewerDTO $interviewer): bool
    {
        if ($interviewer instanceof Data\InterviewerDTO) {
            $interviewerId = $interviewer->interviewer_id;
        } else {
            $interviewerId = $interviewer;
        }

        return $this->interviewerAssignmentsEndpoint->destroy($interviewerId);
    }

    public function assign(string|array $samplingPoint, string|array $interviewer)
    {
        if (is_string($samplingPoint)) {
            return $this->assign([$samplingPoint], $interviewer);
        }

        if (is_string($interviewer)) {
            return $this->assign($samplingPoint, [$interviewer]);
        }

        $interviewerAssignmentsData = Data\SamplingPointInterviewerAssignmentsData::from([
            'sampling_point_ids' => $samplingPoint,
            'interviewer_ids' => $interviewer
        ]);

        return $this->assignmentsEndpoint->store($interviewerAssignmentsData);
    }

    public function unassign(string|array $samplingPoint, string|array $interviewer)
    {
        if (is_string($samplingPoint)) {
            return $this->unassign([$samplingPoint], $interviewer);
        }

        if (is_string($interviewer)) {
            return $this->unassign($samplingPoint, [$interviewer]);
        }

        $interviewerAssignmentsData = Data\SamplingPointInterviewerAssignmentsData::from([
            'sampling_point_ids' => $samplingPoint,
            'interviewer_ids' => $interviewer
        ]);

        return $this->assignmentsEndpoint->destroy($interviewerAssignmentsData);
    }

    /**
     * |------------------------------------------------------------------------
     * | Quota Targets
     * |------------------------------------------------------------------------
     *
     */
    public function getQuotaTargets(): DataCollection
    {
        return $this->quotaTargetsEndpoint->index();
    }

    public function getQuotaTarget(string $quotaLevelId): ?Data\SamplingPointQuotaTargetData
    {
        return $this->quotaTargetsEndpoint->show($quotaLevelId);
    }

    public function setQuotaTarget(string $quotaLevelId, int $target): ?Data\SamplingPointQuotaTargetData
    {
        return $this->quotaTargetsEndpoint->update($quotaLevelId, $target);
    }
}
