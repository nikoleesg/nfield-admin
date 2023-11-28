<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\Endpoints\BaseEndpoint;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

}
