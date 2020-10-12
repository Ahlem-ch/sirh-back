<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointageRepository")
 */
class Pointage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"pointage","public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pointage","public"})
     */
    private $cardRef;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pointage","public"})
     */
    private $machine;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"pointage","public"})
     */
    private $date;

    /**
     * @Groups({"pointage","public"})
     */
    private $dateFormat;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pointage","public"})
     */
    private $time;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pointage","public"})
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="pointages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"pointage"})
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departement", inversedBy="pointages")
     * @Groups({"pointage"})
     */
    private $dpartement;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardRef(): ?string
    {
        return $this->cardRef;
    }

    public function setCardRef(string $cardRef): self
    {
        $this->cardRef = $cardRef;

        return $this;
    }

    public function getMachine(): ?string
    {
        return $this->machine;
    }

    public function setMachine(string $machine): self
    {
        $this->machine = $machine;

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


    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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

    public function getDpartement(): ?Departement
    {
        return $this->dpartement;
    }

    public function setDpartement(?Departement $dpartement): self
    {
        $this->dpartement = $dpartement;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDateFormat(): ?string
    {
        return $this->date->format('Y-m-d');
    }


}
