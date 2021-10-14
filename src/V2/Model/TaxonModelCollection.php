<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model;

use FAPI\Sylius\Model\CreatableFromArray;

final class TaxonModelCollection implements CreatableFromArray
{
    /** @var TaxonModel[] */
    private array $items;

    private ?string $context;

    private ?string $id;

    private ?string $type;

    private ?View $view;

    private ?Search $search;

    private ?int $totalItems;

    public function __construct(array $items, ?string $context, ?string $id, ?string $type, ?View $view, ?Search $search, ?int $totalItems)
    {
        $this->items = $items;
        $this->context = $context;
        $this->id = $id;
        $this->type = $type;
        $this->view = $view;
        $this->search = $search;
        $this->totalItems = $totalItems;
    }

    /**
     * @return static
     */
    public static function createFromArray(array $data): self
    {
        $arrayValues = [];
        foreach ($data as $key => $item) {
            $key = str_replace(['@', 'hydra:'], '', $key);
            $arrayValues[$key] = $item;
        }

        $totalItems = -1;
        if (isset($arrayValues['totalItems'])) {
            $totalItems = $arrayValues['totalItems'];
        }

        $context = -1;
        if (isset($arrayValues['context'])) {
            $context = $arrayValues['context'];
        }

        $id = -1;
        if (isset($arrayValues['id'])) {
            $id = $arrayValues['id'];
        }
        $type = -1;
        if (isset($arrayValues['type'])) {
            $type = $arrayValues['type'];
        }
        $search = null;
        if (isset($arrayValues['search'])) {
            $search = new Search($arrayValues['search']);
        }
        $view = null;
        if (isset($arrayValues['view'])) {
            $view = new View($arrayValues['view']);
        }

        /** @var TaxonModel[] $items */
        $items = [];
        if (isset($arrayValues['member'])) {
            foreach ($arrayValues['member'] as $item) {
                $items[] = TaxonModel::createFromArray($item);
            }
        }

        return new self($items, $context, $id, $type, $view, $search, $totalItems);
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
     * @return TaxonModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
