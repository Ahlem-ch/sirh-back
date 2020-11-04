<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;

class PosteController extends AbstractFOSRestController
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
     * @var PosteRepository
     */
    private $posteRepository;

    public function __construct(PosteRepository $posteRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->posteRepository = $posteRepository;
    }

    /**
     * List All Postes
     *
     * @OA\Get(
     *   path="/postes",
     *   summary="List all Postes",
     *   tags = {"Poste"},
     *   operationId="indexPoste",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="postes_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Poste")),
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
    public function getPostesAction()
    {


        $data = $this->posteRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['poste']));

    }


    /**
     * Show an existing Poste
     *
     * @OA\Get(
     *   path="/postes/{id}",
     *   summary="Show an existing Poste",
     *   tags = {"Poste"},
     *   operationId="showPoste",
     *   @OA\Parameter(name="id", in="path", description="ID of Poste to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="poste_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Poste")),
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
    public function getPosteAction(int $id)
    {
        $data = $this->posteRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['poste']));

    }


    /**
     * Delete an existing Poste
     *
     * @OA\Delete(
     *   path="/postes/{id}",
     *   summary="Delete an existing Poste",
     *   tags = {"Poste"},
     *   operationId="destroyPoste",
     *   @OA\Parameter(name="id", in="path", description="ID of Departement to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="poste_deleted"),
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
    public function deletePosteAction(int $id)
    {
        $data = $this->posteRepository->findOneBy(['id' => $id]);
        $users = $this->userRepository->findBy(['poste' => $id]);
        foreach ($users as $value_id) {
            $value_id->setPoste(null);
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $postes = $this->posteRepository->findAll();

        return $this->view($postes, Response::HTTP_OK)->setContext((new Context())->setGroups(['poste']));

    }


    /**
     * Create a new Poste
     *
     * @OA\Post(
     *   path="/postes",
     *   summary="Create a new Poste",
     *   tags = {"Poste"},
     *   operationId="storePoste",
     *   requestBody={"$ref": "#/components/requestBodies/Poste"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="poste_created"),
     *       @OA\Property(property="data", @OA\Property(property="id",type="integer"),@OA\Property(property="libelle_poste",type="string")),
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
     * @return \FOS\RestBundle\View\View
     */
    public function postPosteAction(Request $request)
    {

        $libelle_poste = $request->get('libelle_poste');
        $poste = new Poste();

        $poste->setLibellePoste($libelle_poste);

        $this->entityManager->persist($poste);
        $this->entityManager->flush();

        $postes = $this->posteRepository->findAll();

        return $this->view($postes, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['poste']));

    }

    /**
     * Create a new Poste
     *
     * @OA\Put(
     *   path="/postes/adds/users",
     *   summary="AffectUser",
     *   tags = {"Poste"},
     *   operationId="AffectUser",
     *   requestBody={"$ref": "#/components/requestBodies/Poste"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="poste_created"),
     *       @OA\Property(property="data", @OA\Property(property="id",type="integer"),@OA\Property(property="libelle_poste",type="string")),
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
     * @return \FOS\RestBundle\View\View
     */
    public function putPosteAddUserAction(Request $request)
    {

        $id = $request->get('id');
        $poste = $this->posteRepository->findOneBy(['id' => $id]);
        if ($poste) {
            $user_id = $request->get('user_id');
            $user = $this->userRepository->findOneBy(['id' => $user_id]);

            $poste->addUser($user);

            $this->entityManager->persist($poste);
            $this->entityManager->flush();

            $postes = $this->posteRepository->findAll();

            return $this->view($postes, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['poste']));
        }

        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['poste']));

    }


    /**
     * Update an existing Poste
     *
     * @OA\Patch(
     *   path="/postes/{id}",
     *   summary="Update an existing Poste",
     *   tags = {"Poste"},
     *   operationId="updatePoste",
     *   requestBody={"$ref": "#/components/requestBodies/Poste"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="poste_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Poste")),
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
    public function patchPosteAction(Request $request, int $id)
    {


        $poste = $this->posteRepository->findOneBy(['id' => $id]);

        $libelle_poste = $request->get('libelle_poste');

        if ($poste) {

            $poste->setLibellePoste($libelle_poste);

            $this->entityManager->persist($poste);
            $this->entityManager->flush();
            $postes = $this->posteRepository->findAll();

            return $this->view($postes, Response::HTTP_OK)->setContext((new Context())->setGroups(['poste']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['poste']));
    }




}
