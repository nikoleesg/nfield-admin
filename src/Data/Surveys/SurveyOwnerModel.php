<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\Surveys;

use Spatie\LaravelData\Data;

final class SurveyOwnerModel extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $userName,
    ) {}
}
