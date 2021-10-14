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

    public function setUp(): void
    {
        $endpoint = 'https://master.demo.sylius.com/';
        $this->clientShop = SyliusClientShop::create($endpoint);
        $this->clientShop->createClientWithCredentials('shop@example.com', 'sylius', SyliusClientAbstract::ACCESS_SHOP);
        $this->clientAdmin = SyliusClientAdmin::create($endpoint);
        //$this->clientAdmin->createClientWithCredentials('shop@example.com', 'sylius', SyliusClientAbstract::ACCESS_ADMIN);
    }

    public static function magnetoSerge() {
        VCR::configure()->setMode('new_episodes');
    }

    /**
     * @return SyliusClientShop
     */
    public function getClientShop(): SyliusClientShop
    {
        return $this->clientShop;
    }

    /**
     * @return SyliusClientAdmin
     */
    public function getClientAdmin(): SyliusClientAdmin
    {
        return $this->clientAdmin;
    }
}
