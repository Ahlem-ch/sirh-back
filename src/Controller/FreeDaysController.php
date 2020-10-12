<?php

namespace App\Controller;

use App\Entity\FreeDays;
use App\Repository\FreeDaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class FreeDaysController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FreeDaysRepository
     */
    private $freeDaysRepository;

    /**
     * FreeDaysController constructor.
     * @param EntityManagerInterface $entityManager
     * @param FreeDaysRepository $freeDaysRepository
     */
    public function __construct(EntityManagerInterface $entityManager, FreeDaysRepository $freeDaysRepository)
    {
        $this->entityManager = $entityManager;
        $this->freeDaysRepository = $freeDaysRepository;
    }

    public function getFreeDaysAction(){

        $data = $this->freeDaysRepository->findAll();
        return $this->view($data,Response::HTTP_OK);

    }

    public function getFreeDayAction(int $id) {
        $data = $this->freeDaysRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK);

    }


    public function deleteFreeDaysAction(int $id) {
        $data = $this->freeDaysRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $frees = $this->freeDaysRepository->findAll();

            return $this->view(   $frees, Response::HTTP_OK);
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postFreeDaysAction (Request $request) {

        $description = $request->get('description');
        $date = $request->get('date');
        $country = $request->get('country');


        $free = new FreeDays();

        $free->setDescription($description);
        $date_d = new \DateTime($date);
        $free->setDate($date_d);
        $free->setCountry($country);

        $this->entityManager->persist($free);
        $this->entityManager->flush();
        $frees = $this->freeDaysRepository->findAll();

        return $this->view($frees,  Response::HTTP_CREATED);

    }
    /**
     * @param Request $request
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchFreeDaysAction(Request $request, int $id)
    {

        $free = $this->freeDaysRepository->findOneBy(['id' => $id]);

        if ($free) {

            $description = $request->get('description', $free->getDescription());
            $date = $request->get('date', $free->getDate());
            $country = $request->get('country', $free->getCountry());


            $free->setDescription($description);
            $date_d = new \DateTime($date);
            $free->setDate($date_d);
            $free->setCountry($country);

            $this->entityManager->persist($free);
            $this->entityManager->flush();

            $frees = $this->freeDaysRepository->findAll();

            return $this->view($frees, Response::HTTP_OK);

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }


}
