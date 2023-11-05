<?php

namespace Nikoleesg\NfieldAdmin\Endpoints\v1;

use Nikoleesg\NfieldAdmin\HttpClient;

class SignInEndpoint
{
    protected string $resourcePath = 'v1/SignIn';

    protected HttpClient $httpClient;

    public function __invoke()
    {
        $httpClient = new HttpClient();

        $response = $httpClient->post($this->resourcePath, false, $this->getCredentials());

        return $response->header('X-AuthenticationToken');
    }

    /**
     * @return array
     */
    private function getCredentials(): array
    {
        return [
            'Domain' => config('nfield-admin.Domain'),
            'Username' => config('nfield-admin.Username'),
            'Password' => config('nfield-admin.Password')
        ];
    }
}
