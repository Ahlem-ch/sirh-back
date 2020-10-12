<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Repository\CategorieRepository;
use App\Repository\ContratRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use OpenApi\Annotations as OA;


class ContratController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ContratRepository
     */
    private $contratRepository;
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;


    public function __construct(ContratRepository $contratRepository,UserRepository $userRepository,
                                CategorieRepository $categorieRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->contratRepository = $contratRepository;
        $this->categorieRepository = $categorieRepository;
    }


    /**
     * List All Contrats
     *
     * @OA\Get(
     *   path="/contrats",
     *   summary="List all Contrats",
     *   tags = {"Contrat"},
     *   operationId="indexContrat",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="contrats_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Contrat")),
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
    public function getContratsAction(){

     //   $d =$this->getUser()->getId();

        $data = $this->contratRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['contrat']));

    }

    /**
     * Show an existing Contrat
     *
     * @OA\Get(
     *   path="/contrats/{id}",
     *   summary="Show an existing Contrat",
     *   tags = {"Contrat"},
     *   operationId="showContrat",
     *   @OA\Parameter(name="id", in="path", description="ID of Contrat to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="contrat_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Contrat")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     */
    public function getContratAction(int $id) {
        $data = $this->contratRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['contrat']));

    }

    /**
     * Delete an existing Contrat
     *
     * @OA\Delete(
     *   path="/contrats/{id}",
     *   summary="Delete an existing Contrat",
     *   tags = {"Contrat"},
     *   operationId="destroyContrat",
     *   @OA\Parameter(name="id", in="path", description="ID of Contrat to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="contrat_deleted"),
     *       @OA\Property(property="data", @OA\Items()),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteContratAction(int $id) {
        $data = $this->contratRepository->findOneBy(['id'=>$id]);

        if($data){
        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $contrats = $this->contratRepository->findAll();

        return $this->view($contrats,Response::HTTP_OK)->setContext((new Context())->setGroups(['contrat']));
        }
        return $this->view(null,Response::HTTP_NO_CONTENT);

    }

    /**
     * Create a new Contrat
     *
     * @OA\Post(
     *   path="/contrats",
     *   summary="Create a new Contrat",
     *   tags = {"Contrat"},
     *   operationId="storeContrat",
     *   requestBody={"$ref": "#/components/requestBodies/Contrat"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="contrat_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Contrat")),
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
      *@Rest\Route("/api/contrat", name="addContrat")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function contrat (Request $request,FileUploader $fileUploader) {

        $type = $request->get('type');
        $dateDebut = $request->get('date_debut');
        $dateFin = $request->get('date_fin');
        $actuelSalaire = $request->get('actuel_salaire');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);
        $categorie_id = $request->get('categorie');
        $categorie = $this->categorieRepository->findOneBy(['id' => $categorie_id]);

        //upload image
        $files = $fileUploader->upload($request);

        $contrat = new Contrat();

        if (count($files) == 2 ) {
            $image = $files['copie_contrat'];
            $contrat->setCopieContrat($image);
        }

        do {
            $random = random_int(1, 9999);
            $ref = 'contrat' . $random;
            $array = $this->contratRepository->findBy(['ref' => $ref] );
        } while ($array != null);

        $contrat->setType($type);
        $contrat->setRef($ref);
        $date_deb = new \DateTime($dateDebut);
        $contrat->setDateDebut($date_deb);
        if ( $dateFin!="null") {
            $date_fin = new \DateTime($dateFin);
            $contrat->setDateFin($date_fin);
        }
        $contrat->setActuelSalaire($actuelSalaire);
        $contrat->setUser($user);
        if ($categorie) {
            $contrat->addCategory($categorie);
        }
        $this->entityManager->persist($contrat);
        $this->entityManager->flush();

        $contrats = $this->contratRepository->findAll();

        return $this->view($contrats,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['contrat']));

    }


    /**
     * Update an existing Contrat
     *
     * @OA\Patch(
     *   path="/contrats/{id}",
     *   summary="Update an existing Contrat",
     *   tags = {"Contrat"},
     *   operationId="updateContrats",
     *   requestBody={"$ref": "#/components/requestBodies/Contrat"},
     *   @OA\Parameter(name="id", in="path", description="ID of Contrat to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="contrat_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Contrat")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/update/{id}/contrat", name="updateContrat")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function modifierContrat(Request $request, FileUploader $fileUploader, int $id)
    {

        $contrat = $this->contratRepository->findOneBy(['id' => $id]);

        $type = $request->get('type', null);
        $dateDebut = $request->get('date_debut');
        $dateFin = $request->get('date_fin');
        $actuelSalaire = $request->get('actuel_salaire', null);
        $categorie_id = $request->get('categorie', null);
        $categorie = $this->categorieRepository->findOneBy(['id' => $categorie_id]);


        if ($contrat) {

            $contrat->setType($type);
            $date_deb = new \DateTime($dateDebut);
            $contrat->setDateDebut($date_deb);
            if($type == 'CDI'){
              $contrat->setDateFin(null);
            }else {
                $date_fin = new \DateTime($dateFin);
                $contrat->setDateFin($date_fin);
            }
            $contrat->setActuelSalaire($actuelSalaire);
            $files = $fileUploader->upload($request);
            if (count($files) == 2 ) {
                $image = $files['copie_contrat'];
                $contrat->setCopieContrat($image);
            }
            if ($categorie) {
                $contrat->addCategory($categorie);
            }

            $this->entityManager->persist($contrat);
            $this->entityManager->flush();

            $contrats = $this->contratRepository->findAll();


            return $this->view($contrats, Response::HTTP_OK)->setContext((new Context())->setGroups(['contrat']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['contrat']));
    }
}
