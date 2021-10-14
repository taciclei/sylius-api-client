<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model;

use FAPI\Sylius\Model\CreatableFromArray;

final class TaxonModel implements CreatableFromArray
{
    /** @var int */
    private $id;

    /** @var string */
    private $code;

    /** @var string[][] */
    private $translations;

    private function __construct(
        int $id,
        string $code,
        array $translations
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->translations = $translations;
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

        return new self($id, $code, $translations);
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
}
