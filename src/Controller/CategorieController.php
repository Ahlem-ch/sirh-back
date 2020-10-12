<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;


class CategorieController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;


    public function __construct(CategorieRepository $categorieRepository,EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->categorieRepository = $categorieRepository;
    }


    /**
     * List All Categories
     *
     * @OA\Get(
     *   path="/categories",
     *   summary="List all Categories",
     *   tags = {"Categorie"},
     *   operationId="indexCategorie",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="categories_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Categorie")),
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
    public function getCategoriesAction(){

        $data = $this->categorieRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['categorie']));

    }

    /**
     * Show an existing Categorie
     *
     * @OA\Get(
     *   path="/categories/{id}",
     *   summary="Show an existing Categorie",
     *   tags = {"Categorie"},
     *   operationId="showCategorie",
     *   @OA\Parameter(name="id", in="path", description="ID of Categorie to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="categorie_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Categorie")),
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
    public function getCategorieAction(int $id) {
        $data = $this->categorieRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['categorie']));

    }

    /**
     * Delete an existing Categorie
     *
     * @OA\Delete(
     *   path="/categories/{id}",
     *   summary="Delete an existing Categorie",
     *   tags = {"Categorie"},
     *   operationId="destroyCat",
     *   @OA\Parameter(name="id", in="path", description="ID of Categorie to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="categorie_deleted"),
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
    public function deleteCategorieAction(int $id) {
        $data = $this->categorieRepository->findOneBy(['id'=>$id]);

        if ($data){
            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $categories = $this->categorieRepository->findAll();

            return $this->view($categories,Response::HTTP_OK)->setContext((new Context())->setGroups(['categorie']));
        }
        return $this->view(null,Response::HTTP_NO_CONTENT);

    }

    /**
     * Create a new Categorie
     *
     * @OA\Post(
     *   path="/categories",
     *   summary="Create a new Categorie",
     *   tags = {"Categorie"},
     *   operationId="storeCategorie",
     *   requestBody={"$ref": "#/components/requestBodies/Categorie"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="categorie_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Categorie")),
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
    public function postCategorieAction (Request $request) {

        $libelle = $request->get('libelle');


        $categorie = new Categorie();

        $categorie->setLibelle($libelle);

        $this->entityManager->persist($categorie);
        $this->entityManager->flush();

        $categories = $this->categorieRepository->findAll();

        return $this->view($categories,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['categorie']));

    }

    /**
     * Update an existing Categorie
     *
     * @OA\Patch(
     *   path="/categories/{id}",
     *   summary="Update an existing Categorie",
     *   tags = {"Categorie"},
     *   operationId="updateCategories",
     *   requestBody={"$ref": "#/components/requestBodies/Categorie"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="categorie_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Categorie")),
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
    public function patchCategorieAction(Request $request, int $id)
    {

        $categorie = $this->categorieRepository->findOneBy(['id' => $id]);

        $libelle = $request->get('libelle');

        if ($categorie) {

            $categorie->setLibelle($libelle);

            $this->entityManager->persist($categorie);
            $this->entityManager->flush();

            $categories = $this->categorieRepository->findAll();


            return $this->view($categories, Response::HTTP_OK)->setContext((new Context())->setGroups(['categorie']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['categorie']));
    }
}
