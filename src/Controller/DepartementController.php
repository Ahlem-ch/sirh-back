<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Repository\DepartementRepository;
use App\Repository\PointageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;


class DepartementController extends AbstractFOSRestController
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
     * @var DepartementRepository
     */
    private $departementRepository;
    /**
     * @var PointageRepository
     */
    private $pointageRepository;

    /**
     * DepartementController constructor.
     * @param DepartementRepository $departementRepository
     * @param UserRepository $userRepository
     * @param PointageRepository $pointageRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(DepartementRepository $departementRepository, UserRepository $userRepository,
                                PointageRepository $pointageRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->departementRepository = $departementRepository;
        $this->pointageRepository = $pointageRepository;
    }

    /**
     * List All Departements
     *
     * @OA\Get(
     *   path="/departements",
     *   summary="List all Departements",
     *   tags = {"Departement"},
     *   operationId="indexDepartement",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="departements_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Departement")),
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
    public function getDepartementsAction()
    {

        $data = $this->departementRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['departement']));

    }


    /**
     * Show an existing Departement
     *
     * @OA\Get(
     *   path="/departements/{id}",
     *   summary="Show an existing Departement",
     *   tags = {"Departement"},
     *   operationId="showDepartement",
     *   @OA\Parameter(name="id", in="path", description="ID of Departement to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="departement_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Departement")),
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
    public function getDepartementAction(int $id)
    {
        $data = $this->departementRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['departement']));

    }

    /**
     * Delete an existing Departement
     *
     * @OA\Delete(
     *   path="/departements/{id}",
     *   summary="Delete an existing Departement",
     *   tags = {"Departement"},
     *   operationId="destroyDep",
     *   @OA\Parameter(name="id", in="path", description="ID of Departement to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="departement_deleted"),
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
    public function deleteDepartementAction(int $id)
    {
        $data = $this->departementRepository->findOneBy(['id' => $id]);
        $users = $this->userRepository->findBy(['departement' => $id]);
        $departements = $this->pointageRepository->findBy(['dpartement' => $id]);

        foreach ($departements as $value_id) {
            $value_id->setDpartement(null);
        }

        foreach ($users as $value_id) {
            $value_id->setDepartement(null);
        }
        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $departements = $this->departementRepository->findAll();

        return $this->view($departements, Response::HTTP_OK)->setContext((new Context())->setGroups(['departement']));

    }

    /**
     * Create a new Departement
     *
     * @OA\Post(
     *   path="/departements",
     *   summary="Create a new Departement",
     *   tags = {"Departement"},
     *   operationId="storeDepartement",
     *   requestBody={"$ref": "#/components/requestBodies/Departement"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="departement_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Departement")),
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
    public function postDepartementAction(Request $request)
    {

        $libelle_departement = $request->get('libelle_departement');

        $departement = new Departement();
        $departement->setLibelleDepartement($libelle_departement);


        $this->entityManager->persist($departement);
        $this->entityManager->flush();
        $departements = $this->departementRepository->findAll();

        return $this->view($departements, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['departement']));

    }


    /**
     * Update an existing Departement
     *
     * @OA\Patch(
     *   path="/departements/{id}",
     *   summary="Update an existing Departement",
     *   tags = {"Departement"},
     *   operationId="updateDepartements",
     *   requestBody={"$ref": "#/components/requestBodies/Departement"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="departement_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Departement")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function patchDepartementsAction(Request $request, int $id)
    {

        $departement = $this->departementRepository->findOneBy(['id' => $id]);

        $libelle_departement = $request->get('libelle_departement');

        if ($departement) {

            $departement->setLibelleDepartement($libelle_departement);

            $this->entityManager->persist($departement);
            $this->entityManager->flush();
            $departements = $this->departementRepository->findAll();
            return $this->view($departements, Response::HTTP_OK)->setContext((new Context())->setGroups(['departement']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['departement']));
    }
}
