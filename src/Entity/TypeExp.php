<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use OpenApi\Annotations as OA;

/**
 * Class TypeExp
 *
 * @package TypeExp
 * @ORM\Entity(repositoryClass="App\Repository\TypeExpRepository")
 * @author  Znaidi Mahdi <mahdi.znaidi@esprit.tn>
 *
 * @OA\Schema(
 *     description="TypeExp model",
 *     title="TypeExp",
 *     required={"libelle"},
 *     @OA\Xml(
 *         name="TypeExp"
 *     )
 * )
 */
class TypeExp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"typesExp"})
     * @OA\Property(
     *     format="int",
     *     description="TypeExp ID",
     *     title="id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"typesExp"})
     * @OA\Property(
     *     format="string",
     *     description="TypeExp libelle",
     *     title="string",
     * )
     *
     * @var integer
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Experience", mappedBy="type")
     * @Groups({"typesExp"})
     * @OA\Property(
     *     description="TypeExp experiences",
     *     title="experiences",type="array", @OA\Items(ref="#/components/schemas/Experience")
     * )
     */
    private $experiences;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
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
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setType($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->contains($experience)) {
            $this->experiences->removeElement($experience);
            // set the owning side to null (unless already changed)
            if ($experience->getType() === $this) {
                $experience->setType(null);
            }
        }

        return $this;
    }
}
