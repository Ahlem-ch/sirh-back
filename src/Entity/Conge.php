<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CongeRepository")
 */
class Conge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"conge","public"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"conge","public"})
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"conge","public"})
     */
    private $date_fin;

    /**
     * @ORM\Column(type="float")
     * @Groups({"conge","public"})
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"conge","public"})
     */

    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="conges")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"conge"})
     */
    private $employe;

    /**
     * @ORM\Column(type="array")
     * @Groups({"conge"})
     */
    private $dates = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeConge", inversedBy="conge")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"conge"})
     */
    private $typeConge;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"conge"})
     */
    private $cause_refus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"conge"})
     */
    private $num_mois;



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

    public function getEmploye(): ?user
    {
        return $this->employe;
    }

    public function setEmploye(?user $employe): self
    {
        $this->employe = $employe;

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

    public function getTypeConge(): ?TypeConge
    {
        return $this->typeConge;
    }

    public function setTypeConge(?TypeConge $typeConge): self
    {
        $this->typeConge = $typeConge;

        return $this;
    }

    public function getCauseRefus(): ?string
    {
        return $this->cause_refus;
    }

    public function setCauseRefus(?string $cause_refus): self
    {
        $this->cause_refus = $cause_refus;

        return $this;
    }

    public function getNumMois(): ?string
    {
        return $this->num_mois;
    }

    public function setNumMois(?string $num_mois): self
    {
        $this->num_mois = $num_mois;

        return $this;
    }


}
