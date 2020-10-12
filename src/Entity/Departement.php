<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;


/**
 * Class Departement
 *
 * @package Departement
 * @ORM\Entity(repositoryClass="App\Repository\DepartementRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Departement model",
 *     title="Departement",
 *     required={"libelle_departement"},
 *     @OA\Xml(
 *         name="Departement"
 *     )
 * )
 */
class Departement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"departement","public","pointage"})
     * @OA\Property(
     *     format="int",
     *     description="Departement ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"departement","public","pointage"})
     * @OA\Property(
     *     format="string",
     *     description="Departement libelle",
     *     title="libelle_departement",
     * )
     *
     * @var string
     */
    private $libelle_departement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="departement")
     * @Groups({"departement"})
     * @OA\Property(
     *     description="Departement users",
     *     title="users",type="array", @OA\Items(ref="#/components/schemas/User")
     * )
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pointage", mappedBy="dpartement")
     */
    private $pointages;



    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->pointages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDepartement(): ?string
    {
        return $this->libelle_departement;
    }

    public function setLibelleDepartement(string $libelle_departement): self
    {
        $this->libelle_departement = $libelle_departement;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setDepartement($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getDepartement() === $this) {
                $user->setDepartement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pointage[]
     */
    public function getPointages(): Collection
    {
        return $this->pointages;
    }

    public function addPointage(Pointage $pointage): self
    {
        if (!$this->pointages->contains($pointage)) {
            $this->pointages[] = $pointage;
            $pointage->setDpartement($this);
        }

        return $this;
    }

    public function removePointage(Pointage $pointage): self
    {
        if ($this->pointages->contains($pointage)) {
            $this->pointages->removeElement($pointage);
            // set the owning side to null (unless already changed)
            if ($pointage->getDpartement() === $this) {
                $pointage->setDpartement(null);
            }
        }

        return $this;
    }

}
