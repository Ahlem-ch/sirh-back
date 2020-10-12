<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use OpenApi\Annotations as OA;

/**
 * Class Salaire
 *
 * @package Salaire
 * @ORM\Entity(repositoryClass="App\Repository\SalaireRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Salaire model",
 *     title="Salaire",
 *     required={"salaire_brut"},
 *     @OA\Xml(
 *         name="Salaire"
 *     )
 * )
 */
class Salaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"salaire","contrat"})
     * @OA\Property(
     *     format="int",
     *     description="Salaire ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"salaire","Salaire"})
     * @OA\Property(
     *     format="float",
     *     description="Salaire brut",
     *     title="salaire_brut",
     * )
     *
     * @var float
     */
    private $salaire_brut;

    /**
     * @ORM\Column(type="float")
     * @Groups({"salaire","contrat"})
     * @OA\Property(
     *     format="float",
     *     description="Salaire net",
     *     title="salaire_net",
     * )
     *
     * @var float
     */
    private $salaire_net;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"salaire","contrat"})
     * @OA\Property(
     *     format="float",
     *     description="prime",
     *     title="prime",
     * )
     *
     * @var float
     */
    private $prime;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"salaire","contrat"})
     * @OA\Property(
     *     format="string",
     *     description="Salaire date_debut",
     *     title="date_debut",
     * )
     *
     * @var string
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"salaire","contrat"})
     * @OA\Property(
     *     format="string",
     *     description="Salaire date_fin",
     *     title="date_fin",
     * )
     *
     * @var string
     */
    private $date_fin;

    /**
     * @Groups({"salaire","contrat"})
     */
    private $debutFormat;

    /**
     * @Groups({"salaire","contrat"})
     */
    private $finFormat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contrat", inversedBy="salaires")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"salaire"})
     * @OA\Property(
     *     description="Contrat",
     *     title="contrat",type="array", @OA\Items(ref="#/components/schemas/Contrat")
     * )
     *
     * @var string
     */
    private $contrat;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalaireBrut(): ?float
    {
        return $this->salaire_brut;
    }

    public function setSalaireBrut(float $salaire_brut): self
    {
        $this->salaire_brut = $salaire_brut;

        return $this;
    }

    public function getSalaireNet(): ?float
    {
        return $this->salaire_net;
    }

    public function setSalaireNet(float $salaire_net): self
    {
        $this->salaire_net = $salaire_net;

        return $this;
    }

    public function getPrime(): ?float
    {
        return $this->prime;
    }

    public function setPrime(float $prime): self
    {
        $this->prime = $prime;

        return $this;
    }


    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

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
