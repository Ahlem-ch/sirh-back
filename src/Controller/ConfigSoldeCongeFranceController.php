<?php

namespace App\Controller;

use App\Entity\ConfigSoldeCongeFrance;
use App\Repository\ConfigSoldeCongeFranceRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigSoldeCongeFranceController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigSoldeCongeFranceRepository
     */
    private $configSoldeCongeRepository;


    public function __construct(ConfigSoldeCongeFranceRepository $configSoldeCongeRepository, EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
        $this->configSoldeCongeRepository = $configSoldeCongeRepository;
    }

    public function getConfigFranceCongesAction()
    {

        $data = $this->configSoldeCongeRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge_france']));

    }

    public function getConfigFranceCongeAction(int $id)
    {
        $data = $this->configSoldeCongeRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge_france']));

    }

    public function deleteConfigFranceCongeAction(int $id)
    {
        $data = $this->configSoldeCongeRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $config_conges = $this->configSoldeCongeRepository->findAll();


        return $this->view($config_conges, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge_france']));

    }

    public function postConfigFranceCongeAction(Request $request)
    {

        $solde = $request->get('solde');

        $config_conge = new ConfigSoldeCongeFrance();
        $config_conge->setSolde($solde);

        $this->entityManager->persist($config_conge);
        $this->entityManager->flush();

        return $this->view($config_conge,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['config_conge_france']));

    }

    public function patchConfigFranceCongeAction(Request $request, int $id)
    {

        $config_conge = $this->configSoldeCongeRepository->findOneBy(['id' => $id]);

        if ($config_conge) {

            $solde = $request->get('solde', $config_conge->getSolde());

            $config_conge->setSolde($solde);

            $this->entityManager->persist($config_conge);
            $this->entityManager->flush();


            return $this->view($config_conge, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_conge_france']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
