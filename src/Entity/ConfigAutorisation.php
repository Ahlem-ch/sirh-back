<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigAutorisationRepository")
 */
class ConfigAutorisation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"config_autorisation"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"config_autorisation"})
     */
    private $nb_autorisation;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"config_autorisation"})
     */
    private $duree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbAutorisation(): ?float
    {
        return $this->nb_autorisation;
    }

    public function setNbAutorisation(float $nb_autorisation): self
    {
        $this->nb_autorisation = $nb_autorisation;

        return $this;
    }

    public function getDuree(): ?float
    {
        return $this->duree;
    }

    public function setDuree(?float $duree): self
    {
        $this->duree = $duree;

        return $this;
    }
}
