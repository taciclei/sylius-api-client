<?php

declare(strict_types=1);

namespace FAPI\Sylius;

use FAPI\Sylius\Http\AuthenticationPlugin;
use FAPI\Sylius\Http\Authenticator;
use FAPI\Sylius\Http\ClientConfigurator;
use FAPI\Sylius\Hydrator\Hydrator;
use FAPI\Sylius\Hydrator\ModelHydrator;
use Http\Client\HttpClient;

abstract class SyliusClientAbstract
{
    public const ACCESS_SHOP = 'shop';
    public const ACCESS_ADMIN = 'admin';

    private HttpClient $httpClient;

    private Hydrator $hydrator;

    private RequestBuilder $requestBuilder;

    private ClientConfigurator $clientConfigurator;

    private Authenticator $authenticator;
    private ?string $tokenApi=null;
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
    public function createNewAccessToken(string $username, string $password, $access = self::ACCESS_SHOP): ?string
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

    public function getByIri($iri, string $class)
    {
        return $this->getHttpClient()->sendRequest('GET', $iri);
    }

    /**
     * @return Hydrator|ModelHydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @return RequestBuilder
     */
    public function getRequestBuilder(): RequestBuilder
    {
        return $this->requestBuilder;
    }

    /**
     * @return ClientConfigurator
     */
    public function getClientConfigurator(): ClientConfigurator
    {
        return $this->clientConfigurator;
    }

    /**
     * @return Authenticator
     */
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

    /**
     * @return string|null
     */
    public function getTokenApi(): ?string
    {
        return $this->tokenApi;
    }

    /**
     * @param string|null $tokenApi
     * @return SyliusClientAbstract
     */
    public function setTokenApi(?string $tokenApi): SyliusClientAbstract
    {
        $this->tokenApi = $tokenApi;
        return $this;
    }

}
