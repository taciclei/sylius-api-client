<?php

declare(strict_types=1);

namespace FAPI\Sylius;

use FAPI\Sylius\Http\ClientConfigurator;
use FAPI\Sylius\V2\Api\ProductApi;
use FAPI\Sylius\V2\Api\TaxonApi;

class SyliusClientShop extends SyliusClientAbstract
{
    public const API_ACCESS = SyliusClientAbstract::ACCESS_SHOP;

    public static function create(string $endpoint): self
    {
        $clientConfigurator = new ClientConfigurator();
        $clientConfigurator->setEndpoint($endpoint);

        return new self($clientConfigurator);
    }

    public function customer(): Api\Customer
    {
        return new Api\Customer($this->getHttpClient(), $this->getHydrator(), $this->getRequestBuilder());
    }

    public function product(): ProductApi
    {
        return new ProductApi($this->getHttpClient(), $this->getHydrator(), $this->getRequestBuilder());
    }

    public function taxon(): TaxonApi
    {
        return new TaxonApi($this->getHttpClient(), $this->getHydrator(), $this->getRequestBuilder());
    }
}
