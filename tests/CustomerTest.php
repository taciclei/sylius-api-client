<?php

declare(strict_types=1);

namespace Tests;

class CustomerTest extends AbstractTest
{
    /**
     * @vcr CustomerTest/testCreate.json
     */
    public function testCreate()
    {
        //static::magnetoSerge();
        $client = $this->getClientShopNoConnected();
        $response = $client->customer()->create(
            'toto204@email.com',
            'tom',
            'dupond',
            'M',
            'testPassword'
        );

        static::assertEquals(204, $response->getStatusCode());
    }
}
