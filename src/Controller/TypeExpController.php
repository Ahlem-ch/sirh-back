<?php

namespace App\Controller;

use App\Entity\TypeExp;
use App\Repository\ExperienceRepository;
use App\Repository\TypeExpRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use OpenApi\Annotations as OA;


class TypeExpController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TypeExpRepository
     */
    private $typeExpRepository;
    /**
     * @var ExperienceRepository
     */
    private $experienceRepository;


    public function __construct(TypeExpRepository $typeExpRepository, ExperienceRepository $experienceRepository ,EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->typeExpRepository = $typeExpRepository;
        $this->experienceRepository = $experienceRepository;
    }

    /**
     * List All TypeExps
     *
     * @OA\Get(
     *   path="/types/exp",
     *   summary="List all TypeExps",
     *   tags = {"TypeExperience"},
     *   operationId="indexTypeExp",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="typeExps_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TypeExp")),
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
    public function getTypesExpAction(){

        $data = $this->typeExpRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesExp']));

    }
    /**
     * Show an existing TypeExp
     *
     * @OA\Get(
     *   path="/types/{id}/exp",
     *   summary="Show an existing TypeExp",
     *   tags = {"TypeExperience"},
     *   operationId="showTypeExp",
     *   @OA\Parameter(name="id", in="path", description="ID of TypeExp to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="typeExp_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/TypeExp")),
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
    public function getTypeExpAction(int $id) {
        $data = $this->typeExpRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesExp']));

    }
    /**
     * Delete an existing TypeExp
     *
     * @OA\Delete(
     *   path="/types/{id}/exp",
     *   summary="Delete an existing TypeExp",
     *   tags = {"TypeExperience"},
     *   operationId="destroyTypeExp",
     *   @OA\Parameter(name="id", in="path", description="ID of TypeExp to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="typeExp_deleted"),
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
    public function deleteTypeExpAction(int $id) {
        $data = $this->typeExpRepository->findOneBy(['id'=>$id]);
        $experience = $this->experienceRepository->findOneBy(['type'=>$id]);

        if($experience){
            $experience->setType(null);
        }
        $this->entityManager->remove($data);
        $this->entityManager->flush();

        return $this->view(null,Response::HTTP_NO_CONTENT);

    }

    /**
     * Create a new TypeExp
     *
     * @OA\Post(
     *   path="/types/exps",
     *   summary="Create a new TypeExp",
     *   tags = {"TypeExperience"},
     *   operationId="storeTypeExp",
     *   requestBody={"$ref": "#/components/requestBodies/TypeExp"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="typeExp_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/TypeExp")),
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
    public function postTypesExpAction (Request $request) {

        $libelle = $request->get('libelle');

        $types_exp = new TypeExp();
        $types_exp->setLibelle($libelle);


        $this->entityManager->persist($types_exp);
        $this->entityManager->flush();

        return $this->view($types_exp,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['typesExp']));

    }


    /**
     * Update an existing TypeExp
     *
     * @OA\Patch(
     *   path="/types/{id}/exp",
     *   summary="Update an existing TypeExp",
     *   tags = {"TypeExperience"},
     *   operationId="updateTypeExps",
     *   requestBody={"$ref": "#/components/requestBodies/TypeExp"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="typeExp_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/TypeExp")),
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
    public function patchTypeExpAction(Request $request, int $id)
    {

        $types_exp = $this->typeExpRepository->findOneBy(['id' => $id]);

        $libelle = $request->get('libelle');

        if ($types_exp) {

            $types_exp->setLibelle($libelle);

            $this->entityManager->persist($types_exp);
            $this->entityManager->flush();

            return $this->view($types_exp, Response::HTTP_OK)->setContext((new Context())->setGroups(['typesExp']));

        }
        return $this->view($types_exp, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['typesExp']));
    }
}
