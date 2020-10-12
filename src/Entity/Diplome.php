<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use OpenApi\Annotations as OA;


/**
 * Class Diplome
 *
 * @package Diplome
 * @ORM\Entity(repositoryClass="App\Repository\DiplomeRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Diplome model",
 *     title="Diplome",
 *     required={"libelle_diplome"},
 *     @OA\Xml(
 *         name="Diplome"
 *     )
 * )
 */
class Diplome
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"diplome","public"})
     * @OA\Property(
     *     format="int",
     *     description="Diplome ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"diplome","public"})
     * @OA\Property(
     *     format="string",
     *     description="Diplome libelle",
     *     title="libelle",
     * )
     *
     * @var string
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"diplome","public"})
     * @OA\Property(
     *     format="string",
     *     description="Diplome type",
     *     title="type",
     * )
     *
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"diplome","public"})
     * @OA\Property(
     *     format="string",
     *     description="Diplome type",
     *     title="annee",example="2019-10-09T00:00:00+00:00"
     * )
     *
     * @var string
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="diplomes")
     * @Groups({"diplome"})
     * @OA\Property(
     *     description="Diplome user",
     *     title="users",type="array", @OA\Items(ref="#/components/schemas/User")
     * )
     *
     * @var string
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"diplome","public"})
     * @OA\Property(
     *     format="string",
     *     description="Diplome ecole",
     *     title="ecole",
     * )
     *
     * @var string
     */
    private $ecole;

    /**
     * @Groups({"diplome","public"})
     */
    private $anneeFormat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"diplome","public"})
     */
    private $pi_jointe;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

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

    public function getEcole(): ?string
    {
        return $this->ecole;
    }

    public function setEcole(string $ecole): self
    {
        $this->ecole = $ecole;

        return $this;
    }

    public function getAnneeFormat(): ?string
    {
        return $this->annee->format('Y-m');
    }

    public function getPiJointe(): ?string
    {
        return $this->pi_jointe;
    }

    public function setPiJointe(?string $pi_jointe): self
    {
        $this->pi_jointe = $pi_jointe;

        return $this;
    }

}
