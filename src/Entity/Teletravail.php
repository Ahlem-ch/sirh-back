<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeletravailRepository")
 */
class Teletravail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"teletravail","public"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"teletravail","public"})
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"teletravail","public"})
     */
    private $date_fin;

    /**
     * @ORM\Column(type="float")
     * @Groups({"teletravail","public"})
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"teletravail","public"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="teletravails")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"teletravail"})
     */
    private $collaborateur;

    /**
     * @ORM\Column(type="array")
     * @Groups({"teletravail","public"})
     */
    private $dates = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"teletravail","public"})
     */
    private $cause_refus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDuree(): ?float
    {
        return $this->duree;
    }

    public function setDuree(float $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCollaborateur(): ?user
    {
        return $this->collaborateur;
    }

    public function setCollaborateur(?user $collaborateur): self
    {
        $this->collaborateur = $collaborateur;

        return $this;
    }

    public function getDates(): ?array
    {
        return $this->dates;
    }

    public function setDates(array $dates): self
    {
        $this->dates = $dates;

        return $this;
    }

    public function getCauseRefus(): ?string
    {
        return $this->cause_refus;
    }

    public function setCauseRefus(string $cause_refus): self
    {
        $this->cause_refus = $cause_refus;

        return $this;
    }
}
