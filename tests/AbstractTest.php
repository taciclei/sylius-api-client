<?php

declare(strict_types=1);

namespace Tests;

use FAPI\Sylius\SyliusClientAbstract;
use FAPI\Sylius\SyliusClientAdmin;
use FAPI\Sylius\SyliusClientShop;
use PHPUnit\Framework\TestCase;
use VCR\VCR;

abstract class AbstractTest extends TestCase
{
    private $clientShop;

    private $clientAdmin;
    private $clientShopNoConnected;

    public function setUp(): void
    {
        $endpoint = 'https://api-club-local.leoo-factory.io/';
        $this->clientShopNoConnected = SyliusClientShop::create($endpoint);
        $this->clientShop = SyliusClientShop::create($endpoint);
        $this->clientShop->createClientWithCredentials('shop@example.com', 'sylius', SyliusClientAbstract::ACCESS_SHOP);
        $this->clientAdmin = SyliusClientAdmin::create($endpoint);
        //$this->clientAdmin->createClientWithCredentials('shop@example.com', 'sylius', SyliusClientAbstract::ACCESS_ADMIN);
    }

    public static function magnetoSerge()
    {
        VCR::configure()->setMode('new_episodes');
    }

    public function getClientShop(): SyliusClientShop
    {
        return $this->clientShop;
    }

    public function getClientAdmin(): SyliusClientAdmin
    {
        return $this->clientAdmin;
    }

    /**
     * @return SyliusClientShop
     */
    public function getClientShopNoConnected(): SyliusClientShop
    {
        return $this->clientShopNoConnected;
    }


}
