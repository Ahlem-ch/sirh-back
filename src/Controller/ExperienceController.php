<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use OpenApi\Annotations as OA;

class ExperienceController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ExperienceRepository
     */
    private $experienceRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(ExperienceRepository $experienceRepository, UserRepository $userRepository ,EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->experienceRepository = $experienceRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * List All Experiences
     *
     * @OA\Get(
     *   path="/experiences",
     *   summary="List all Experiences",
     *   tags = {"Experience"},
     *   operationId="indexExperience",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="experiences_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Experience")),
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
    public function getExperiencesAction(){

        $data = $this->experienceRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));

    }


    /**
     * Show an existing Experience
     *
     * @OA\Get(
     *   path="/experiences/{id}",
     *   summary="Show an existing Experience",
     *   tags = {"Experience"},
     *   operationId="showExperience",
     *   @OA\Parameter(name="id", in="path", description="ID of Experience to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="experience_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Experience")),
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
    public function getExperienceAction(int $id) {
        $data = $this->experienceRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));

    }

    /**
     * Delete an existing Experience
     *
     * @OA\Delete(
     *   path="/experiences/{id}",
     *   summary="Delete an existing Experience",
     *   tags = {"Experience"},
     *   operationId="destroyExperience",
     *   @OA\Parameter(name="id", in="path", description="ID of Experience to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="experience_deleted"),
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
    public function deleteExperienceAction(int $id) {
        $data = $this->experienceRepository->findOneBy(['id'=>$id]);
        if($data) {
            $this->entityManager->remove($data);
            $this->entityManager->flush();
            $experiences = $this->experienceRepository->findAll();
            return $this->view($experiences, Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);


    }


    /**
     * Create a new Experience
     *
     * @OA\Post(
     *   path="/experiences",
     *   summary="Create a new Experience",
     *   tags = {"Experience"},
     *   operationId="storeExperience",
     *   requestBody={"$ref": "#/components/requestBodies/Experience"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="experience_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Experience")),
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
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postExperienceAction (Request $request) {

        $intitule = $request->get('intitule');
        $type = $request->get('type');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');
        $user_id = $request->get('user');
        $response = $request->get('response');

        $experience = new Experience();
        $user =$this->userRepository->findOneBy(['id'=>$user_id]);

        $experience->setIntitule($intitule);
        $experience->setType($type);
        $date_deb = new \DateTime($date_debut);
        $experience->setDateDebut($date_deb);
        if($date_fin != "null") {
            $date_f = new \DateTime($date_fin);
        }
        $experience->setDateFin($date_f);
        if($user) {
            $experience->setUser($user);
        }
        $this->entityManager->persist($experience);
        $this->entityManager->flush();


        if ($response == 'one') {
            return $this->view($experience, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['experience']));
        } else {
            return $this->getExperiencesAction();
        }

    }


    /**
     * Update an existing Experience
     *
     * @OA\Patch(
     *   path="/experiences/{id}",
     *   summary="Update an existing Experience",
     *   tags = {"Experience"},
     *   operationId="updateExperiences",
     *   requestBody={"$ref": "#/components/requestBodies/Experience"},
     *   @OA\Parameter(name="id", in="path", description="ID of Experience to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="experience_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Experience")),
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
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchExperienceAction(Request $request, int $id)
    {

        $experience = $this->experienceRepository->findOneBy(['id' => $id]);

        $intitule = $request->get('intitule');
        $type = $request->get('type');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

        if ($experience) {


            $experience->setIntitule($intitule);
            $experience->setType($type);
            $date_deb = new \DateTime($date_debut);
            $experience->setDateDebut($date_deb);
            $date_f = new \DateTime($date_fin);
            $experience->setDateFin($date_f);


            $this->entityManager->persist($experience);
            $this->entityManager->flush();

            $experiences = $this->experienceRepository->findAll();

            return $this->view($experiences, Response::HTTP_OK)->setContext((new Context())->setGroups(['experience']));

        }
        return $this->view($experience, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['experience']));
    }

}
