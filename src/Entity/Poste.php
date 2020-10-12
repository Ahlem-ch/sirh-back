<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;

/**
 * Class Poste
 *
 * @package Poste
 * @ORM\Entity(repositoryClass="App\Repository\PosteRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="Poste model",
 *     title="Poste",
 *     required={"nom"},
 *     @OA\Xml(
 *         name="Poste"
 *     )
 * )
 */
class Poste
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @OA\Property(
     *     format="int",
     *     description="Poste ID",
     *     title="id",
     * )
     *
     * @var integer
     * @Groups({"public","poste"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @OA\Property(
     *     format="string",
     *     description="Poste libelle",
     *     title="libelle_poste",
     * )
     *
     * @var string
     * @Groups({"public","poste"})
     */
    private $libelle_poste;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="poste")
     * @OA\Property(
     *     description="Poste users",
     *     title="users",type="array", @OA\Items(ref="#/components/schemas/User")
     * )
     * @Groups({"poste"})
     */
    private $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibellePoste(): ?string
    {
        return $this->libelle_poste;
    }

    public function setLibellePoste(string $libelle_poste): self
    {
        $this->libelle_poste = $libelle_poste;

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
            $user->setPoste($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPoste() === $this) {
                $user->setPoste(null);
            }
        }

        return $this;
    }


}
