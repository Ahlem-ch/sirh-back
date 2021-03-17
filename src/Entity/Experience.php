<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;

/**
 * Class Experience
 *
 * @package Experience
 * @ORM\Entity(repositoryClass="App\Repository\ExperienceRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Experience model",
 *     title="Experience",
 *     required={"intitule"},
 *     @OA\Xml(
 *         name="Experience"
 *     )
 * )
 */
class Experience
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"public","experience","typesExp"})
     * @OA\Property(
     *     format="int",
     *     description="Experience ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","typesExp"})
     * @OA\Property(
     *     format="string",
     *     description="Experience intitule",
     *     title="intitule",
     * )
     *
     * @var string
     */
    private $intitule;


    /**
     * @ORM\Column(type="datetime")
     * @Groups({"public","experience"})
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"public","experience","typesExp"})
     */
    private $date_fin;


    /**
     * @Groups({"experience"})
     */
    private $debutFormat;

    /**
     * @Groups({"experience"})
     */
    private $finFormat;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="experiences")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"experience"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeExp", inversedBy="experiences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?TypeExp
    {
        return $this->type;
    }

    public function setType(?TypeExp $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDebutFormat(): ?string
    {
        if ($this->date_fin != null) {
            return $this->date_debut->format('Y-m-d');
        }  else {
            return $this->date_debut;
        }
    }


    public function getFinFormat(): ?string
    {
        if ($this->date_fin != null) {
            return $this->date_fin->format('Y-m-d');
        } else {
            return $this->date_fin;
        }
    }

}
