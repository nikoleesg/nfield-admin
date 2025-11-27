<?php

namespace Nikoleesg\NfieldAdmin\Traits;

trait EndpointPath
{
    /**
     * The base path for API resource.
     *
     * @example '/v2/surveys'
     * @var string
     */
    protected string $basePath;

    /**
     * Get the normalized base path for the API endpoint.
     * This typically represents the root resource collection.
     *
     * @example 'v2/surveys'
     * @return string
     */
    protected function basePath(): string
    {
        return trim($this->basePath, '/');
    }

    /**
     * Helper function
     * @param ...$segments
     * @return string
     */
    private function join(...$segments): string
    {
        return implode('/', array_map(fn($segment) => trim($segment, '/'), $segments));
    }

    /**
     * Build the path for a resource item.
     *
     * @example 'v2/surveys/{surveyId}'
     * @param string $resourceId
     * @return string
     */
    protected function resourcePath(string $resourceId): string
    {
        return $this->join($this->basePath(), $resourceId);
    }

    /**
     * Build a path for an action applied to the root resource.
     *
     * @example 'v2/surveys/search'
     * @param string $action
     * @return string
     */
    protected function actionPath(string $action): string
    {
        return $this->join($this->basePath(), $action);
    }

    /**
     * Build a path for an action applied to a specific resource item.
     *
     * @example 'v2/surveys/{surveyId}/download'
     * @param string $resourceId
     * @param string $action
     * @return string
     */
    protected function resourceActionPath(string $resourceId, string $action): string
    {
        return $this->join($this->basePath(), $resourceId, $action);
    }

    /**
     * Build the path to a sub-resource under a resource item.
     *
     * @example v2/surveys/{surveyId}/fieldwork
     * @param string $resourceId
     * @param string $subResource
     * @return string
     */
    protected function subResourcePath(string $resourceId, string $subResource): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource);
    }

    /**
     * Build a path for an action on a sub-resource collection.
     *
     * @example v2/surveys/{surveyId}/fieldwork/start
     * @param string $resourceId
     * @param string $subResource
     * @param string $action
     * @return string
     */
    protected function subResourceActionPath(string $resourceId, string $subResource, string $action): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $action);
    }

    /**
     * Build the path to a specific item inside a sub-resource.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}
     * @param string $resourceId
     * @param string $subResource
     * @param mixed $itemId
     * @return string
     */
    protected function subResourceItemPath(string $resourceId, string $subResource, mixed $itemId): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId);
    }

    /**
     * Build a path for an action applied to a sub-resource item.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/activate
     * @param string $resourceId
     * @param string $subResource
     * @param mixed $itemId
     * @param string $action
     * @return string
     */
    protected function subResourceItemActionPath(string $resourceId, string $subResource, mixed $itemId, string $action): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId, $action);
    }

    /**
     * Build the path to a nested resource under a sub-resource item.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/Addresses
     * @param string $resourceId
     * @param string $subResource
     * @param mixed $itemId
     * @param string $nestedResource
     * @return string
     */
    protected function nestedResourcePath(string $resourceId, string $subResource, mixed $itemId, string $nestedResource): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId, $nestedResource);
    }

    /**
     * Build the path to a specific item inside a nested resource.
     *
     * @example v2/surveys/{surveyId}/samplingPoints/{samplingPointId}/Addresses/{addressId}
     * @param string $resourceId
     * @param string $subResource
     * @param mixed $itemId
     * @param string $nestedResource
     * @param mixed $nestedItemId
     * @return string
     */
    protected function nestedResourceItemPath(string $resourceId, string $subResource, mixed $itemId, string $nestedResource, mixed $nestedItemId): string
    {
        return $this->join($this->basePath(), $resourceId, $subResource, $itemId, $nestedResource, $nestedItemId);
    }
}
