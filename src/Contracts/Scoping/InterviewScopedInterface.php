<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service or resource that operates on a single interview.
 *
 * Every interview belongs to a survey, so this contract extends
 * {@see SurveyScopedInterface} rather than standing on its own. The API types
 * the interview ID as an int32 on every path that takes one.
 */
interface InterviewScopedInterface extends SurveyScopedInterface
{
    public function setInterviewId(int $interviewId): static;

    public function getInterviewId(): int;
}
