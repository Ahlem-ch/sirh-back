<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Repository\MissionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MissionController extends AbstractFOSRestController
{
    /**
     * @var MissionRepository
     */
    private $missionRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(MissionRepository $missionRepository, EntityManagerInterface $entityManager, UserRepository $userRepository){

        $this->missionRepository = $missionRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getMissionsAction()
    {

        $data = $this->missionRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['mission']));

    }

    public function getMissionAction(int $id)
    {
        $data = $this->missionRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['mission']));

    }

    public function deleteMissionAction(int $id)
    {
        $data = $this->missionRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $missions = $this->missionRepository->findAll();


        return $this->view($missions, Response::HTTP_OK)->setContext((new Context())->setGroups(['mission']));

    }

    public function postMissionAction(Request $request)
    {

        $journee = $request->get('journee');
        $demi_journee = $request->get('demi_journee');
        $client = $request->get('client');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);


        $mission = new Mission();
        $mission->setJournee($journee);
        $mission->setDemiJournee($demi_journee);
        $mission->setCreatedAt(new \DateTime());
        $mission->setClient($client);
        $mission->setUser($user);


        $this->entityManager->persist($mission);
        $this->entityManager->flush();
        $data = $this->missionRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['mission']));

    }

    public function patchMissionAction(Request $request, int $id)
    {

        $mission = $this->missionRepository->findOneBy(['id' => $id]);

        if ($mission) {

            $journee = $request->get('journee', $mission->getJournee());
            $demi_journee = $request->get('demi_journee', $mission->getDemiJournee());
            $client = $request->get('client', $mission->getClient());

            $mission->setJournee($journee);
            $mission->setDemiJournee($demi_journee);
            $mission->setClient($client);


            $this->entityManager->persist($mission);
            $this->entityManager->flush();

            $data = $this->missionRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['mission']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
