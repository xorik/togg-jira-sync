<?php

namespace App\Model;

class JiraWorkLog
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $issueId;

    /** @var string */
    protected $description;

    /** @var \DateTime */
    protected $date;

    /** @var int */
    protected $dur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIssueId(): string
    {
        return $this->issueId;
    }

    public function setIssueId(string $issueId): void
    {
        $this->issueId = $issueId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->date = $date;
    }

    public function getDur(): int
    {
        return $this->dur;
    }

    public function setDur(int $dur): void
    {
        $this->dur = $dur;
    }
}
