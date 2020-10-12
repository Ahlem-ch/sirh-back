<?php

namespace App\Controller;

use App\Entity\AutorisationSortie;
use App\Repository\AutorisationSortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AutorisationSortieController extends AbstractFOSRestController
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
     * @var AutorisationSortieRepository
     */
    private $autorisationSortieRepository;

    public function __construct(AutorisationSortieRepository $autorisationSortieRepository, EntityManagerInterface $entityManager, UserRepository $userRepository){

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->autorisationSortieRepository = $autorisationSortieRepository;
    }

    public function getAutorisationsAction()
    {

        $data = $this->autorisationSortieRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['autorisation']));

    }

    public function getAutorisationAction(int $id)
    {
        $data = $this->autorisationSortieRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['autorisation']));

    }

    public function deleteAutorisationAction(int $id)
    {
        $data = $this->autorisationSortieRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $Autorisations = $this->autorisationSortieRepository->findAll();


        return $this->view($Autorisations, Response::HTTP_OK)->setContext((new Context())->setGroups(['autorisation']));

    }

    public function postAutorisationAction(Request $request)
    {

        $motif = $request->get('motif');
        $date = $request->get('date');
        $heure = $request->get('heure');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);


        $autorisation = new AutorisationSortie();
        $autorisation->setMotif($motif);
        $autorisation->setDate(new \DateTime($date));
        $autorisation->setHeure($heure);
        $autorisation->setStatut('En attente');
        $autorisation->setUser($user);


        $this->entityManager->persist($autorisation);
        $this->entityManager->flush();
        $data = $this->autorisationSortieRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['autorisation']));

    }

    public function patchAutorisationAction(Request $request, int $id)
    {

        $autorisation = $this->autorisationSortieRepository->findOneBy(['id' => $id]);

        if ($autorisation) {

            $statut = $request->get('statut', $autorisation->getStatut());
            $refus = $request->get('cause_refus', null);


            $autorisation->setStatut($statut);
            if($refus) {
                $autorisation->setCauseRefus($refus);
            }

            $this->entityManager->persist($autorisation);
            $this->entityManager->flush();

            $data = $this->autorisationSortieRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['autorisation']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
