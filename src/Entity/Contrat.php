<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use OpenApi\Annotations as OA;


/**
 * Class Contrat
 *
 * @package Contrat
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Contrat model",
 *     title="Contrat",
 *     required={"type"},
 *     @OA\Xml(
 *         name="Contrat"
 *     )
 * )
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"contrat","public","salaire","categorie"})
     * @OA\Property(
     *     format="int",
     *     description="Contrat ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"contrat","public","salaire","categorie"})
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"contrat","public","salaire","categorie"})
     * @OA\Property(
     *     format="string",
     *     description="Contrat type",
     *     title="type",
     * )
     *
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"contrat","public","salaire","categorie"})
     * @OA\Property(
     *     format="string",
     *     description="Experience date_debut",
     *     title="date_debut",
     * )
     *
     * @var string
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"contrat","public","salaire","categorie"})
     * @OA\Property(
     *     format="string",
     *     description="Experience date_fin",
     *     title="date_fin",
     * )
     *
     * @var string
     */
    private $date_fin;


    /**
     * @Groups({"contrat"})
     */
    private $debutFormat;

    /**
     * @Groups({"contrat"})
     */
    private $finFormat;

    /**
     * @ORM\Column(type="float")
     * @Groups({"contrat","public","salaire","categorie"})
     * @OA\Property(
     *     format="float",
     *     description="Contrat salaire actuel",
     *     title="salaire actuel",
     * )
     *
     * @var float
     */
    private $actuel_salaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contrat","public","salaire","categorie"})
     * @OA\Property(
     *     format="string",
     *     description="Contrat copie_contrat",
     *     title="copie_contrat",
     * )
     *
     * @var string
     */
    private $copie_contrat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Salaire", mappedBy="contrat", orphanRemoval=true)
     * @Groups({"contrat"})
     */
    private $salaires;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", mappedBy="contrats")
     * @Groups({"contrat"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="contrats")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"contrat","salaire"})
     * @OA\Property(
     *     description="Contrat user",
     *     title="user",type="array", @OA\Items(ref="#/components/schemas/User")
     * )
     *
     * @var string
     */
    private $user;


    public function __construct()
    {
        $this->salaires = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function setDateFin(?\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getActuelSalaire(): ?float
    {
        return $this->actuel_salaire;
    }

    public function setActuelSalaire(float $actuel_salaire): self
    {
        $this->actuel_salaire = $actuel_salaire;

        return $this;
    }

    public function getCopieContrat(): ?string
    {
        return $this->copie_contrat;
    }

    public function setCopieContrat(?string $copie_contrat): self
    {
        $this->copie_contrat = $copie_contrat;

        return $this;
    }

    /**
     * @return Collection|Salaire[]
     */
    public function getSalaires(): Collection
    {
        return $this->salaires;
    }

    public function addSalaire(Salaire $salaire): self
    {
        if (!$this->salaires->contains($salaire)) {
            $this->salaires[] = $salaire;
            $salaire->setContrat($this);
        }

        return $this;
    }

    public function removeSalaire(Salaire $salaire): self
    {
        if ($this->salaires->contains($salaire)) {
            $this->salaires->removeElement($salaire);
            // set the owning side to null (unless already changed)
            if ($salaire->getContrat() === $this) {
                $salaire->setContrat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addContrat($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeContrat($this);
        }

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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDebutFormat(): ?string
    {
        return $this->date_debut->format('Y-m-d');
    }

    public function getFinFormat(): ?string
    {
        if($this->date_fin != null) {
            return $this->date_fin->format('Y-m-d');
        } else {
            return $this->date_fin;
        }
    }
}
