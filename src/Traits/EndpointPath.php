<?php

namespace Nikoleesg\NfieldAdmin\Traits;

trait EndpointPath
{
    protected string $basePath;

    /**
     * Return the base path for the endpoint.
     *
     * @example 'v2/surveys'
     * @return string
     */
    protected function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * Return the path for a specific resource.
     *
     * @example 'v2/surveys/{surveyId}'
     * @param string $resourceId
     * @return string
     */
    protected function resourcePath(string $resourceId): string
    {
        return $this->basePath() . '/' . $resourceId;
    }

    /**
     * @param string $action
     * @return string
     */
    protected function actionPath(string $action): string
    {
        return $this->basePath() . '/' . $action;
    }

    /**
     * Return
     * @param string $resourceId
     * @param string $action
     * @return string
     */
    protected function resourceActionPath(string $resourceId, string $action): string
    {
        return $this->resourcePath($resourceId) . '/' . $action;
    }

    protected function subResourcePath(string $resourceId, string $subResource): string
    {
        return $this->resourcePath($resourceId) . '/' . $subResource;
    }

    protected function subResourceActionPath(string $resourceId, string $subResource, string $action): string
    {
        return $this->subResourcePath($resourceId, $subResource) . '/' . $action;
    }

    protected function subResourceItemPath(string $resourceId, string $subResource, mixed $itemId): string
    {
        return $this->subResourcePath($resourceId, $subResource) . '/' . $itemId;
    }
}
