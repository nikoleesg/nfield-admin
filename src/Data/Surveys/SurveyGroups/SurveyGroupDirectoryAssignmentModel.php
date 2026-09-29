<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys\SurveyGroups;

use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\Casts\CarbonCast;
use Nikoleesg\NfieldAdmin\Enums\DirectoryObjectTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

/**
 * A survey group's assignment to a directory (Entra ID) user, service
 * principal or security group.
 *
 * Mirrors Nfield.Manager.Surveys.Interactions.SurveyGroup.Assignments
 * .GetDirectoryAssignments+SurveyGroupDirectoryAssignment.
 */
final class SurveyGroupDirectoryAssignmentModel extends Data
{
    public function __construct(
        public int $surveyGroupId,
        public ?string $tenantId = null,
        public ?string $objectId = null,
        #[WithCast(EnumCast::class, type: DirectoryObjectTypeEnum::class)]
        public ?DirectoryObjectTypeEnum $objectType = null,
        #[WithCast(CarbonCast::class)]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: DATE_ATOM)]
        public ?Carbon $dateAdded = null,
    ) {}
}
