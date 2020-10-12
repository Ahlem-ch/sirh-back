<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeCongeRepository")
 */
class TypeConge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"typesConge","conge"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"typesConge","conge"})
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\conge", mappedBy="typeConge")
     */
    private $conge;

    public function __construct()
    {
        $this->conge = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|conge[]
     */
    public function getConge(): Collection
    {
        return $this->conge;
    }

    public function addConge(conge $conge): self
    {
        if (!$this->conge->contains($conge)) {
            $this->conge[] = $conge;
            $conge->setTypeConge($this);
        }

        return $this;
    }

    public function removeConge(conge $conge): self
    {
        if ($this->conge->contains($conge)) {
            $this->conge->removeElement($conge);
            // set the owning side to null (unless already changed)
            if ($conge->getTypeConge() === $this) {
                $conge->setTypeConge(null);
            }
        }

        return $this;
    }

}
