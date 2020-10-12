<?php

namespace App\Controller;

use App\Entity\Pointage;
use App\Repository\DepartementRepository;
use App\Repository\PointageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class PointageController extends AbstractFOSRestController
{
    /**
     * @var PointageRepository
     */
    private $pointageRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var DepartementRepository
     */
    private $departementRepository;

    /**
     * PointageController constructor.
     * @param PointageRepository $pointageRepository
     * @param UserRepository $userRepository
     * @param DepartementRepository $departementRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PointageRepository $pointageRepository, UserRepository $userRepository,
                                DepartementRepository $departementRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->pointageRepository = $pointageRepository;
        $this->userRepository = $userRepository;
        $this->departementRepository = $departementRepository;
    }

    public function getPointagesAction()
    {

        $data = $this->pointageRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['pointage']));

    }


    public function getPointageAction(int $id)
    {
        $data = $this->pointageRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['pointage']));

    }


    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postPointagesAction(Request $request)
    {

        $pointages = json_decode($request->getContent(), true);

        foreach ((array)$pointages as $value) {

            $emp = $this->userRepository->findOneBy(['matricule_pointage' => $value['matricule']]);
            $dep = $this->departementRepository->findOneBy(['libelle_departement' => $value['departement']]);

            if($emp) {

                $pointage = new Pointage();

                $pointage->setCardRef($value['cardRef']);
                $pointage->setMachine($value['machine']);
                $pointage->setEtat($value['etat']);
                $var = $value['date'];
                $date = str_replace('/', '-', $var);
                $date2 = new \DateTime(date('Y-m-d', strtotime($date)));
                $pointage->setDate($date2);
                $pointage->setTime($value['time']);
                $pointage->setEmploye($emp);
                if ($dep) {
                    $pointage->setDpartement($dep);
                }
                $this->entityManager->persist($pointage);
                $this->entityManager->flush();

            }
        }


        $data = $this->pointageRepository->findAll();

        return $this->view($data, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['pointage']));

    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deletePointageAction(int $id){

        $data = $this->pointageRepository->findOneBy(['id' => $id]);

        if ($data) {
            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $pointages = $this->pointageRepository->findAll();

            return $this->view($pointages, Response::HTTP_OK)->setContext((new Context())->setGroups(['pointage']));

        }

        return $this->view(null, Response::HTTP_OK)->setContext((new Context())->setGroups(['pointage']));

    }


    /**
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchPointageAction(Request $request, int $id)
    {


        $pointage = $this->pointageRepository->findOneBy(['id' => $id]);

        if ($pointage) {

            $ref = $request->get('cardRef', $pointage->getCardRef());
            $machine = $request->get('machine', $pointage->getMachine());
            $time = $request->get('time', $pointage->getTime());
            $date = $request->get('date', $pointage->getDate());
            $departement_id = $request->get('dpartement', null);
            $departement = $this->departementRepository->findOneBy(['id' => $departement_id]);

            if ($departement) {
                $pointage->setDpartement($departement);
            }

            $pointage->setCardRef($ref);
            $pointage->setMachine($machine);
            $pointage->setTime($time);
            $date1 = new \DateTime($date);
            $pointage->setDate($date1);

            $this->entityManager->persist($pointage);
            $this->entityManager->flush();
            $pointages = $this->pointageRepository->findAll();

            return $this->view($pointages, Response::HTTP_OK)->setContext((new Context())->setGroups(['pointage']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['pointage']));
    }

}
