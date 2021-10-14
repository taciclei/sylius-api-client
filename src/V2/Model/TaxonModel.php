<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model;

use FAPI\Sylius\Model\CreatableFromArray;

final class TaxonModel implements CreatableFromArray
{
    private int $id;
    private string $code;

    /** @var string[][] */
    private array $translations;
    private array $children;

    private function __construct(int $id, string $code, array $translations, array $children)
    {
        $this->id = $id;
        $this->code = $code;
        $this->translations = $translations;
        $this->children = $children;
    }


    public static function createFromArray(array $data): self
    {
        $id = -1;
        if (isset($data['id'])) {
            $id = $data['id'];
        }

        $code = '';
        if (isset($data['code'])) {
            $code = $data['code'];
        }

        $translations = [];
        if (isset($data['translations'])) {
            $translations = $data['translations'];
        }

        $children = [];
        if (isset($data['children'])) {
            $children = $data['children'];
        }

        return new self($id, $code, $translations,$children);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return \string[][]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function getBylocal($local): array
    {
        return $this->translations[$local];
    }
}
