<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nikoleesg\NfieldAdmin\Data\NewCapiInterviewerRequestData;
use Spatie\LaravelData\DataCollection;
use Nikoleesg\NfieldAdmin\Endpoints\v1;
use Nikoleesg\NfieldAdmin\Data\InterviewerDTO;

class InterviewerService
{
    protected ?string $interviewerId;

    protected v1\CapiInterviewersEndpoint $capiInterviewerEndpoint;

    protected v1\InterviewerAssignmentsEndpoint $interviewerAssignmentsEndpoint;

    public function __construct()
    {
        $this->initEndpoints();
    }

    protected function initEndpoints(): self
    {
        $this->capiInterviewerEndpoint = new v1\CapiInterviewersEndpoint();

        if ($this->isInterviewerConfigured()) {
            $this->interviewerAssignmentsEndpoint = new v1\InterviewerAssignmentsEndpoint($this->interviewerId);
        }

        return $this;
    }

    protected function isInterviewerConfigured(): bool
    {
        return isset($this->interviewerId);
    }

    public function setInterviewer(string $interviewerId): self
    {
        $this->interviewerId = $interviewerId;

        return $this->initEndpoints();
    }

    public function getCapiInterviewers(): DataCollection
    {
        return $this->capiInterviewerEndpoint->index();
    }

    public function getCapiInterviewer(string $interviewerId): ?InterviewerDTO
    {
        return $this->capiInterviewerEndpoint->show($interviewerId);
    }

    public function createCapiInterviewers(NewCapiInterviewerRequestData $data): ?InterviewerDTO
    {
        return $this->capiInterviewerEndpoint->store($data);
    }

    public function deleteCapiInterviewers(string|InterviewerDTO $interviewer): bool
    {
        if ($interviewer instanceof InterviewerDTO) {
            $interviewerId = $interviewer->interviewer_id;
        } else {
            $interviewerId = $interviewer;
        }

        return $this->capiInterviewerEndpoint->destroy($interviewerId);
    }

    public function resetPassword(string|InterviewerDTO $interviewer, string $password)
    {
        if ($interviewer instanceof InterviewerDTO) {
            $interviewerId = $interviewer->interviewer_id;
        } else {
            $interviewerId = $interviewer;
        }

        return $this->capiInterviewerEndpoint->update($interviewerId, $password);
    }

    public function getAssignments(): DataCollection
    {
        return $this->interviewerAssignmentsEndpoint->index();
    }

}
