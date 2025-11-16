<?php

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Collection;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpoint as SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\BackgroundActivities\BackgroundActivityStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\ClearSurveySampleModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleFilterModel;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SampleUploadStatus;
use Nikoleesg\NfieldAdmin\Data\Surveys\Sample\SurveyCreateSampleColumnModel;

final class SurveySampleCollectionResource
{
    public function __construct(
        protected SurveySampleEndpointInterface $endpoint,
        protected string $surveyId
    ) {}

    /**
     * Retrieves the sample data for the specified survey.
     */
    public function list(): array
    {
        $sampleData = $this->endpoint->all($this->surveyId);

        $encodedSampleData = mb_convert_encoding($sampleData, "UTF-8", "UTF-16LE");

        $removeBOMSampleData = preg_replace('/^\x{FEFF}/u', '', $encodedSampleData);

        $lines = preg_split('/\r\n|\r|\n/', $removeBOMSampleData);

        $header = str_getcsv(array_shift($lines), "\t");

        return collect($lines)
            ->reject(function ($item) {
                return trim($item) === '';
            })
            ->map(function ($item) use ($header) {
                return array_combine($header, str_getcsv($item, "\t"));
            })
            ->toArray();
    }

    /**
     * Uploads the sample data for the specified survey.
     * Pass the sample data as a CSV formatted string in the body of the request.
     */
    public function upload(string $sampleData): SampleUploadStatus
    {
        // TODO: Implement data object
        return new SampleUploadStatus();
    }

    /**
     * Bulk deletes the specified survey's SampleData.
     */
    public function bulkDelete(SampleFilterModel $sampleFilterModel): BackgroundActivityStatus
    {
//        $status = $this->endpoint->destroy($this->surveyId, $sampleFilterModel->toArray());
        // TODO:
        return new BackgroundActivityStatus();
    }

    public function for(int $interviewId): SurveySampleResource
    {
        return new SurveySampleResource($this->endpoint, $this->surveyId, $interviewId);
    }

    public function update(array $surveyUpdateSampleRecordModel)
    {
        return $this->endpoint->update($this->surveyId, $surveyUpdateSampleRecordModel);
    }

    public function block(SampleFilterModel $sampleFilterModel): BackgroundActivityStatus
    {
        return new BackgroundActivityStatus();
    }

    public function create(Collection $surveyCreateSampleColumnModelCollection)
    {
        return $this->endpoint->create($this->surveyId, $surveyCreateSampleColumnModelCollection->toArray());
    }

    public function reset(SampleFilterModel $sampleFilterModel): BackgroundActivityStatus
    {
        return new BackgroundActivityStatus();
    }

    public function clear(ClearSurveySampleModel $clearSurveySampleModel): BackgroundActivityStatus
    {
        return new BackgroundActivityStatus();
    }

    public function download(string $fileName): BackgroundActivityStatus
    {
        return new BackgroundActivityStatus();
    }


}
