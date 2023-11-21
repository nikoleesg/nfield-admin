<?php

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Data\InterviewerData;
use Nikoleesg\NfieldAdmin\Data\NewCapiInterviewerRequestData;
use Nikoleesg\NfieldAdmin\Endpoints\v1\CapiInterviewersEndpoint;
use Spatie\LaravelData\DataCollection;

class InterviewerService
{
    protected CapiInterviewersEndpoint $capiInterviewerEndpoint;

    public function __construct()
    {
        $this->initEndpoint();
    }

    protected function initEndpoint(): self
    {
        $this->capiInterviewerEndpoint = new CapiInterviewersEndpoint();

        return $this;
    }

    public function getCapiInterviewers(): DataCollection
    {
        return $this->capiInterviewerEndpoint->index();
    }

    public function getCapiInterviewer(string $interviewerId): ?InterviewerData
    {
        return $this->capiInterviewerEndpoint->show($interviewerId);
    }

    public function createCapiInterviewers(NewCapiInterviewerRequestData $data): ?InterviewerData
    {
        return $this->capiInterviewerEndpoint->store($data);
    }

    public function deleteCapiInterviewers(string|InterviewerData $interviewer): bool
    {
        if ($interviewer instanceof InterviewerData) {
            $interviewerId = $interviewer->interviewer_id;
        } else {
            $interviewerId = $interviewer;
        }

        return $this->capiInterviewerEndpoint->destroy($interviewerId);
    }

    public function resetPassword(string|InterviewerData $interviewer, string $password)
    {
        if ($interviewer instanceof InterviewerData) {
            $interviewerId = $interviewer->interviewer_id;
        } else {
            $interviewerId = $interviewer;
        }

        return $this->capiInterviewerEndpoint->update($interviewerId, $password);
    }
}
