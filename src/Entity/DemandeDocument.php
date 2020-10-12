<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeDocumentRepository")
 */
class DemandeDocument
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"demande_document"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"demande_document"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"demande_document"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeDocument")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"demande_document","typesDocument"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"demande_document","public"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"demande_document"})
     */
    private $created_at;


    public function __construct()
    {
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getType(): ?TypeDocument
    {
        return $this->type;
    }

    public function setType(?TypeDocument $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }


}
