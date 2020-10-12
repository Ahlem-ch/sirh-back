<?php

namespace App\Controller;

use App\Entity\Technologie;
use App\Repository\TechnologieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TechnologieController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TechnologieRepository
     */
    private $technologieRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * TechnologieController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TechnologieRepository $technologieRepository
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TechnologieRepository $technologieRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->technologieRepository = $technologieRepository;
        $this->userRepository = $userRepository;
    }

    public function getTechnologiesAction(){

        $data = $this->technologieRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['technologie']));

    }

    public function getTechnologieAction(int $id) {
        $data = $this->technologieRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['technologie']));

    }

    public function deleteTechnologieAction(int $id) {
        $data = $this->technologieRepository->findOneBy(['id'=>$id]);
        if ( $data) {
            $this->entityManager->remove($data);
            $this->entityManager->flush();

        }

        $technologies = $this->technologieRepository->findAll();

        return $this->view($technologies, Response::HTTP_OK)->setContext((new Context())->setGroups(['technologie']));

    }

    public function postTechnologieAction (Request $request) {

        $libelle = $request->get('libelle');

        $technologie = new Technologie();
        $technologie->setLibelle($libelle);


        $this->entityManager->persist($technologie);
        $this->entityManager->flush();

        $technologies = $this->technologieRepository->findAll();

        return $this->view($technologies,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['technologie']));

    }


    public function patchTechnologieAction(Request $request, int $id)
    {

        $technologie = $this->technologieRepository->findOneBy(['id' => $id]);

        $libelle = $request->get('libelle', $technologie->getLibelle());

        if ($technologie) {

            $technologie->setLibelle($libelle);

            $this->entityManager->persist($technologie);
            $this->entityManager->flush();

            $technologies = $this->technologieRepository->findAll();


            return $this->view($technologies, Response::HTTP_OK)->setContext((new Context())->setGroups(['technologie']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['technologie']));
    }

}
