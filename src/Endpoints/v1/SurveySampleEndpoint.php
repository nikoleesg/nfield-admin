<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Nikoleesg\NfieldAdmin\Data\SurveyUpdateSampleRecordDTO;

class SurveySampleEndpoint extends BaseEndpoint
{
    protected string $resourcePath = 'v1/Surveys/{surveyId}/Sample';

    protected ?string $surveyId;

    public function __construct(?string $surveyId = null)
    {
        $this->resourcePath = str_replace(['{surveyId}'], [$surveyId], $this->resourcePath);

        $this->surveyId = $surveyId;

        parent::__construct();
    }

    /**
     * Retrieves the sample data for the specified survey.
     * https://apiap.nfieldmr.com/help/api/get-v1-surveys-surveyid-sample
     *
     * @return false|string|string[]
     */
    public function index()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        if ($response->ok()) {
            // save survey sample to local storage
            $filePath = config('nfield-admin.sample_files_store_path');

            $fileName = sprintf('PA_SurveySample_%s.txt', Carbon::now('Asia/Singapore')->format('Ymd_His'));

            $fileFullPath = str_replace("{surveyId}", $this->surveyId,"$filePath/$fileName");

            $file = Storage::put($fileFullPath, $response->body());

            if ($file) {
                return $fileFullPath;
            }
        }

        return false;
    }

    /**
     * Retrieves a single sample record for the specified survey.
     * https://apiap.nfieldmr.com/help/api/get-v1-surveys-surveyid-sample-interviewid
     *
     * @param int $interviewId
     * @return false|string|string[]
     */
    public function show(int $interviewId)
    {
        $resourcePath = $this->resourcePath . "/$interviewId";

        $response = $this->httpClient->get($resourcePath);

        if ($response->ok()) {
            // save survey sample to local storage
            $filePath = config('nfield-admin.sample_files_store_path');

            $fileName = sprintf('PA_SurveySample_%s.txt', Carbon::now('Asia/Singapore')->format('Ymd_His'));

            $fileFullPath = str_replace("{surveyId}", $this->surveyId,"$filePath/$fileName");

            $file = Storage::put($fileFullPath, $response->body());

            if ($file) {
                return $fileFullPath;
            }
        }

        return false;
    }

    /**
     * Updates Sample Record
     * https://apiap.nfieldmr.com/help/api/put-v1-surveys-surveyid-sample-update
     *
     * @param SurveyUpdateSampleRecordDTO $sampleRecordData
     * @return false|mixed
     */
    public function update(SurveyUpdateSampleRecordDTO $sampleRecordData)
    {
        $resourcePath = $this->resourcePath . '/Update';

        $columnUpdates = [];

        foreach ($sampleRecordData->column_updates as $column_update) {
            $columnUpdates[] = array_change_key_casing($column_update->toArray(), CASE_STUDLY);
        }

        // Transform to SurveyUpdateSampleRecordModel
        $updateSampleRecordModel = [
            'SampleRecordId' => $sampleRecordData->sample_record_id,
            'ColumnUpdates' => $columnUpdates
        ];

        $response = $this->httpClient->put($resourcePath, true, $updateSampleRecordModel);

        if ($response->ok()) {
            return json_decode($response->body(), true)['ResultStatus'];
        }

        return false;
    }

    /**
     * Retrieves the sample data for the specified survey.
     * https://apiap.nfieldmr.com/help/api/get-v1-surveys-surveyid-sample
     *
     * @return false|string|string[]
     */
    public function get()
    {
        $resourcePath = $this->resourcePath;

        $response = $this->httpClient->get($resourcePath);

        if ($response->ok()) {
            $utf8Content = mb_convert_encoding($response->body(), "UTF-8", "UTF-16LE");

            $lines = preg_split('/\r\n|\r|\n/', $utf8Content);

            $headerLine = array_shift($lines);
            // Remove UTF-16 BOM
            $headerLineRemoveBOM = preg_replace('/^\x{FEFF}/u', '', $headerLine);

            $header = str_getcsv($headerLineRemoveBOM, "\t");

            $rows = $lines;

            return collect($rows)
                ->reject(function ($item) {
                    return trim($item) === '';
                })
                ->map(function ($item) use ($header) {
                    return array_combine($header, str_getcsv($item, "\t"));
                });
        }

        return false;
    }

}
