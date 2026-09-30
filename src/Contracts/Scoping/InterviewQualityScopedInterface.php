<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Contracts\Scoping;

/**
 * A service that operates on the quality record of one interview.
 *
 * The interview quality paths type the interview id as a string, unlike the
 * int32 every other interview path uses, so this is a scope of its own rather
 * than {@see InterviewScopedInterface}. It extends {@see SurveyScopedInterface}.
 */
interface InterviewQualityScopedInterface extends SurveyScopedInterface
{
    public function setQualityInterviewId(string $qualityInterviewId): static;

    public function getQualityInterviewId(): string;
}
