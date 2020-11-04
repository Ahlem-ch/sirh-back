<?php

namespace App\Controller;

use App\Entity\Diplome;
use App\Entity\Experience;
use App\Entity\Technologie;
use App\Entity\User;
use App\Repository\ConfigAutorisationRepository;
use App\Repository\ConfigSoldeCongeRepository;
use App\Repository\DepartementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\DocumentRepository;
use App\Repository\ExperienceRepository;
use App\Repository\NoteFraisRepository;
use App\Repository\PosteRepository;
use App\Repository\ReferenceEmpRepository;
use App\Repository\TechnologieRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @method successResponse(string $string)
 */
class RegistrationController extends AbstractFOSRestController
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DiplomeRepository
     */
    private $diplomeRepository;
    /**
     * @var PosteRepository
     */
    private $posteRepository;
    /**
     * @var DepartementRepository
     */
    private $departementRepository;
    /**
     * @var ExperienceRepository
     */
    private $experienceRepository;
    /**
     * @var ConfigAutorisationRepository
     */
    private $configAutorisationRepository;
    /**
     * @var ReferenceEmpRepository
     */
    private $referenceEmpRepository;
    /**
     * @var ConfigSoldeCongeRepository
     */
    private $configSoldeCongeRepository;
    /**
     * @var NoteFraisRepository
     */
    private $noteFraisRepository;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;
    /**
     * @var TechnologieRepository
     */
    private $technologieRepository;


    /**
     * RegistrationController constructor.
     * @param UserRepository $userRepository
     * @param DiplomeRepository $diplomeRepository
     * @param NoteFraisRepository $noteFraisRepository
     * @param PosteRepository $posteRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param DepartementRepository $departementRepository
     * @param ExperienceRepository $experienceRepository
     * @param ReferenceEmpRepository $referenceEmpRepository
     * @param DocumentRepository $documentRepository
     * @param ConfigSoldeCongeRepository $configSoldeCongeRepository
     * @param ConfigAutorisationRepository $configAutorisationRepository
     * @param TechnologieRepository $technologieRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, DiplomeRepository $diplomeRepository, NoteFraisRepository $noteFraisRepository,
                                PosteRepository $posteRepository, UserPasswordEncoderInterface $passwordEncoder, DepartementRepository $departementRepository,
                                ExperienceRepository $experienceRepository,ReferenceEmpRepository $referenceEmpRepository,DocumentRepository $documentRepository,
                                ConfigSoldeCongeRepository $configSoldeCongeRepository, ConfigAutorisationRepository $configAutorisationRepository,
                                TechnologieRepository $technologieRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->diplomeRepository = $diplomeRepository;
        $this->posteRepository = $posteRepository;
        $this->departementRepository = $departementRepository;
        $this->experienceRepository = $experienceRepository;
        $this->referenceEmpRepository = $referenceEmpRepository;
        $this->configSoldeCongeRepository = $configSoldeCongeRepository;
        $this->configAutorisationRepository = $configAutorisationRepository;
        $this->noteFraisRepository = $noteFraisRepository;
        $this->documentRepository = $documentRepository;
        $this->technologieRepository = $technologieRepository;
    }


    /**
     * Create a new User
     *
     * @OA\Post(
     *   path="/register",
     *   summary="Create a new User",
     *   tags = {"User"},
     *   operationId="storeUser",
     *   requestBody={"$ref": "#/components/requestBodies/User"},
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="user_created"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/User")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     *
     */
    /**
     * @Route("/api/register", name="registration")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param ValidatorInterface $validator
     * @param Swift_Mailer $mailer
     * @param int $length
     * @return View
     * @throws \Exception
     */
    public function register(Request $request, FileUploader $fileUploader, ValidatorInterface $validator, Swift_Mailer $mailer, $length = 12)
    {
//      dump($request->getContent());

        $diplomes = $request->get('diplomes', null);
        $experiences = $request->get('experiences', null);
        $nom = $request->get('nom');
        $matricule_pointage = $request->get('matricule_pointage', null);
        $jira_id = $request->get('jira_id', null);
        $email = $request->get('email');
        $prenom = $request->get('prenom');
        $adresse = $request->get('adresse');
        $num_telephone = $request->get('num_telephone');
        $cin_passport = $request->get('cin_passport');
        $etat_civil = $request->get('etat_civil');
        $sexe = $request->get('sexe');
        $date_naissance = $request->get('date_naissance', null);
        $location = $request->get('emplacement');
        $nbr_enfants = $request->get('nbr_enfants', null);
        $poste_id = $request->get('poste_id', null);
        $poste = $this->posteRepository->findOneBy(['id' => $poste_id]);
        $departement_id = $request->get('departement_id');
        $departement = $this->departementRepository->findOneBy(['id' => $departement_id]);


        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);


        if (!is_null($user)) {
            return $this->view([
                'message' => 'User is alrady exist'
            ], Response::HTTP_CONFLICT);
        }

        $user = new User();

        //upload files
        $files = $fileUploader->upload($request);
        if (count($files) === 2) {
            $image = $files['image'];
            $user->setImage($image);
        } else if (count($files) === 4) {
            $image = $files['image'];

            $maladie = $files['maladie'];
            $mutuelle = $files['mutuelle'];
            $user->setImage($image);
            $user->setMaladie($maladie);
            $user->setMutuelle($mutuelle);
        }

        // fonction pour la création d'un mot de passe

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $new_password = '';
        for ($i = 0; $i < $length; $i++) {
            $new_password .= $characters[rand(0, $charactersLength - 1)];
        }
        $user->setPassword($this->passwordEncoder->encodePassword($user, $new_password));

        //////////////////////////////////////////////////////////////////////////

        $user->setRoles(["ROLE_USER"]);
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setEmail($email);
        $date_naiss = \DateTime::createFromFormat('D M d Y H:i:s e+', $date_naissance);
        $user->setDateNaissance(new \DateTime($date_naiss));



        //ajout d'une matricule HR configurable sinon un valeur par défaut est attribué

        $reference = $this->referenceEmpRepository->findOneBy([]);

        if($reference !== null) {
            $val = $reference->getLibelle();
            $min = $reference->getValueMin();
            $max = $reference->getValueMax();

            do {
                $random = random_int($min, $max);
                $matricule_hr = $val . $random;
                $array = $this->userRepository->findBy(['matricule_hr' => $matricule_hr]);
            } while ($array != null);

        } else if ($reference === null) {
            do {
                $random = random_int(1, 9999);
                $matricule_hr = 'Emp' . $random;
                $array = $this->userRepository->findBy(['matricule_hr' => $matricule_hr]);
            } while ($array != null);
        }

        $user->setMatriculeHr($matricule_hr);

        //////////////////////////////////////////////////////////////////////////////////




        $user->setAdresse($adresse);
        $user->setNumTelephone($num_telephone);
        $user->setCinPassport($cin_passport);
        $user->setEtatCivil($etat_civil);
        $user->setSexe($sexe);
        $user->setStatut('actuel');
        $user->setCopieIdentite("");

        if ($nbr_enfants != "null") {
            $user->setNbrEnfants($nbr_enfants);
        }

        if ($matricule_pointage != "null") {
            $user->setMatriculePointage($matricule_pointage);
        }

        if ($jira_id != "null") {
            $user->setJiraId($jira_id);
        }

        if ($sexe != "null") {
            $user->setSexe($sexe);
        }

        //ajout du solde de congé configurable sinon un valeur par défaut est attribué

        $conge = $this->configSoldeCongeRepository->findOneBy([]);

        if($conge !== null) {
            $val = $conge->getSolde();
            $user->setSolde($val);

        } else if ($conge === null) {
            $user->setSolde(0);
        }

        ///////////////////////////////////////////////////////////////////////


        //ajout du solde des autorisations de sortie configurable sinon un valeur par défaut est attribué

        $autorisation = $this->configAutorisationRepository->findOneBy([]);

        if($autorisation !== null) {
            $val = $autorisation->getNbAutorisation();
            $user->setSoldeAutorisationSortie($val);

        } else if ($autorisation === null) {
            $user->setSoldeAutorisationSortie(0);
        }

        ///////////////////////////////////////////////////////////////////////




        $user->setMatriculeHr($matricule_hr);
        $user->setLocalisation($location);
        $user->cretaedAt();
        $user->updatedAt();
        if ($poste) {
            $user->setPoste($poste);
        }
        if ($departement) {
            $user->setDepartement($departement);
        }
        foreach ((array)$diplomes as $value_id) {
            $value = $this->diplomeRepository->find(['id' => $value_id]);

            $user->addDiplome($value);
        }
        foreach ((array)$experiences as $value_id) {
            $value = $this->experienceRepository->find(['id' => $value_id]);

            $user->addExperience($value);
        }

        /**
         * @var $err ConstraintViolationList
         */
        $err = $validator->validate($user);
        $pattern = '/^(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
        if (count($err) > 0) {

            $message = [];
            $array = $err->getIterator()->getArrayCopy();
            /**
             * @var  $item ConstraintViolation
             */
            foreach ($array as $item) {
                //Remplir $message avec $item->getMessage()
                array_push($message, $item->getMessage());
            }
            return $this->view($message, Response::HTTP_BAD_REQUEST);
        } elseif (preg_match($pattern, $new_password)) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();


            // contenu du mail de bienvenue

            $body = ' Nous sommes très heureux de vous avoir dans notre groupe ! 
                      Nous croyons que vous pouvez utiliser vos compétences et vos talents pour aider <br>
                      notre entreprise à atteindre de nouveaux sommets. Bienvenue à bord ! <br>
                      Vous pouvez accéder  maintenant à notre platforme <div style="color:#0d5aa7">sirh.prod-projet.com</div>.<br>
                      Ton mot de passe est : <b>' . $new_password . '</b><br> 
                      Pour le changer il suffit de se connecter, d\'aller à ton espace personnel et de choisir un nouveau';
            $message = (new Swift_Message('Bienvenue à notre platforme'))
                ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
                ->setTo([$email])
                ->setBody($body)
                ->setContentType('text/html')
                ->attach(\Swift_Attachment::fromPath(__DIR__.'/../../public/uploads/images/agence-inspire-tunisie.jpg'));
            $mailer->send($message);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $new_password));

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////


            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->view($user, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['public']));
        }
        $messages = 'The password should contains minimum eight characters, at least one letter and one number';
        return $this->view($messages, Response::HTTP_BAD_REQUEST);
    }


    /**
     * List All Users
     *
     * @OA\Get(
     *   path="/users",
     *   summary="List all Users",
     *   tags = {"User"},
     *   operationId="index2",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="users_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     *
     */
    public function getUsersAction()
    {

        $data = $this->userRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));
    }


    /**
     * @Route("/api/archiver/{id}", name="archiverUser")
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function archiverCollaborateur(Request $request, int $id)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {
            $statut = $request->get('statut', $user->getStatut());


            $user->setStatut($statut);

            $user->cretaedAt();
            $user->updatedAt();

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api/users/{equipe}/{statut}", name="usersEquipe")
     * @param string $equipe
     * @param string $statut
     * @return View
     */
    public function getUsersEquipeeAction(string $equipe, string $statut)
    {

        $data = $this->userRepository->findBy(['localisation'=> $equipe, 'statut'=> $statut]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));
    }


    /**
     * @Route("/api/user/{id}", name="usersEquipeId")
     * @param int $id
     * @return View
     */
    public function userById(int $id)
    {
        $data = $this->userRepository->findOneBy(['id'=> $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

    }


    /**
     * @Route("/api/user/{id}", name="deleteUser")
     * @param $id
     * @return View
     */
    public function deleteUserAction(int $id)
    {
        $data = $this->userRepository->findOneBy(['id' => $id]);
        $diplome = $this->diplomeRepository->findBy(['user' => $id]);
        $note = $this->noteFraisRepository->findBy(['user' => $id]);
        $document = $this->documentRepository->findBy(['user' => $id]);

        foreach ($diplome as $value_id) {
            $dep = $this->diplomeRepository->find(['id' => $value_id]);
            $data->removeDiplome($dep);
        }

        foreach ($note as $value) {
            $n = $this->noteFraisRepository->find(['id' => $value]);
            $n->setUser(Null);
        }

        foreach ($document as $value) {
            $d = $this->documentRepository->find(['id' => $value]);
            $d->setUser(Null);
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $data = $this->userRepository->findAll();

        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api/user/{id}/{equipe}", name="deleteUserEquipe")
     * @param int $id
     * @param string $equipe
     * @return View
     */
    public function deleteUserEquipeAction(int $id, string $equipe)
    {
        $data = $this->userRepository->findOneBy(['id' => $id]);
        $diplome = $this->diplomeRepository->findBy(['user' => $id]);
        $note = $this->noteFraisRepository->findBy(['user' => $id]);
        $document = $this->documentRepository->findBy(['user' => $id]);

        foreach ($diplome as $value_id) {
            $dep = $this->diplomeRepository->find(['id' => $value_id]);
            $data->removeDiplome($dep);
        }

        foreach ($note as $value) {
            $n = $this->noteFraisRepository->find(['id' => $value]);
            $n->setUser(Null);
        }

        foreach ($document as $value) {
            $d = $this->documentRepository->find(['id' => $value]);
            $d->setUser(Null);
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $data = $this->userRepository->findBy(['localisation'=> $equipe]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api/update/{id}/users/diplome", name="update")
     * @Method({"POST"})
     * @param Request $request
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function putDiplomeAction(Request $request, int $id)
    {

        $libelle = $request->get('libelle');
        $type = $request->get('type');
        $ecole = $request->get('ecole');
        $annee = $request->get('annee');

        $diplome = new Diplome();
        $diplome->setLibelle($libelle);
        $diplome->setType($type);
        $diplome->setEcole($ecole);
        $diplome->setAnnee(new \DateTime("$annee"));

        $this->entityManager->persist($diplome);
        $this->entityManager->flush();

        $user = $this->userRepository->findOneBy(['id' => $id]);

        if ($user) {

            $user->addDiplome($diplome);

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }


    /**
     * Update an existing User
     *
     * @OA\Patch(
     *   path="/update/{id}/users/experience",
     *   summary="Update an existing User",
     *   tags = {"User"},
     *   operationId="updateUser",
     *   requestBody={"$ref": "#/components/requestBodies/User"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="user_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/User")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/update/{id}/users/experience", name="updateExp")
     * @Method({"POST"})
     * @param Request $request
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function putExperienceAction(Request $request, int $id)
    {

        $intitule = $request->get('intitule');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

        $experience = new Experience();
        $experience->setIntitule($intitule);
        $date_deb = new \DateTime($date_debut);
        $experience->setDateDebut($date_deb);
        if($date_fin != "null") {
            $date_f = new \DateTime($date_fin);
            $experience->setDateFin($date_f);
        }

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {

            $user->addExperience($experience);

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api/update/{id}/users/technologie", name="updateTechnologie")
     * @Method({"POST"})
     * @param Request $request
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function putTechnologieAction(Request $request, int $id)
    {

        $intitule = $request->get('libelle');

        $technologie = new Technologie();
        $technologie->setLibelle($intitule);

        $this->entityManager->persist($technologie);
        $this->entityManager->flush();

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {

            $user->addTechnology($technologie);

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api/affect/{id}/users/technologie", name="updateTechnologie")
     * @Method({"POST"})
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function affectTechnologieAction(Request $request,int $id)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        $id_tec  = $request->get('technologie_id');
        $technologie = $this->technologieRepository->findOneBy(['id' => $id_tec]);

        if ($user) {

            $user->addTechnology($technologie);

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * @Route("/api/remove/user/{id}/technologie/{id_tec}", name="removeTechnologie")
     * @Method({"POST"})
     * @param int $id
     * @param int $id_tec
     * @return View
     */
    public function removeTechnologieAction(int $id, int $id_tec)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        $technologie = $this->technologieRepository->findOneBy(['id' => $id_tec]);

        if ($user && $technologie) {

            $user->removeTechnology($technologie);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }

    /**
     * Update an existing User
     *
     * @OA\Patch(
     *   path="/update/users/{id}",
     *   summary="Update an existing User",
     *   tags = {"User"},
     *   operationId="updateUser",
     *   requestBody={"$ref": "#/components/requestBodies/User"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="user_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/User")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/update/{id}/users", name="updateUser")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function modifierUser(Request $request, FileUploader $fileUploader, int $id)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {
            $email = $request->get('email', $user->getEmail());
            $matricule_hr = $request->get('matricule_hr', $user->getMatriculeHr());
            $matricule_pointage = $request->get('matricule_pointage', $user->getMatriculePointage());
            $jira_id = $request->get('jira_id', $user->getJiraId());
            $nom = $request->get('nom', $user->getNom());
            $prenom = $request->get('prenom', $user->getPrenom());
            $adresse = $request->get('adresse', $user->getAdresse());
            $num_telephone = $request->get('num_telephone', $user->getNumTelephone());
            $cin_passport = $request->get('cin_passport', $user->getCinPassport());
            $etat_civil = $request->get('etat_civil', $user->getEtatCivil());
            $sexe = $request->get('sexe', $user->getSexe());
            $nbr_enfants = $request->get('nbr_enfants', $user->getNbrEnfants());
            $solde = $request->get('solde', $user->getSolde());
            $autorisation = $request->get('autorisation', $user->getSoldeAutorisationSortie());
            $emplacement = $request->get('localisation', $user->getLocalisation());
            $date_naissance = $request->get('date_naissance');
            $poste_id = $request->get('poste_id', null);
            $poste = $this->posteRepository->findOneBy(['id' => $poste_id]);
            $departement_id = $request->get('departement_id', null);
            $departement = $this->departementRepository->findOneBy(['id' => $departement_id]);

            $files = $fileUploader->upload($request);
            if (count($files) == 2) {
                $image = $files['image'];
                $user->setImage($image);
            }

            if ($poste) {
                $user->setPoste($poste);
            }

            if ($departement) {
                $user->setDepartement($departement);
            }

            $diplome_id = $request->get('diplome_id', null);

            if ($diplome_id) {
                $diplome = $this->diplomeRepository->find(['id' => $diplome_id]);
                $user->addDiplome($diplome);
            }
            $user->setEmail($email);
            $user->setMatriculeHr($matricule_hr);
            $user->setMatriculePointage($matricule_pointage);
            $user->setJiraId($jira_id);
            $user->setPrenom($prenom);
            $user->setNom($nom);
            if($date_naissance){
                $naissance = new \DateTime($date_naissance);
                $user->setDateNaissance($naissance);
            }
            $user->setAdresse($adresse);
            $user->setNumTelephone($num_telephone);
            $user->setCinPassport($cin_passport);
            $user->setSexe($sexe);
            $user->setEtatCivil($etat_civil);
            $user->setNbrEnfants($nbr_enfants);
            $user->setSolde($solde);
            $user->setSoldeAutorisationSortie($autorisation);
            $user->setLocalisation($emplacement);
            $user->setCopieIdentite("");

            $user->cretaedAt();
            $user->updatedAt();

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

        }
        return $this->view($user, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['public']));
    }


    public function putUserSoldeAction()
    {

        $data = $this->userRepository->findAll();
        $list_id = [];
        foreach ($data as $d) {
            array_push($list_id, $d->getId());
        }

        foreach ($list_id as $id) {
            $emp = $this->userRepository->findOneBy(['id' => $id]);
            $emp->setSolde($emp->getSolde() + 2);
            $this->entityManager->persist($emp);
            $this->entityManager->flush();
        }
    }

    /**
     * @Route("/api/update/{id}/maladie", name="updateMaladie")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function modifierMaladieUser(Request $request, FileUploader $fileUploader, int $id)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {
            $files = $fileUploader->upload($request);

            if (count($files) == 2) {
                $image = $files['maladie'];
                $user->setMaladie($image);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

    }

    /**
     * @Route("/api/update/{id}/mutuelle", name="updateMutuelle")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function modifierMutuelleUser(Request $request, FileUploader $fileUploader, int $id)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {
            $files = $fileUploader->upload($request);
            if (count($files) == 2) {
                $image = $files['mutuelle'];
                $user->setMutuelle($image);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

    }

    /**
     * @Route("/api/update/{id}/solde/conge", name="updateSoldeConge")
     * @param Request $request
     * @param int $id
     * @return View
     * @throws \Exception
     */
    public function modifierSoldeUser(Request $request, int $id)
    {

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user) {
            $solde = $request->get('solde', null);
            $soldeAutorisation = $request->get('soldeAutorisation', null);
            if($solde) {
                $user->setSolde($user->getSolde() - $solde);
            }
            if($soldeAutorisation) {
                $user->setSoldeAutorisationSortie($user->getSoldeAutorisationSortie() - 1);
            }
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));
        }
    }


    /**
     * @Route("/api/change_password/{id}", name="changePassword")
     * @param Request $request
     * @param int $id
     * @return View
     */
    public function postChangePassword(Request $request, int $id)
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if ($user) {
            $old_pwd = $request->get('old_password');
            $new_pwd = $request->get('new_password');
            $checkPass = $this->passwordEncoder->isPasswordValid($user, $old_pwd);

            if ($checkPass === true) {
                $new_pass = $this->passwordEncoder->encodePassword($user, $new_pwd);
                $user->setPassword($new_pass);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->view($user, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));
            } else {
                $this->view(null, Response::HTTP_NOT_FOUND);
            }
        }
    }


    /**
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @param int $length
     * @Route("/password/reset", name="mail")
     * @return View
     */
    public function postSendMail(Request $request, Swift_Mailer $mailer, $length = 12) {


        $email = $request->get('email', null);
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if ($user) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $new_password = '';
            for ($i = 0; $i < $length; $i++) {
                $new_password .= $characters[rand(0, $charactersLength - 1)];
            }
            $body = 'Votre nouveau mot de passe est : <b>' . $new_password . '</b><br> 
                 Pour le changer il suffit de se connecter, d\'aller à ton espace personnel et de choisir un nouveau';
            $message = (new Swift_Message('Réinsialisation du mot de passe'))
                ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
                ->setTo([$email])
                ->setBody($body)
                ->setContentType('text/html')
                ->attach(\Swift_Attachment::fromPath(__DIR__.'/../../public/uploads/images/agence-inspire-tunisie.jpg'));
            $mailer->send($message);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $new_password));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->view(null, Response::HTTP_OK);
        } else {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/change/{role}/user/{id}", name="changeRole")
     * @param string $role
     * @param int $id
     * @return View
     */
    public function changeRole(string $role, int $id)
    {

        $user = $this->userRepository->findOneBy(['id'=> $id]);
        if($user) {
            $user->setRoles([$role]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        $data = $this->userRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

    }

    /**
     * @Route("/api/{role}/users", name="usersByRole")
     * @param string $role
     * @return View
     */
    public function getCollaborateurByRole(string $role)
    {

        $data = $this->userRepository->userByRole($role);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['public']));

    }


}


