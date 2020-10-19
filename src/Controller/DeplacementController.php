<?php

namespace App\Controller;

use App\Entity\Deplacement;
use App\Repository\DeplacementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeplacementController extends  AbstractFOSRestController
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
     * @var DeplacementRepository
     */
    private $deplacementRepository;


    /**
     * DeplacementController constructor.
     * @param DeplacementRepository $deplacementRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(DeplacementRepository $deplacementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->deplacementRepository = $deplacementRepository;
    }

    public function getDeplacementsAction()
    {

        $data = $this->deplacementRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['deplacement']));

    }

    public function getDeplacementAction(int $id)
    {
        $data = $this->deplacementRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['deplacement']));

    }

    public function deleteDeplacementAction(int $id)
    {
        $data = $this->deplacementRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $notes = $this->deplacementRepository->findAll();


        return $this->view($notes, Response::HTTP_OK)->setContext((new Context())->setGroups(['deplacement']));

    }

    public function postDeplacementAction (Request $request, int $id) {

        $duree = $request->get('duree');
        $date = $request->get('date');
        $description = $request->get('description');
        $user = $this->userRepository->findOneBy(['id'=> $id]);

        $deplacement = new Deplacement();

        $deplacement->setDuree($duree);
        $deplacement->setDate(new \DateTime($date));
        $deplacement->setDescription($description);
        $deplacement->setUser($user);


        $this->entityManager->persist($deplacement);
        $this->entityManager->flush();

        $deplacements = $this->deplacementRepository->findAll();

        return $this->view($deplacements,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['deplacement']));

    }


    public function patchDeplacementAction(Request $request, int $id)
    {

        $deplacement = $this->deplacementRepository->findOneBy(['id' => $id]);

        if ($deplacement) {

            $duree = $request->get('duree', $deplacement->getDuree());
            $date = $request->get('date', $deplacement->getDate());
            $description = $request->get('description', $deplacement->getDescription());

            $deplacement->setDuree($duree);
            $deplacement->setDate($date);
            $deplacement->setDescription($description);

            $this->entityManager->persist($deplacement);
            $this->entityManager->flush();

            $deplacements = $this->deplacementRepository->findAll();


            return $this->view($deplacements, Response::HTTP_OK)->setContext((new Context())->setGroups(['deplacement']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['deplacement']));
    }

}
