<?php

declare(strict_types=1);

namespace Tests;

class TaxonTest extends AbstractTest
{
    public function testGetAll()
    {
        $client = $this->createClientWithCredentials();
        $allTaxons = $client->taxon()->getAll();

        static::assertCount(4, $allTaxons->getItems());
    }
}
