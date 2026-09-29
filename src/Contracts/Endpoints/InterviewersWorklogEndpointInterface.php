<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface InterviewersWorklogEndpointInterface
{
    /**
     * Request a download of the interviewers' worklog for a date range.
     *
     * @param  array<string, mixed>  $interviewersWorklogRequestModel
     * @return array<string, mixed>
     */
    public function download(array $interviewersWorklogRequestModel): array;
}
