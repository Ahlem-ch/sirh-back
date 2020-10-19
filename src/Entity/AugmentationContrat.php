<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AugmentationContratRepository")
 */
class AugmentationContrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"augmentation","contrat"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"augmentation","contrat"})
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"augmentation","contrat"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\contrat", inversedBy="augmentationContrats")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"augmentation"})
     */
    private $contrat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

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

    public function getContrat(): ?contrat
    {
        return $this->contrat;
    }

    public function setContrat(?contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }
}
