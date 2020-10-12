<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RessourceRepository")
 */
class Ressource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $issue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $account_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_end;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_start_project;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_end_project;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status_project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?string
    {
        return $this->project;
    }

    public function setProject(string $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getIssue(): ?string
    {
        return $this->issue;
    }

    public function setIssue(string $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAccountId(): ?string
    {
        return $this->account_id;
    }

    public function setAccountId(?string $account_id): self
    {
        $this->account_id = $account_id;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getDateStartProject(): ?\DateTimeInterface
    {
        return $this->date_start_project;
    }

    public function setDateStartProject(?\DateTimeInterface $date_start_project): self
    {
        $this->date_start_project = $date_start_project;

        return $this;
    }

    public function getDateEndProject(): ?\DateTimeInterface
    {
        return $this->date_end_project;
    }

    public function setDateEndProject(?\DateTimeInterface $date_end_project): self
    {
        $this->date_end_project = $date_end_project;

        return $this;
    }

    public function getStatusProject(): ?string
    {
        return $this->status_project;
    }

    public function setStatusProject(?string $status_project): self
    {
        $this->status_project = $status_project;

        return $this;
    }
}
