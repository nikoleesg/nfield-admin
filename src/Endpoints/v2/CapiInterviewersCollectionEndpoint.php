<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v2;

use Nikoleesg\NfieldAdmin\Contracts\Endpoints\CapiInterviewersCollectionEndpointInterface;
use Nikoleesg\NfieldAdmin\Data\CapiInterviewers\NewCapiInterviewerRequestData;

final class CapiInterviewersCollectionEndpoint extends BaseEndpoint implements CapiInterviewersCollectionEndpointInterface
{
    protected string $version = 'v2';

    protected function buildPath(): string
    {
        return "/{$this->version}/capiInterviewers";
    }

    /**
     * Get all CAPI interviewers
     */
    public function list(): array
    {
        $uri = $this->basePath();

        return $this->normalizeListResponse($this->httpClient->get($uri)->json());
    }

    /**
     * Find CAPI interviewers with filters
     */
    public function find(array $data = []): array
    {
        $uri = $this->basePath();

        return $this->normalizeListResponse($this->httpClient->get($uri, $data)->json());
    }

    /**
     * Create a new CAPI interviewer
     */
    public function create(NewCapiInterviewerRequestData $data): array
    {
        $uri = $this->basePath();

        return $this->normalizeItemResponse(
            $this->httpClient->post($uri, $data->toArray())->json()
        );
    }

    /**
     * Get CAPI interviewer by client interviewer ID
     */
    public function getByClientId(string $clientInterviewerId): array
    {
        $uri = $this->actionPath("getByClientId/{$clientInterviewerId}");

        return $this->normalizeItemResponse($this->httpClient->get($uri)->json());
    }

    private function normalizeListResponse(mixed $json): array
    {
        if (! is_array($json)) {
            return [];
        }

        $items = $json;

        if (! array_is_list($items) && isset($items['value']) && is_array($items['value'])) {
            $items = $items['value'];
        }

        if (! is_array($items) || ! array_is_list($items)) {
            return [];
        }

        return array_map([$this, 'normalizeItemResponse'], $items);
    }

    private function normalizeItemResponse(mixed $json): array
    {
        if (! is_array($json)) {
            return [];
        }

        $normalized = [];

        foreach ($json as $key => $value) {
            $normalized[is_string($key) ? lcfirst($key) : $key] = $value;
        }

        return $normalized;
    }
}
