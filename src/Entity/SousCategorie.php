<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;


/**
 * Class SousCategorie
 *
 * @package SousCategorie
 * @ORM\Entity(repositoryClass="App\Repository\SousCategorieRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="SousCategorie model",
 *     title="SousCategorie",
 *     required={"libelle"},
 *     @OA\Xml(
 *         name="SousCategorie"
 *     )
 * )
 */
class SousCategorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"sous_categorie","categorie"})
     * @OA\Property(
     *     format="int",
     *     description="SousCategorie ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"sous_categorie","categorie"})
     * @OA\Property(
     *     format="string",
     *     description="SousCategorie libelle",
     *     title="libelle",
     * )
     *
     * @var string
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="sousCategories")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"sous_categorie"})
     * @OA\Property(
     *     description="Categorie",
     *     title="categories",type="array", @OA\Items(ref="#/components/schemas/Categorie")
     * )
     */
    private $categories;

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

    public function getCategories(): ?Categorie
    {
        return $this->categories;
    }

    public function setCategories(?Categorie $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}
