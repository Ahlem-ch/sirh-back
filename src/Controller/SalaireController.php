<?php

namespace App\Controller;

use App\Entity\Salaire;
use App\Repository\ContratRepository;
use App\Repository\SalaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use OpenApi\Annotations as OA;


class SalaireController extends AbstractFOSRestController
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
     * @var SalaireRepository
     */
    private $salaireRepository;
    /**
     * @var ContratRepository
     */
    private $contratRepository;


    public function __construct(SalaireRepository $salaireRepository,UserRepository $userRepository,ContratRepository $contratRepository,EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->salaireRepository = $salaireRepository;
        $this->contratRepository = $contratRepository;
    }


    /**
     * List All Salaires
     *
     * @OA\Get(
     *   path="/salaires",
     *   summary="List all Salaires",
     *   tags = {"Salaire"},
     *   operationId="indexSalaire",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="salaires_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Salaire")),
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
    public function getSalairesAction(){

        $data = $this->salaireRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['salaire']));

    }

    /**
     * Show an existing Salaire
     *
     * @OA\Get(
     *   path="/salaires/{id}",
     *   summary="Show an existing Salaire",
     *   tags = {"Salaire"},
     *   operationId="showSalaire",
     *   @OA\Parameter(name="id", in="path", description="ID of Salaire to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="salaire_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Salaire")),
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
    public function getSalaireAction(int $id) {
        $data = $this->salaireRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['salaire']));

    }

    /**
     * Delete an existing Salaire
     *
     * @OA\Delete(
     *   path="/salaires/{id}",
     *   summary="Delete an existing Salaire",
     *   tags = {"Salaire"},
     *   operationId="destroySalaire",
     *   @OA\Parameter(name="id", in="path", description="ID of Salaire to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="salaire_deleted"),
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
    public function deleteSalaireAction(int $id) {
        $data = $this->salaireRepository->findOneBy(['id'=>$id]);

        if ($data){
        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $salaires = $this->salaireRepository->findAll();

        return $this->view($salaires,Response::HTTP_OK)->setContext((new Context())->setGroups(['salaire']));
        }
        return $this->view(null,Response::HTTP_NO_CONTENT);
    }


    /**
     * Create a new Salaire
     *
     * @OA\Post(
     *   path="/salaires",
     *   summary="Create a new Salaire",
     *   tags = {"Salaire"},
     *   operationId="storeSalaire",
     *   requestBody={"$ref": "#/components/requestBodies/Salaire"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="salaire_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Salaire")),
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
     * @throws \Exception
     */
    public function postSalaireAction (Request $request) {

        $salaire_brut = $request->get('salaire_brut');
        $salaire_net = $request->get('salaire_net');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');
        $prime = $request->get('prime');
        $contrat_id = $request->get('contrat_id');
        $contrat = $this->contratRepository->findOneBy(['id' => $contrat_id]);


        $salaire = new Salaire();
        $salaire->setSalaireBrut($salaire_brut);
        $salaire->setSalaireNet($salaire_net);
        $date_deb = new \DateTime($date_debut);
        $salaire->setDateDebut($date_deb);
        if ( $date_fin!="null") {
            $date_f = new \DateTime($date_fin);
            $contrat->setDateFin($date_f);
        }
        if ($prime!="null"&& $prime!=""){
            $salaire->setPrime($prime);
        }
        $salaire->setContrat($contrat);



        $this->entityManager->persist($salaire);
        $this->entityManager->flush();

        $salaires = $this->salaireRepository->findAll();

        return $this->view($salaires,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['salaire']));

    }

    /**
     * Update an existing Salaire
     *
     * @OA\Patch(
     *   path="/salaires/{id}",
     *   summary="Update an existing Salaire",
     *   tags = {"Salaire"},
     *   operationId="updateSalaires",
     *   requestBody={"$ref": "#/components/requestBodies/Salaire"},
     *   @OA\Parameter(name="id", in="path", description="ID of Salaire to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="salaire_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Salaire")),
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
    public function patchSalaireAction(Request $request, int $id)
    {

        $salaire = $this->salaireRepository->findOneBy(['id' => $id]);

        $salaire_brut = $request->get('salaire_brut',$salaire->getSalaireBrut());
        $salaire_net = $request->get('salaire_net',$salaire->getSalaireNet());
        $date_debut = $request->get('date_debut',$salaire->getDateDebut());
        $date_fin = $request->get('date_fin',$salaire->getDateFin());
        $prime = $request->get('prime', $salaire->getPrime());

        if ($salaire) {

            $salaire->setSalaireBrut($salaire_brut);
            $salaire->setSalaireNet($salaire_net);
            $salaire->setSalaireNet($salaire_net);
            $date_deb = new \DateTime($date_debut);
            $salaire->setDateDebut($date_deb);
            $date_f = new \DateTime($date_fin);
            $salaire->setDateFin($date_f);
            $salaire->setPrime($prime);

            $this->entityManager->persist($salaire);
            $this->entityManager->flush();

            $salaires = $this->salaireRepository->findAll();


            return $this->view($salaires, Response::HTTP_OK)->setContext((new Context())->setGroups(['salaire']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['salaire']));
    }

    public function getSalaireByContratAction(Request $request){


        $id = $request->get('contrat');
        $data = $this->salaireRepository->findOneBy(['contrat'=> $id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['salaire']));

    }
}
