<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model\Product;

use FAPI\Sylius\Model\CreatableFromArray;

/**
 * @author Kasim Taskin <taskinkasim@gmail.com>
 */
final class VariantCollection implements CreatableFromArray
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $pages;

    /**
     * @var int
     */
    private $total;

    /**
     * @var Variant[]
     */
    private $items;

    private function __construct(
        int $page,
        int $limit,
        int $pages,
        int $total,
        array $items
    ) {
        $this->page = $page;
        $this->limit = $limit;
        $this->pages = $pages;
        $this->total = $total;
        if ([] !== $items) {
            $this->items = $items;
        }
    }

    /**
     * @return ProductCollection
     */
    public static function createFromArray(array $data): self
    {
        $page = -1;
        if (isset($data['page'])) {
            $page = $data['page'];
        }

        $limit = -1;
        if (isset($data['limit'])) {
            $limit = $data['limit'];
        }

        $pages = -1;
        if (isset($data['pages'])) {
            $pages = $data['pages'];
        }

        $total = -1;
        if (isset($data['total'])) {
            $total = $data['total'];
        }

        /** @var Variant[] $items */
        $items = [];
        if (isset($data['_embedded'], $data['_embedded']['items'])) {
            foreach ($data['_embedded']['items'] as $item) {
                $items[] = Variant::createFromArray($item);
            }
        }

        return new self($page, $limit, $pages, $total, $items);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return Variant[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
