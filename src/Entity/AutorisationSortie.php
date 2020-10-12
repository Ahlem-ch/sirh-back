<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AutorisationSortieRepository")
 */
class AutorisationSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"autorisation"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"autorisation"})
     */
    private $motif;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"autorisation"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"autorisation"})
     */
    private $heure;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"autorisation"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="autorisationSorties")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"autorisation"})
     *
     */
    private $user;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"autorisation"})
     */
    private $cause_refus;

    /**
     * @Groups({"autorisation"})
     */
    private $dateFormat;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

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

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
    public function getDateFormat(): ?string
    {
        return $this->date->format('Y-m-d');
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



}
