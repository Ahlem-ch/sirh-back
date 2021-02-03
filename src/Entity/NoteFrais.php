<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteFraisRepository")
 */
class NoteFrais
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"note","public"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"note","public"})
     */
    private $piece_jointe;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"note","public"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="noteFrais")
     * @Groups({"note"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"note"})
     */
    private $created_at;

    /**
     * @Groups({"note"})
     */
    private $dateFormat;


    /**
     * @Groups({"note"})
     */
    private $dateAjoutFormat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"note"})
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $statut;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $Montant_HT;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $TVA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"note"})
     */
    private $TTC;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $remboursement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $cause_refus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"note"})
     */
    private $equipe;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPieceJointe(): ?string
    {
        return $this->piece_jointe;
    }

    public function setPieceJointe(string $piece_jointe): self
    {
        $this->piece_jointe = $piece_jointe;

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

    public function getDateFormat(): ?string
    {
        return $this->date->format('Y-m-d');;
    }

    public function getDateAjoutFormat(): ?string
    {
        return $this->created_at->format('Y-m-d');;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMontantHT(): ?int
    {
        return $this->Montant_HT;
    }

    public function setMontantHT(?int $Montant_HT): self
    {
        $this->Montant_HT = $Montant_HT;

        return $this;
    }

    public function getTVA(): ?int
    {
        return $this->TVA;
    }

    public function setTVA(?int $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    public function getRemboursement(): ?int
    {
        return $this->remboursement;
    }

    public function setRemboursement(?int $remboursement): self
    {
        $this->remboursement = $remboursement;

        return $this;
    }

    public function getTTC(): ?int
    {
        return $this->TTC;
    }

    public function setTTC(?int $TTC): self
    {
        $this->TTC = $TTC;

        return $this;
    }

    public function getCauseRefus(): ?string
    {
        return $this->cause_refus;
    }

    public function setCauseRefus(?string $cause_refus): self
    {
        $this->cause_refus = $cause_refus;

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
