<?php

declare(strict_types=1);

namespace Tests;

use FAPI\Sylius\SyliusClientV2;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    private $token;

    private $client;

    public function setUp(): void
    {
        $endpoint = 'https://master.demo.sylius.com/';
        $this->client = SyliusClientV2::create($endpoint);
    }

    protected function createClientWithCredentials($token = null): SyliusClientV2
    {
        $token = $token ?: $this->getToken();
        $this->client->authenticate($token);

        return $this->client;
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($body = []): string
    {
        if ($this->token) {
            return $this->token;
        }

        return $this->client->createNewAccessToken('shop@example.com', 'sylius');
    }
}
