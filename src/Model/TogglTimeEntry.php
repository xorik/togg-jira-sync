<?php

namespace App\Model;

class TogglTimeEntry
{
    /** @var int */
    protected $id = 0;

    /** @var string */
    protected $description = '';

    /** @var int */
    protected $dur = 0;

    /** @var \DateTime */
    protected $start;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDur(): int
    {
        return $this->dur;
    }

    public function setDur(int $dur): void
    {
        $this->dur = $dur;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): void
    {
        $start->setTimezone(new \DateTimeZone('UTC'));
        $this->start = $start;
    }
}
