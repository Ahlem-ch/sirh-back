<?php

namespace App\Controller;

use App\Entity\Diplome;
use App\Repository\DiplomeRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;



class DiplomeController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DiplomeRepository
     */
    private $diplomeRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(DiplomeRepository $diplomeRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->diplomeRepository = $diplomeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * List All Diplomes
     *
     * @OA\Get(
     *   path="/diplomes",
     *   summary="List all Diplomes",
     *   tags = {"Diplome"},
     *   operationId="indexDiplome",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="diplomes_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Diplome")),
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
    public function getDiplomesAction()
    {

        $data = $this->diplomeRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['diplome']));

    }

    /**
     * Show an existing Diplome
     *
     * @OA\Get(
     *   path="/diplomes/{id}",
     *   summary="Show an existing Diplome",
     *   tags = {"Diplome"},
     *   operationId="showDiplome",
     *   @OA\Parameter(name="id", in="path", description="ID of Diplome to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="Diplome_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Diplome")),
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
    public function getDiplomeAction(int $id)
    {
        $data = $this->diplomeRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['diplome']));

    }


    /**
     * Delete an existing Diplome
     *
     * @OA\Delete(
     *   path="/diplomes/{id}",
     *   summary="Delete an existing Diplome",
     *   tags = {"Diplome"},
     *   operationId="destroyDiplome",
     *   @OA\Parameter(name="id", in="path", description="ID of Diplome to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="diplome_deleted"),
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
    public function deleteDiplomeAction(int $id)
    {
        $data = $this->diplomeRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $diplomes = $this->diplomeRepository->findAll();


        return $this->view($diplomes, Response::HTTP_OK)->setContext((new Context())->setGroups(['diplome']));

    }


    /**
     * Create a new Diplome
     *
     * @OA\Post(
     *   path="/diplomes",
     *   summary="Create a new Diplome",
     *   tags = {"Diplome"},
     *   operationId="storeDiplome",
     *   requestBody={"$ref": "#/components/requestBodies/Diplome"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="Diplome_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Diplome")),
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
     * /
     /**
     * @Route("/api/diplome", name="addDiplome")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function diplome(Request $request, FileUploader $fileUploader)
    {

        $libelle = $request->get('libelle');
        $type = $request->get('type');
        $ecole = $request->get('ecole');
        $annee = $request->get('annee');
        $response = $request->get('response');

        //upload image
        $files = $fileUploader->upload($request);

        $diplome = new Diplome();

        if (count($files) == 2 ) {
            $image = $files['piece_jointe'];
            $diplome->setPiJointe($image);
        }

        $diplome->setLibelle($libelle);
        $diplome->setType($type);
        $diplome->setEcole($ecole);
        $ann = new \DateTime($annee);
        $diplome->setAnnee($ann);

        $this->entityManager->persist($diplome);
        $this->entityManager->flush();

        if ($response == 'one'){
            return $this->view($diplome, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['diplome']));
        } else {
            return $this->getDiplomesAction();
        }
    }

    /**
     * Update an existing Diplome
     *
     * @OA\Patch(
     *   path="/diplomes/{id}",
     *   summary="Update an existing Diplome",
     *   tags = {"Diplome"},
     *   operationId="updateDiplomes",
     *   requestBody={"$ref": "#/components/requestBodies/Diplome"},
     *   @OA\Parameter(name="id", in="path", description="ID of diplome to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="diplome_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Diplome")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/update/{id}/diplome", name="updateDiplome")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function modifierDiplome(Request $request, FileUploader $fileUploader, int $id)
    {

        $diplome = $this->diplomeRepository->findOneBy(['id' => $id]);

        $libelle = $request->get('libelle');
        $type = $request->get('type');
        $ecole = $request->get('ecole');
        $annee = $request->get('annee');


        if ($diplome) {

            $diplome->setLibelle($libelle);
            $diplome->setType($type);
            $diplome->setEcole($ecole);
            $ann = new \DateTime($annee);
            $diplome->setAnnee($ann);
            $files = $fileUploader->upload($request);
            if (count($files) == 2 ) {
                $image = $files['piece_jointe'];
                $diplome->setPiJointe($image);
            }

            $this->entityManager->persist($diplome);
            $this->entityManager->flush();
            $diplomes = $this->diplomeRepository->findAll();


            return $this->view($diplomes, Response::HTTP_OK)->setContext((new Context())->setGroups(['diplome']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['diplome']));
    }
}

