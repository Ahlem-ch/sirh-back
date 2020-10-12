<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use OpenApi\Annotations as OA;


/**
 * Class Document
 *
 * @package Document
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Document model",
 *     title="Document",
 *     required={"libelle_document"},
 *     @OA\Xml(
 *         name="Document"
 *     )
 * )
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"document","public"})
     * @OA\Property(
     *     format="int",
     *     description="Document ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"document","public"})
     * @OA\Property(
     *     format="string",
     *     description="Document reférence",
     *     title="référence",
     * )
     *
     * @var string
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"document"})
     * @OA\Property(
     *     format="string",
     *     description="Departement libelle",
     *     title="libelle_document",
     * )
     *
     * @var string
     */
    private $libelle_document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"document","public"})
     * @OA\Property(
     *     format="string",
     *     description="Document image",
     *     title="image",
     * )
     *
     * @var string
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="documents")
     * @Groups({"document"})
     * @OA\Property(
     *     description="Document users",
     *     title="users",type="array", @OA\Items(ref="#/components/schemas/User")
     * )
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"document","public"})
     */
    private $visibilite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeDocument", inversedBy="document")
     * @Groups({"document"})
     */
    private $typeDocument;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"document"})
     */
    private $equipe;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDocument(): ?string
    {
        return $this->libelle_document;
    }

    public function setLibelleDocument(string $libelle_document): self
    {
        $this->libelle_document = $libelle_document;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getVisibilite(): ?string
    {
        return $this->visibilite;
    }

    public function setVisibilite(?string $visibilite): self
    {
        $this->visibilite = $visibilite;

        return $this;
    }

    public function getTypeDocument(): ?TypeDocument
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(?TypeDocument $typeDocument): self
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

    public function getEquipe(): ?string
    {
        return $this->equipe;
    }

    public function setEquipe(?string $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }

}
