<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Repository\CongeRepository;
use App\Repository\TypeCongeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CongeController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CongeRepository
     */
    private $congeRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TypeCongeRepository
     */
    private $typeCongeRepository;

    /**
     * CongeController constructor.
     * @param EntityManagerInterface $entityManager
     * @param CongeRepository $congeRepository
     * @param UserRepository $userRepository
     * @param TypeCongeRepository $typeCongeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CongeRepository $congeRepository, UserRepository $userRepository, TypeCongeRepository $typeCongeRepository)
    {
        $this->entityManager = $entityManager;
        $this->congeRepository = $congeRepository;
        $this->userRepository = $userRepository;
        $this->typeCongeRepository = $typeCongeRepository;
    }

    public function getCongesAction(){

        $data = $this->congeRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));

    }

    public function getCongeAction(int $id) {
        $data = $this->congeRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));

    }


    public function deleteCongeAction(int $id) {
        $data = $this->congeRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $conges = $this->congeRepository->findAll();

            return $this->view(   $conges, Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postCongeAction (Request $request) {

        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');
        $duree = $request->get('duree');
        $type_id = $request->get('type');
        $dates = $request->get('dates');
        $statut	 = $request->get('statut');
        $mois	 = $request->get('num_mois');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);
        $type = $this->typeCongeRepository->findOneBy(['id' => $type_id]);


        $conge = new Conge();

        $date_deb = new \DateTime($date_debut);
        $date_f = new \DateTime($date_fin);
        $conge->setDateDebut($date_deb);
        $conge->setDateFin($date_f);
        $conge->setDuree($duree);
        $conge->setTypeConge($type);
        $conge->setDates($dates);
        $conge->setStatut("En Attente");
        $conge->setNumMois($mois);
        $conge->setEmploye($user);

        $this->entityManager->persist($conge);
        $this->entityManager->flush();
        $data = $this->congeRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['conge']));

    }

    /**
     * @param Request $request
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchCongeAction(Request $request, int $id)
    {

        $conge = $this->congeRepository->findOneBy(['id' => $id]);

        if ($conge) {

            $duree = $request->get('duree', $conge->getDuree());
            $type = $request->get('type',$conge->getTypeConge());
            $date_debut = $request->get('date_debut', $conge->getDateDebut());
            $date_fin = $request->get('date_fin', $conge->getDateFin());
            $statut	 = $request->get('statut', $conge->getStatut());
            $dates = $request->get('dates', $conge->getDates());
            $cause = $request->get('cause_refus', $conge->getTypeConge());

            $conge->setDateDebut($date_debut);
            $conge->setDateFin($date_fin);
            $conge->setDuree($duree);
            $conge->setTypeConge($type);
            $conge->setStatut($statut);
            $conge->setDates($dates);
            $conge->setCauseRefus($cause);

            $this->entityManager->persist($conge);
            $this->entityManager->flush();

            $data = $this->congeRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
