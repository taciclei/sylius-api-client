<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model\Product;

use FAPI\Sylius\Model\CreatableFromArray;

/**
 * @author Kasim Taskin <taskinkasim@gmail.com>
 */
final class Variant implements CreatableFromArray
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string[][]
     */
    private $translations;

    /**
     * @var string[][]
     */
    private $channelPricings;

    /**
     * Variant constructor.
     *
     * @param string[][] $translations
     */
    private function __construct(
        int $id,
        string $code,
        array $translations,
        array $channelPricings
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->translations = $translations;
        $this->channelPricings = $channelPricings;
    }

    /**
     * @return Variant
     */
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

        $channelPricings = [];
        if (isset($data['channelPricings'])) {
            $channelPricings = $data['channelPricings'];
        }

        return new self($id, $code, $translations, $channelPricings);
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
     * @return \string[][]
     */
    public function getChannelPricings(): array
    {
        return $this->channelPricings;
    }
}
