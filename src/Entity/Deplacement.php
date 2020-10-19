<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeplacementRepository")
 */
class Deplacement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"deplacement","public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"deplacement","public"})
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"deplacement","public"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"deplacement","public"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="deplacements")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"deplacement"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
}
