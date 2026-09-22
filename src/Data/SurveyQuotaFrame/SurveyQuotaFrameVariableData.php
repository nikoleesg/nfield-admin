<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SurveyQuotaFrameVariableData extends Data
{
    public function __construct(
        public string $id,
        public string $definitionId,
        #[DataCollectionOf(SurveyQuotaFrameLevelData::class)]
        public DataCollection $levels,
        public bool $isHidden
    ) {}
}
