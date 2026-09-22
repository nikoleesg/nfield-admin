<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Data\SurveyQuotaFrame;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SurveyQuotaFrameLevelData extends Data
{
    public function __construct(
        public string $id,
        public string $definitionId,
        public ?int $target,
        public ?int $maxTarget,
        public ?int $maxOvershoot,
        #[DataCollectionOf(SurveyQuotaFrameVariableData::class)]
        public DataCollection $variables,
        public bool $isHidden
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            $data['id'],
            $data['definitionId'],
            $data['target'],
            $data['maxTarget'],
            $data['maxOvershoot'],
            ! empty($data['variables']) ? SurveyQuotaFrameVariableData::collect($data['variables'], DataCollection::class) : SurveyQuotaFrameVariableData::collect([], DataCollection::class),
            $data['isHidden']
        );
    }
}
