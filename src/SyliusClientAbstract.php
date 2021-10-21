<?php

declare(strict_types=1);

namespace FAPI\Sylius;

use FAPI\Sylius\Http\AuthenticationPlugin;
use FAPI\Sylius\Http\Authenticator;
use FAPI\Sylius\Http\ClientConfigurator;
use FAPI\Sylius\Hydrator\Hydrator;
use FAPI\Sylius\Hydrator\ModelHydrator;
use FAPI\Sylius\V2\Api\Customer;
use Http\Client\HttpClient;

abstract class SyliusClientAbstract
{
    public const ACCESS_SHOP = 'shop';

    public const ACCESS_ADMIN = 'admin';

    public const API_ACCESS = self::ACCESS_SHOP;

    private HttpClient $httpClient;

    private Hydrator $hydrator;

    private RequestBuilder $requestBuilder;

    private ClientConfigurator $clientConfigurator;

    private Authenticator $authenticator;

    private ?string $tokenApi = null;

    public ?Customer $customer = null;

    /**
     * The constructor accepts already configured HTTP clients.
     * Use the configure method to pass a configuration to the Client and create an HTTP Client.
     */
    public function __construct(
        ClientConfigurator $clientConfigurator,
        Hydrator $hydrator = null,
        RequestBuilder $requestBuilder = null
    ) {
        $this->clientConfigurator = $clientConfigurator;
        $this->hydrator = $hydrator ?: new ModelHydrator();
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->authenticator = new Authenticator($this->requestBuilder, $this->clientConfigurator->createConfiguredClient());
    }

    /**
     * Autnenticate a user with the API. This will return an access token.
     * Warning, this will remove the current access token.
     */
    public function createNewAccessToken(string $username, string $password): ?string
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);
        $response = $this->authenticator->createAccessToken($username, $password, self::API_ACCESS);
        $customerIri = $this->getAuthenticator()->getCustomer();
        return $response;
    }

    /**
     * Autenticate the client with an access token. This should be the full access token object with
     * refresh token and expirery timestamps.
     *
     * ```php
     *   $accessToken = $client->createNewAccessToken('foo', 'bar');
     *   $client->authenticate($accessToken);
     *```
     */
    public function authenticate(string $accessToken): void
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);
        $this->clientConfigurator->appendPlugin(new AuthenticationPlugin($this->authenticator, $accessToken));
    }

    /**
     * The access token may have been refreshed during the requests. Use this function to
     * get back the (possibly) refreshed access token.
     */
    public function getAccessToken(): ?string
    {
        return $this->authenticator->getAccessToken();
    }

    public function getByIri($iri, string $class)
    {
        $response = $this->getHttpClient()->sendRequest();

        return $this->hydrator->hydrate($response, $class);
    }

    /**
     * @return Hydrator|ModelHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    public function getRequestBuilder(): RequestBuilder
    {
        return $this->requestBuilder;
    }

    public function getClientConfigurator(): ClientConfigurator
    {
        return $this->clientConfigurator;
    }

    public function getAuthenticator(): Authenticator
    {
        return $this->authenticator;
    }

    public function getHttpClient(): HttpClient
    {
        return $this->getClientConfigurator()->createConfiguredClient();
    }

    public function createClientWithCredentials(string $username, string $password, $access = self::ACCESS_SHOP): self
    {
        $token = $this->getTokenApi() ?: $this->getToken($username, $password, $access);
        $this->setTokenApi($token);
        $this->authenticate($token);

        return $this;
    }

    /**
     * Use other credentials if needed.
     */
    private function getToken(string $username, string $password, $access = self::ACCESS_SHOP): string
    {
        if ($this->getTokenApi()) {
            return $this->getTokenApi();
        }

        return $this->createNewAccessToken($username, $password, $access);
    }

    public function getTokenApi(): ?string
    {
        return $this->tokenApi;
    }

    public function setTokenApi(?string $tokenApi): self
    {
        $this->tokenApi = $tokenApi;

        return $this;
    }
}
