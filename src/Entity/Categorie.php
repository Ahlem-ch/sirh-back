<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;


/**
 * Class Categorie
 *
 * @package Contrat
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Categorie model",
 *     title="Categorie",
 *     @OA\Xml(
 *         name="Categorie"
 *     )
 * )
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"categorie","contrat","sous_categorie"})
     * @OA\Property(
     *     format="int",
     *     description="Categorie ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"categorie","contrat","sous_categorie"})
     * @OA\Property(
     *     format="string",
     *     description="Categorie libelle",
     *     title="string",
     * )
     *
     * @var string
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Contrat", inversedBy="categories")
     * @Groups({"categorie"})
     * @OA\Property(
     *     description="Categorie contrat",
     *     title="contrats",type="array", @OA\Items(ref="#/components/schemas/Contrat")
     * )
     */
    private $contrats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SousCategorie", mappedBy="categories", orphanRemoval=true)
     * @Groups({"categorie"})
     */
    private $sousCategories;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->sousCategories = new ArrayCollection();
    }

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

    /**
     * @return Collection|Contrat[]
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats[] = $contrat;
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->contains($contrat)) {
            $this->contrats->removeElement($contrat);
        }

        return $this;
    }

    /**
     * @return Collection|SousCategorie[]
     */
    public function getSousCategories(): Collection
    {
        return $this->sousCategories;
    }

    public function addSousCategory(SousCategorie $sousCategory): self
    {
        if (!$this->sousCategories->contains($sousCategory)) {
            $this->sousCategories[] = $sousCategory;
            $sousCategory->setCategories($this);
        }

        return $this;
    }

    public function removeSousCategory(SousCategorie $sousCategory): self
    {
        if ($this->sousCategories->contains($sousCategory)) {
            $this->sousCategories->removeElement($sousCategory);
            // set the owning side to null (unless already changed)
            if ($sousCategory->getCategories() === $this) {
                $sousCategory->setCategories(null);
            }
        }

        return $this;
    }
}
