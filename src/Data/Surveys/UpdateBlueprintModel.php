<?php

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Nikoleesg\NfieldAdmin\Enums\BlueprintConfigurationEnum;
use Spatie\LaravelData\Data;

final class UpdateBlueprintModel extends Data
{
    public function __construct(
        public string $surveyId,
        public BlueprintConfigurationEnum $includedConfiguration = BlueprintConfigurationEnum::All,
    ) {}
}
