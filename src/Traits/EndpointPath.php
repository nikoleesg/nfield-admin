<?php

declare(strict_types=1);

namespace Nikoleesg\NfieldAdmin\Traits;

trait EndpointPath
{
    /**
     * The base path for API resource.
     *
     * @example '/v2/surveys'
     */
    protected string $basePath;

    /**
     * Get the normalized base path for the API endpoint.
     * This typically represents the root resource collection.
     *
     * @example 'v2/surveys'
     */
    protected function basePath(): string
    {
        return trim($this->basePath, '/');
    }

    /**
     * Join path segments with a single slash between them.
     *
     * Ids are not always strings — an interview id and a quota `eTag` are
     * integers — and `declare(strict_types=1)` means a `string` parameter would
     * throw a TypeError on the first such call rather than coerce.
     */
    private function join(string|int ...$segments): string
    {
        return implode('/', array_map(fn (string|int $segment) => trim((string) $segment, '/'), $segments));
    }

    /**
     * Build the path for a resource item.
     *
     * @example 'v2/surveys/{surveyId}'
     */
    protected function resourcePath(string $resourceId): string
    {
        return $this->join($this->basePath(), $resourceId);
    }

    /**
     * Build a path for an action applied to the root resource.
     *
     * @example 'v2/surveys/search'
     */
    protected function actionPath(string $action): string
    {
        return $this->join($this->basePath(), $action);
    }

    /**
     * Build a path for an action applied to a specific resource item.
     *
     * @example 'v2/surveys/{surveyId}/download'
     */
    protected function resourceActionPath(string $resourceId, string $action): string
    {
        return $this->join($this->basePath(), $resourceId, $action);
    }

    /**
     * Build the path to a sub-resource under a resource item.
     *
     * @example v2/surveys/{surveyId}/fieldwork
     */
    protected function subResourcePath(string $resourceId, string $subResource): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource);
    }

    /**
     * Build a path for an action on a sub-resource collection.
     *
     * @example v2/surveys/{surveyId}/fieldwork/start
     */
    protected function subResourceActionPath(string $resourceId, string $subResource, string $action): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $action);
    }

    /**
     * Build the path to a specific item inside a sub-resource.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}
     */
    protected function subResourceItemPath(string $resourceId, string $subResource, string|int $itemId): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId);
    }

    /**
     * Build a path for an action applied to a sub-resource item.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/activate
     */
    protected function subResourceItemActionPath(string $resourceId, string $subResource, string|int $itemId, string $action): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId, $action);
    }

    /**
     * Build the path to a nested resource under a sub-resource item.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/Addresses
     */
    protected function nestedResourcePath(string $resourceId, string $subResource, string|int $itemId, string $nestedResource): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId, $nestedResource);
    }

    /**
     * Build the path to a specific item inside a nested resource.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/Addresses/{addressId}
     */
    protected function nestedResourceItemPath(string $resourceId, string $subResource, string|int $itemId, string $nestedResource, string|int $nestedItemId): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId, $nestedResource, $nestedItemId);
    }
}
