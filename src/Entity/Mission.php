<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MissionRepository")
 */
class Mission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"mission","public"})
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"mission","public"})
     */
    private $journee = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"mission","public"})
     */
    private $demi_journee = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mission"})
     */
    private $client;


    /**
     * @ORM\Column(type="datetime")
     * @Groups({"mission"})
     */
    private $created_at;


    /**
     * @Groups({"mission"})
     */
    private $dateFormat;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="missions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"mission"})
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJournee(): ?array
    {
        return $this->journee;
    }

    public function setJournee(?array $journee): self
    {
        $this->journee = $journee;

        return $this;
    }

    public function getDemiJournee(): ?array
    {
        return $this->demi_journee;
    }

    public function setDemiJournee(?array $demi_journee): self
    {
        $this->demi_journee = $demi_journee;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDateFormat(): ?string
    {
        return $this->created_at->format('Y-m-d');;
    }
}
