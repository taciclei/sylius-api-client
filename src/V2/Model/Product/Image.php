<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model\Product;

use FAPI\Sylius\Model\CreatableFromArray;

/**
 * @author Kasim Taskin <taskinkasim@gmail.com>
 */
final class Image implements CreatableFromArray
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $path;

    private function __construct(
        int $id,
        string $type,
        string $path
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->path = $path;
    }

    /**
     * @return Product
     */
    public static function createFromArray(array $data): self
    {
        $id = -1;
        if (isset($data['id'])) {
            $id = $data['id'];
        }

        $type = '';
        if (isset($data['type'])) {
            $type = $data['type'];
        }

        $path = '';
        if (isset($data['path'])) {
            $path = $data['path'];
        }

        return new self($id, $type, $path);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
