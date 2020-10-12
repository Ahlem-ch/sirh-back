<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormationController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var formationRepository
     */
    private $formationRepository;


    /**
     * FormationController constructor.
     * @param EntityManagerInterface $entityManager
     * @param formationRepository $formationRepository
     */
    public function __construct(EntityManagerInterface $entityManager, FormationRepository $formationRepository)
    {
        $this->entityManager = $entityManager;
        $this->formationRepository = $formationRepository;
    }

    public function getFormationsAction(){

        $data = $this->formationRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['Formation']));

    }

    public function getFormationAction(int $id) {
        $data = $this->formationRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['Formation']));

    }


    public function deleteFormationAction(int $id) {
        $data = $this->formationRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $Formations = $this->formationRepository->findAll();

            return $this->view(   $Formations, Response::HTTP_OK)->setContext((new Context())->setGroups(['Formation']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postFormationAction (Request $request) {


        do {
            $random = random_int(1, 9999);
            $ref = 'for' . $random;
            $array = $this->formationRepository->findBy(['ref' => $ref] );
        } while ($array != null);
        $description = $request->get('description');
        $lieu = $request->get('lieu');
        $formateur = $request->get('formateur');
        $date = $request->get('date');

        $Formation = new Formation();

        $datee = new \DateTime($date);
        $Formation->setDate($datee);
        $Formation->setRef($ref);
        $Formation->setDescription($description);
        $Formation->setLieu($lieu);
        $Formation->setFormateur($formateur);

        $this->entityManager->persist($Formation);
        $this->entityManager->flush();
        $data = $this->formationRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['Formation']));

    }

    /**
     * @param Request $request
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchFormationAction(Request $request, int $id)
    {

        $Formation = $this->formationRepository->findOneBy(['id' => $id]);

        if ($Formation) {

            $description = $request->get('description', $Formation->getDescription());
            $lieu = $request->get('lieu', $Formation->getLieu());
            $formateur = $request->get('formateur', $Formation->getFormateur());
            $date = $request->get('date', $Formation->getDate());

            $datee = new \DateTime($date);
            $Formation->setDate($datee);
            $Formation->setDescription($description);
            $Formation->setLieu($lieu);
            $Formation->setFormateur($formateur);

            $this->entityManager->persist($Formation);
            $this->entityManager->flush();

            $data = $this->formationRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['Formation']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
