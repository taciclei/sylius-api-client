<?php

declare(strict_types=1);

namespace FAPI\Sylius\V2\Model;

class View
{
    private string $id;

    private string $type;

    private string $first;

    private string $last;

    private string $next;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFirst(): string
    {
        return $this->first;
    }

    public function setFirst(string $first): self
    {
        $this->first = $first;

        return $this;
    }

    public function getLast(): string
    {
        return $this->last;
    }

    public function setLast(string $last): self
    {
        $this->last = $last;

        return $this;
    }

    public function getNext(): string
    {
        return $this->next;
    }

    public function setNext(string $next): self
    {
        $this->next = $next;

        return $this;
    }
}
