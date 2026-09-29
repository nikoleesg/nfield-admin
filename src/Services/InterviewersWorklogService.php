<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Services;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\InterviewersWorklogEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Domain\InterviewersWorklogRequestModel;

/**
 * The interviewers' worklog, reached through `NfieldManager::interviewersWorklog()`.
 *
 * Services mirror the endpoint naming: this pairs with InterviewersWorklogEndpoint.
 */
class InterviewersWorklogService
{
    public function __construct(
        protected InterviewersWorklogEndpointInterface $interviewersWorklogEndpoint,
    ) {}

    /**
     * Request a download of the worklog for a date range.
     *
     * The download is prepared asynchronously; poll the returned activity
     * through `NfieldManager::backgroundActivities()`.
     *
     * @param  array<string, mixed>|InterviewersWorklogRequestModel  $data
     */
    public function download(array|InterviewersWorklogRequestModel $data): BackgroundActivityStatus
    {
        $payload = InterviewersWorklogRequestModel::from($data)->toArray();

        return BackgroundActivityStatus::from($this->interviewersWorklogEndpoint->download($payload));
    }
}
