<?php

declare(strict_types=1);

namespace FAPI\Sylius;

use FAPI\Sylius\Http\AuthenticationPlugin;
use FAPI\Sylius\Http\Authenticator;
use FAPI\Sylius\Http\ClientConfigurator;
use FAPI\Sylius\Hydrator\Hydrator;
use FAPI\Sylius\Hydrator\ModelHydrator;
use FAPI\Sylius\V2\Api\ProductApi;
use FAPI\Sylius\V2\Api\TaxonApi;
use Http\Client\HttpClient;

class SyliusClientV2
{
    private HttpClient $httpClient;

    private Hydrator $hydrator;

    private RequestBuilder $requestBuilder;

    private ClientConfigurator $clientConfigurator;

    private Authenticator $authenticator;

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

    public static function create(string $endpoint): self
    {
        $clientConfigurator = new ClientConfigurator();
        $clientConfigurator->setEndpoint($endpoint);

        return new self($clientConfigurator);
    }

    /**
     * Autnenticate a user with the API. This will return an access token.
     * Warning, this will remove the current access token.
     */
    public function createNewAccessToken(string $username, string $password, $access = 'shop'): ?string
    {
        $this->clientConfigurator->removePlugin(AuthenticationPlugin::class);

        return $this->authenticator->createAccessToken($username, $password, $access);
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

    public function custom(Hydrator $hydrator = null): Api\Custom
    {
        return new Api\Custom($this->getHttpClient(), $hydrator ?? $this->hydrator, $this->requestBuilder);
    }

    public function customer(): Api\Customer
    {
        return new Api\Customer($this->getHttpClient(), $this->hydrator, $this->requestBuilder);
    }

    public function cart(): Api\Cart
    {
        return new Api\Cart($this->getHttpClient(), $this->hydrator, $this->requestBuilder);
    }

    public function product(): ProductApi
    {
        return new ProductApi($this->getHttpClient(), $this->hydrator, $this->requestBuilder);
    }

    public function taxon(): TaxonApi
    {
        return new TaxonApi($this->getHttpClient(), $this->hydrator, $this->requestBuilder);
    }

    public function checkout(): Api\Checkout
    {
        return new Api\Checkout($this->getHttpClient(), $this->hydrator, $this->requestBuilder);
    }

    private function getHttpClient(): HttpClient
    {
        return $this->clientConfigurator->createConfiguredClient();
    }
}
