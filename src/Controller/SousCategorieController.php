<?php

namespace App\Controller;

use App\Entity\SousCategorie;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;


class SousCategorieController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SousCategorieRepository
     */
    private $sousCategorieRepository;
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;


    /**
     * SousCategorieController constructor.
     * @param SousCategorieRepository $sousCategorieRepository
     * @param CategorieRepository $categorieRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SousCategorieRepository $sousCategorieRepository, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->sousCategorieRepository = $sousCategorieRepository;
        $this->categorieRepository = $categorieRepository;
    }


    /**
     * List All SousCategories
     *
     * @OA\Get(
     *   path="/souscategories",
     *   summary="List all SousCategories",
     *   tags = {"SousCategorie"},
     *   operationId="indexSousCategorie",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="SousCategories_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SousCategorie")),
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
    public function getSouscategoriesAction(){

        $data = $this->sousCategorieRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['sous_categorie']));

    }
    /**
     * Show an existing SousCategorie
     *
     * @OA\Get(
     *   path="/souscategories/{id}",
     *   summary="Show an existing SousCategorie",
     *   tags = {"SousCategorie"},
     *   operationId="showSousCategorie",
     *   @OA\Parameter(name="id", in="path", description="ID of SousCategorie to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="SousCategorie_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/SousCategorie")),
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
    public function getSouscategorieAction(int $id) {
        $data = $this->sousCategorieRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['sous_categorie']));

    }

    /**
     * Delete an existing SousCategorie
     *
     * @OA\Delete(
     *   path="/souscategories/{id}",
     *   summary="Delete an existing SousCategorie",
     *   tags = {"SousCategorie"},
     *   operationId="destroySousCat",
     *   @OA\Parameter(name="id", in="path", description="ID of SousCategorie to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="SousCategorie_deleted"),
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
    public function deleteSouscategorieAction(int $id) {
        $data = $this->sousCategorieRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $sous_categories = $this->sousCategorieRepository->findAll();

            return $this->view($sous_categories, Response::HTTP_OK)->setContext((new Context())->setGroups(['sous_categorie']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * Create a new SousCategorie
     *
     * @OA\Post(
     *   path="/souscategories",
     *   summary="Create a new SousCategorie",
     *   tags = {"SousCategorie"},
     *   operationId="storeSousCategorie",
     *   requestBody={"$ref": "#/components/requestBodies/SousCategorie"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="SousCategorie_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/SousCategorie")),
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
    public function postSouscategorieAction (Request $request) {

        $libelle = $request->get('libelle');
        $id = $request->get('categorie');
        $categorie = $this->categorieRepository->findOneBy(['id'=> $id]);


        $sous_categorie = new sousCategorie();

        $sous_categorie->setLibelle($libelle);
        $sous_categorie->setCategories($categorie);

        $this->entityManager->persist($sous_categorie);
        $this->entityManager->flush();
        $sous_categories = $this->sousCategorieRepository->findAll();

        return $this->view($sous_categories,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['sous_categorie']));

    }

    /**
     * Update an existing SousCategorie
     *
     * @OA\Patch(
     *   path="/souscategories/{id}",
     *   summary="Update an existing SousCategorie",
     *   tags = {"SousCategorie"},
     *   operationId="updateSousCategories",
     *   requestBody={"$ref": "#/components/requestBodies/SousCategorie"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="SousCategorie_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/SousCategorie")),
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
    public function patchSouscategorieAction(Request $request, int $id)
    {

        $sous_categorie = $this->sousCategorieRepository->findOneBy(['id' => $id]);

        if ($sous_categorie) {

            $libelle = $request->get('libelle');
            $sous_categorie->setLibelle($libelle);

            $id = $request->get('categorie', null);
            $categorie = $this->categorieRepository->findOneBy(['id'=> $id]);

            if ($categorie){
                $sous_categorie->setCategories($categorie);
            }

            $this->entityManager->persist($sous_categorie);
            $this->entityManager->flush();

            $sous_categories = $this->sousCategorieRepository->findAll();

            return $this->view($sous_categories, Response::HTTP_OK)->setContext((new Context())->setGroups(['sous_categorie']));

        }
        return $this->view($sous_categorie, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['sous_categorie']));
    }

}
