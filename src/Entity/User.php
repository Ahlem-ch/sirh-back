<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class User
 *
 * @package User
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    use Timestamps;


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */

    private $email;

    /**
     * @ORM\Column(type="json")
     * @var string
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $matricule_hr;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $prenom;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $num_telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $cin_passport;

    /**
     * @ORM\Column(type="string", length=255, nullable= true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $etat_civil;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var string
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $copie_identite;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @var integer
     */
    private $nbr_enfants;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Experience", mappedBy="user", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $experiences;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $notifications;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Poste", inversedBy="users")
     * @Groups({"public"})
     */
    private $poste;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departement", inversedBy="users")
     * @Groups({"public"})
     */
    private $departement;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Diplome", mappedBy="user")
     * @Groups({"public"})
     */
    private $diplomes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="user")
     * @Groups({"public"})
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contrat", mappedBy="user", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $contrats;

    /**
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    public $naissance;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conge", mappedBy="employe", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $conges;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pointage", mappedBy="employe", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $pointages;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $solde;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $localisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public"})
     */
    private $maladie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public"})
     */
    private $mutuelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mission", mappedBy="user", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $missions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NoteFrais", mappedBy="user")
     * @Groups({"public"})
     */
    private $noteFrais;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AutorisationSortie", mappedBy="user", orphanRemoval=true)
     */
    private $autorisationSorties;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"public"})
     */
    private $solde_autorisation_sortie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $matricule_pointage;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Technologie", mappedBy="user")
     * @Groups({"public"})
     */
    private $technologies;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $sexe;

    /**
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     * @ORM\Column(type="string", nullable=true)
     */
    private $jira_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deplacement", mappedBy="user", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $deplacements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Teletravail", mappedBy="collaborateur", orphanRemoval=true)
     * @Groups({"public"})
     */
    private $teletravails;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"public","experience","poste","departement","diplome","document","contrat","salaire","conge","pointage",
     *     "notification","mission","note","autorisation","demande_document","technologie","deplacement","augmentation","teletravail"})
     */
    private $date_embauche;

    /**
     *
     * @ORM\PostLoad()
     */
    public function formatDate()
    {
        $this->naissance = $this->date_naissance->format('Y-m-d');
    }

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->diplomes = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->contrats = new ArrayCollection();
        $this->conges = new ArrayCollection();
        $this->pointages = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->noteFrais = new ArrayCollection();
        $this->autorisationSorties = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->deplacements = new ArrayCollection();
        $this->teletravails = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * @see UserInterface
     */
    public function getLocation(): string
    {
        return (string)$this->localisation;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return string
     */
    public function getMatriculeHr(): string
    {
        return $this->matricule_hr;
    }

    /**
     * @param string $matricule_hr
     */
    public function setMatriculeHr(string $matricule_hr): void
    {
        $this->matricule_hr = $matricule_hr;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getNumTelephone(): string
    {
        return $this->num_telephone;
    }

    /**
     * @param string $num_telephone
     */
    public function setNumTelephone(string $num_telephone): void
    {
        $this->num_telephone = $num_telephone;
    }

    /**
     * @return string
     */
    public function getCinPassport():? string
    {
        return $this->cin_passport;
    }

    /**
     * @param string $cin_passport
     */
    public function setCinPassport(string $cin_passport): void
    {
        $this->cin_passport = $cin_passport;
    }

    /**
     * @return string
     */
    public function getEtatCivil():? string
    {
        return $this->etat_civil;
    }

    /**
     * @param string $etat_civil
     */
    public function setEtatCivil(string $etat_civil): void
    {
        $this->etat_civil = $etat_civil;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getCopieIdentite(): string
    {
        return $this->copie_identite;
    }

    /**
     * @param string $copie_identite
     */
    public function setCopieIdentite(string $copie_identite): void
    {
        $this->copie_identite = $copie_identite;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

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
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->contains($experience)) {
            $this->experiences->removeElement($experience);
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection|Diplome[]
     */
    public function getDiplomes(): Collection
    {
        return $this->diplomes;
    }

    public function addDiplome(Diplome $diplome): self
    {
        if (!$this->diplomes->contains($diplome)) {
            $this->diplomes[] = $diplome;
            $diplome->setUser($this);
        }

        return $this;
    }

    public function removeDiplome(Diplome $diplome): self
    {
        if ($this->diplomes->contains($diplome)) {
            $this->diplomes->removeElement($diplome);
            // set the owning side to null (unless already changed)
            if ($diplome->getUser() === $this) {
                $diplome->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

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
            $contrat->setUser($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->contains($contrat)) {
            $this->contrats->removeElement($contrat);
            // set the owning side to null (unless already changed)
            if ($contrat->getUser() === $this) {
                $contrat->setUser(null);
            }
        }

        return $this;
    }

    public function getNbrEnfants(): ?int
    {
        return $this->nbr_enfants;
    }

    public function setNbrEnfants(?int $nbr_enfants): self
    {
        $this->nbr_enfants = $nbr_enfants;

        return $this;
    }

    /**
     * @return Collection|Conge[]
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conge $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setEmploye($this);
        }

        return $this;
    }

    public function removeConge(Conge $conge): self
    {
        if ($this->conges->contains($conge)) {
            $this->conges->removeElement($conge);
            // set the owning side to null (unless already changed)
            if ($conge->getEmploye() === $this) {
                $conge->setEmploye(null);
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
            $pointage->setEmploye($this);
        }

        return $this;
    }

    public function removePointage(Pointage $pointage): self
    {
        if ($this->pointages->contains($pointage)) {
            $this->pointages->removeElement($pointage);
            // set the owning side to null (unless already changed)
            if ($pointage->getEmploye() === $this) {
                $pointage->setEmploye(null);
            }
        }

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(?float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getMaladie(): ?string
    {
        return $this->maladie;
    }

    public function setMaladie(?string $maladie): self
    {
        $this->maladie = $maladie;

        return $this;
    }

    public function getMutuelle(): ?string
    {
        return $this->mutuelle;
    }

    public function setMutuelle(?string $mutuelle): self
    {
        $this->mutuelle = $mutuelle;

        return $this;
    }

    /**
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setUser($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->contains($mission)) {
            $this->missions->removeElement($mission);
            // set the owning side to null (unless already changed)
            if ($mission->getUser() === $this) {
                $mission->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NoteFrais[]
     */
    public function getNoteFrais(): Collection
    {
        return $this->noteFrais;
    }

    public function addNoteFrai(NoteFrais $noteFrai): self
    {
        if (!$this->noteFrais->contains($noteFrai)) {
            $this->noteFrais[] = $noteFrai;
            $noteFrai->setUser($this);
        }

        return $this;
    }

    public function removeNoteFrai(NoteFrais $noteFrai): self
    {
        if ($this->noteFrais->contains($noteFrai)) {
            $this->noteFrais->removeElement($noteFrai);
            // set the owning side to null (unless already changed)
            if ($noteFrai->getUser() === $this) {
                $noteFrai->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|AutorisationSortie[]
     */
    public function getAutorisationSorties(): Collection
    {
        return $this->autorisationSorties;
    }

    public function addAutorisationSorty(AutorisationSortie $autorisationSorty): self
    {
        if (!$this->autorisationSorties->contains($autorisationSorty)) {
            $this->autorisationSorties[] = $autorisationSorty;
            $autorisationSorty->setUser($this);
        }

        return $this;
    }

    public function removeAutorisationSorty(AutorisationSortie $autorisationSorty): self
    {
        if ($this->autorisationSorties->contains($autorisationSorty)) {
            $this->autorisationSorties->removeElement($autorisationSorty);
            // set the owning side to null (unless already changed)
            if ($autorisationSorty->getUser() === $this) {
                $autorisationSorty->setUser(null);
            }
        }

        return $this;
    }

    public function getSoldeAutorisationSortie(): ?float
    {
        return $this->solde_autorisation_sortie;
    }

    public function setSoldeAutorisationSortie(?float $solde_autorisation_sortie): self
    {
        $this->solde_autorisation_sortie = $solde_autorisation_sortie;

        return $this;
    }

    public function getMatriculePointage(): ?int
    {
        return $this->matricule_pointage;
    }

    public function setMatriculePointage(?int $matricule_pointage): self
    {
        $this->matricule_pointage = $matricule_pointage;

        return $this;
    }

    /**
     * @return Collection|Technologie[]
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technologie $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies[] = $technology;
            $technology->addUser($this);
        }

        return $this;
    }

    public function removeTechnology(Technologie $technology): self
    {
        if ($this->technologies->contains($technology)) {
            $this->technologies->removeElement($technology);
            $technology->removeUser($this);
        }

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getJiraId(): ?string
    {
        return $this->jira_id;
    }

    public function setJiraId(?string $jira_id): self
    {
        $this->jira_id = $jira_id;

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

    /**
     * @return Collection|Deplacement[]
     */
    public function getDeplacements(): Collection
    {
        return $this->deplacements;
    }

    public function addDeplacement(Deplacement $deplacement): self
    {
        if (!$this->deplacements->contains($deplacement)) {
            $this->deplacements[] = $deplacement;
            $deplacement->setUser($this);
        }

        return $this;
    }

    public function removeDeplacement(Deplacement $deplacement): self
    {
        if ($this->deplacements->contains($deplacement)) {
            $this->deplacements->removeElement($deplacement);
            // set the owning side to null (unless already changed)
            if ($deplacement->getUser() === $this) {
                $deplacement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Teletravail[]
     */
    public function getTeletravails(): Collection
    {
        return $this->teletravails;
    }

    public function addTeletravail(Teletravail $teletravail): self
    {
        if (!$this->teletravails->contains($teletravail)) {
            $this->teletravails[] = $teletravail;
            $teletravail->setCollaborateur($this);
        }

        return $this;
    }

    public function removeTeletravail(Teletravail $teletravail): self
    {
        if ($this->teletravails->contains($teletravail)) {
            $this->teletravails->removeElement($teletravail);
            // set the owning side to null (unless already changed)
            if ($teletravail->getCollaborateur() === $this) {
                $teletravail->setCollaborateur(null);
            }
        }

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->date_embauche;
    }

    public function setDateEmbauche(?\DateTimeInterface $date_embauche): self
    {
        $this->date_embauche = $date_embauche;

        return $this;
    }


}
