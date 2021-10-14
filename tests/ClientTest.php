<?php

declare(strict_types=1);

namespace Tests;

class ClientTest extends AbstractTest
{
    public function testCreate()
    {
        $client = $this->createClientWithCredentials();
        $allProducts = $client->product()->getAll();

        static::assertCount(21, $allProducts->getItems());
    }
}
