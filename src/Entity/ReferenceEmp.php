<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReferenceEmpRepository")
 */
class ReferenceEmp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("reference")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("reference")
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     * @Groups("reference")
     */
    private $value_min;

    /**
     * @ORM\Column(type="integer")
     * @Groups("reference")
     */
    private $value_max;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getValueMin(): ?int
    {
        return $this->value_min;
    }

    public function setValueMin(int $value_min): self
    {
        $this->value_min = $value_min;

        return $this;
    }

    public function getValueMax(): ?int
    {
        return $this->value_max;
    }

    public function setValueMax(int $value_max): self
    {
        $this->value_max = $value_max;

        return $this;
    }
}
