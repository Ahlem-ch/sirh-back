<?php

namespace App\Controller;

use App\Entity\ConfigSoldeConge;
use App\Repository\ConfigSoldeCongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigSoldeCongeController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigSoldeCongeRepository
     */
    private $configSoldeCongeRepository;


    public function __construct(ConfigSoldeCongeRepository $configSoldeCongeRepository, EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
        $this->configSoldeCongeRepository = $configSoldeCongeRepository;
    }

    public function getConfigCongesAction()
    {

        $data = $this->configSoldeCongeRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge']));

    }

    public function getConfigCongeAction(int $id)
    {
        $data = $this->configSoldeCongeRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge']));

    }

    public function deleteConfigCongeAction(int $id)
    {
        $data = $this->configSoldeCongeRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $config_conges = $this->configSoldeCongeRepository->findAll();


        return $this->view($config_conges, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge']));

    }

    public function postConfigCongeAction(Request $request)
    {

        $solde = $request->get('solde');

        $config_conge = new ConfigSoldeConge();
        $config_conge->setSolde($solde);

        $this->entityManager->persist($config_conge);
        $this->entityManager->flush();

        return $this->view($config_conge,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['config_conge']));

    }

    public function patchConfigCongeAction(Request $request, int $id)
    {

        $config_conge = $this->configSoldeCongeRepository->findOneBy(['id' => $id]);

        if ($config_conge) {

            $solde = $request->get('solde', $config_conge->getSolde());

            $config_conge->setSolde($solde);

            $this->entityManager->persist($config_conge);
            $this->entityManager->flush();


            return $this->view($config_conge, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
