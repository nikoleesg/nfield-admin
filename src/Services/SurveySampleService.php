<?php

namespace Nikoleesg\NfieldAdmin\Services;


use Illuminate\Support\Collection;
use Log;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use Nikoleesg\NfieldAdmin\Resources\SurveySampleResource;
use RuntimeException;

class SurveySampleService
{
    protected ?string $surveyId = null;

    public function __construct(
        protected SurveySampleCollectionEndpointInterface $surveySampleCollectionEndpoint,
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;
        return $this;
    }

    /**
     * Download and parse sample data from the survey
     *
     * @return array Array of sample records with headers as keys
     * @throws RuntimeException If CSV parsing fails
     */
    public function downloadSampleData(): array
    {
        // Get raw CSV data from endpoint
        $rawCsvData = $this->surveySampleCollectionEndpoint->download($this->surveyId);

        // Parse CSV into array
        return $this->parseCsvData($rawCsvData);
    }

    // TODO
    public function uploadSampleData() {}
    public function blockSampleData() {}

    /**
     * Create a survey sample (Online)
     * @param Collection $surveyCreateSampleColumnModelCollection
     * @return Collection
     */
    public function createSampleData(Collection $surveyCreateSampleColumnModelCollection): Collection
    {
        $response = $this->surveySampleCollectionEndpoint->create($this->surveyId, $surveyCreateSampleColumnModelCollection->toArray());

        return collect($response);
    }

    // TODO:
    public function resetSampleData() {}
    public function clearSampleDataColumns() {}

    /**
     * @param string|null $fileName
     * @return array
     */
    public function requestSampleDownload(?string $fileName = null): array
    {
        $fileName = $fileName ?? $this->generateSampleFileName();

        return $this->surveySampleCollectionEndpoint->requestDownload($this->surveyId, $fileName);
    }

    /**
     * Return SurveySampleResource for the specified survey
     * @param int $interviewId
     * @return SurveySampleResource
     */
    public function for(int $interviewId): SurveySampleResource
    {
        $surveySampleResource = new SurveySampleResource($this->surveySampleEndpoint);

        if ($this->surveyId !== null) {
            $surveySampleResource->setSurveyId($this->surveyId);
        }

        $surveySampleResource->setInterviewId($interviewId);

        return $surveySampleResource;
    }


    /**
     * Parse CSV string data into an associative array
     *
     * @param string $csvData Raw CSV data (potentially UTF-16LE with BOM)
     * @return array Parsed data with headers as keys
     * @throws RuntimeException If CSV structure is invalid
     */
    protected function parseCsvData(string $csvData): array
    {
        // Handle encoding conversion (UTF-16LE to UTF-8)
        $csvData = $this->normalizeEncoding($csvData);

        // Split into lines
        $lines = $this->splitIntoLines($csvData);

        if (empty($lines)) {
            return [];
        }

        // Extract and validate header
        $header = str_getcsv(array_shift($lines), "\t");

        if (empty($header)) {
            throw new RuntimeException('CSV file has no header row');
        }

        // Parse data rows
        return $this->parseDataRows($lines, $header);
    }

    /**
     * Normalize CSV encoding to UTF-8 and remove BOM
     */
    protected function normalizeEncoding(string $data): string
    {
        // Convert from UTF-16LE to UTF-8
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-16LE');

        // Remove Unicode BOM character
        return preg_replace('/^\x{FEFF}/u', '', $data);
    }

    /**
     * Split CSV data into lines, handling different line endings
     */
    protected function splitIntoLines(string $data): array
    {
        // Split by any combination of line endings (Windows, Unix, Mac)
        $lines = preg_split('/\r\n|\n|\r/', $data);

        // Remove empty lines
        return array_values(array_filter($lines, fn($line) => trim($line) !== ''));
    }

    /**
     * Parse data rows into associative arrays using headers as keys
     */
    protected function parseDataRows(array $lines, array $header): array
    {
        $headerCount = count($header);
        $results = [];

        foreach ($lines as $lineNumber => $line) {
            // Skip empty lines
            if (trim($line) === '') {
                continue;
            }

            // Parse the line
            $row = str_getcsv($line, "\t");

            // Validate column count
            if (count($row) !== $headerCount) {
                // Log warning or handle mismatch
                Log::warning("CSV row {$lineNumber} has mismatched columns", [
                    'expected' => $headerCount,
                    'actual' => count($row),
                    'line' => $line
                ]);
                continue; // Skip malformed rows
            }

            // Combine header with row data
            $results[] = array_combine($header, $row);
        }

        return $results;
    }

    /**
     * Generate a default filename for sample download
     *
     * @return string Generated filename with survey ID and timestamp
     */
    protected function generateSampleFileName(): string
    {
        return sprintf(
            'Survey_%s_Samples_%s',
            $this->surveyId,
            now('Asia/Singapore')->format('Ymd_His')
        );
    }
}
