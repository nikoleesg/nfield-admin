<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * A survey group's assignment to a local (Nfield-native) identity.
 *
 * Mirrors Nfield.Manager.Surveys.Interactions.SurveyGroup.Assignments
 * .GetNativeAssignments+SurveyGroupNativeAssignment; named "local" after the
 * localAssignments path it comes from.
 */
final class SurveyGroupLocalAssignmentModel extends Data
{
    public function __construct(
        public int $surveyGroupId,
        public ?string $nativeIdentityId = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $dateAdded = null,
    ) {}
}
