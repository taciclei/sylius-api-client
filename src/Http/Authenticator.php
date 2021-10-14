<?php

declare(strict_types=1);

namespace FAPI\Sylius\Http;

use FAPI\Sylius\RequestBuilder;
use Http\Client\HttpClient;

/**
 * Class that gets access tokens.
 *
 * @internal
 */
final class Authenticator
{
    private RequestBuilder $requestBuilder;

    private HttpClient $httpClient;

    private ?string $accessToken;

    private string $accessMode = 'shop';

    public function __construct(RequestBuilder $requestBuilder, HttpClient $httpClient, string $clientId = null, string $clientSecret = null)
    {
        $this->requestBuilder = $requestBuilder;
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function createAccessToken(string $username, string $password, $access = 'shop'): ?string
    {
        $this->accessMode = $access;
        $body = ['email' => $username, 'password' => $password];
        $path = sprintf('/api/v2/%s/authentication-token', $access);
        $request = $this->requestBuilder->create('POST', $path, [
            'accept' => 'application/json',
            'Content-type' => 'application/json',
        ], json_encode($body));

        $response = $this->httpClient->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return null;
        }

        $this->accessToken = json_decode($response->getBody()->__toString(), true)['token'];

        return $this->accessToken;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getAccessMode(): ?string
    {
        return $this->accessMode;
    }
}
