<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model;

class search
{
    private string $type;

    private string $template;

    private array $mapping = [];

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function setMapping(array $mapping): self
    {
        $this->mapping = $mapping;

        return $this;
    }
}
