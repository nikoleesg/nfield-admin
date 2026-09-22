<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Resources;

use Illuminate\Support\Facades\Log;
use Nikoleesg\NfieldAdmin\Contracts\Endpoints\SurveySampleEndpointInterface;
use RuntimeException;

final class SurveySampleResource
{
    protected ?string $surveyId = null;

    protected ?int $interviewId = null;

    public function __construct(
        protected SurveySampleEndpointInterface $surveySampleEndpoint,
    ) {}

    public function setSurveyId(string $surveyId): self
    {
        $this->surveyId = $surveyId;

        return $this;
    }

    public function setInterviewId(int $interviewId): self
    {
        $this->interviewId = $interviewId;

        return $this;
    }

    public function getSampleRecord(): array
    {
        // Get raw CSV data from endpoint
        $rawCsvData = $this->surveySampleEndpoint->get($this->surveyId, $this->interviewId);

        // Parse CSV into array
        return $this->parseCsvData($rawCsvData)[0];
    }

    public function deleteSampleData(array $sampleFilterModel): array
    {
        return $this->surveySampleEndpoint->destroy($this->surveyId, $sampleFilterModel);
    }

    public function updateSampleRecord(array $surveyUpdateSampleRecordModel): array
    {
        return $this->surveySampleEndpoint->update($this->surveyId, $surveyUpdateSampleRecordModel);
    }

    /**
     * Parse CSV string data into an associative array
     *
     * @param  string  $csvData  Raw CSV data (potentially UTF-16LE with BOM)
     * @return array Parsed data with headers as keys
     *
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

        if (empty($header[0])) {
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
        return array_values(array_filter($lines, fn ($line) => trim($line) !== ''));
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
                    'line' => $line,
                ]);

                continue; // Skip malformed rows
            }

            // Combine header with row data
            $results[] = array_combine($header, $row);
        }

        return $results;
    }
}
