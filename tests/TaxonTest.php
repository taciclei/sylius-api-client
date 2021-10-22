<?php

declare(strict_types=1);

namespace Tests;

class TaxonTest extends AbstractTest
{
    /**
     * @vcr TaxonTest/testGetAll.json
     */
    public function testGetAll()
    {
        $client = $this->getClientShop();
        $allTaxons = $client->taxon()->getAll();

        static::assertCount(4, $allTaxons->getItems());
    }

    /**
     * @vcr TaxonTest/testGetAll.json
     */
    public function testGet()
    {
        //static::magnetoSerge();

        $client = $this->getClientShop();
        $tShirts = $client->taxon()->get('t_shirts');
        static::assertCount(2, $tShirts->getChildren());
        static::assertCount(8, $tShirts->getTranslations());
        $fr = $tShirts->getBylocal('fr_FR');
        static::assertCount(6, $fr);
        static::assertEquals('T-shirts', $fr['name']);
        static::assertEquals('categorie/t-shirts', $fr['slug']);
        static::assertEquals('Eos tempora qui hic eos nostrum. Quam ea ut voluptas quisquam. Aut molestias voluptatem molestias commodi repudiandae et.', $fr['description']);
    }
}
