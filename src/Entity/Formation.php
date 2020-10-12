<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 */
class Formation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("Formation")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("Formation")
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("Formation")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("Formation")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("Formation")
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("Formation")
     */
    private $formateur;

    /**
     * @Groups("Formation")
     */
    private $dateFormat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getFormateur(): ?string
    {
        return $this->formateur;
    }

    public function setFormateur(?string $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getDateFormat(): ?string
    {
        return $this->date->format('Y-m-d');
    }
}
