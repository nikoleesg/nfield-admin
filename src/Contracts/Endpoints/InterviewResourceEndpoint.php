<?php

namespace Nikoleesg\NfieldAdmin\Contracts\Endpoints;

interface InterviewResourceEndpoint
{
    public function download(int $interviewId): array;

    public function delete(int $interviewId): array;



}
