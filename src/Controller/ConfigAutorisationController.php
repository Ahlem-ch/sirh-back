<?php

namespace App\Controller;

use App\Entity\ConfigAutorisation;
use App\Repository\ConfigAutorisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigAutorisationController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ConfigAutorisationRepository
     */
    private $configAutorisationRepository;


    public function __construct(ConfigAutorisationRepository $configAutorisationRepository, EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
        $this->configAutorisationRepository = $configAutorisationRepository;
    }

    public function getConfigAutorisationsAction()
    {

        $data = $this->configAutorisationRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_autorisation']));

    }

    public function getConfigAutorisationAction(int $id)
    {
        $data = $this->configAutorisationRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_autorisation']));

    }

    public function deleteConfigAutorisationAction(int $id)
    {
        $data = $this->configAutorisationRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $config_autorsiations = $this->configAutorisationRepository->findAll();


        return $this->view($config_autorsiations, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_autorisation']));

    }

    public function postConfigAutorisationAction(Request $request)
    {

        $nb_autorisation = $request->get('nb_autorisation');
        $duree = $request->get('duree');


        $config_autorsiation = new ConfigAutorisation();

        $config_autorsiation->setNbAutorisation($nb_autorisation);
        $config_autorsiation->setDuree($duree);


        $this->entityManager->persist($config_autorsiation);
        $this->entityManager->flush();

        return $this->view($config_autorsiation,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['config_autorisation']));

    }

    public function patchConfigAutorisationAction(Request $request, int $id)
    {

        $config_autorsiation = $this->configAutorisationRepository->findOneBy(['id' => $id]);

        if ($config_autorsiation) {

            $nb_autorisation = $request->get('nb_autorisation', $config_autorsiation->getNbAutorisation());
            $duree = $request->get('duree',$config_autorsiation->getDuree());

            $config_autorsiation->setNbAutorisation($nb_autorisation);
            $config_autorsiation->setDuree($duree);

            $this->entityManager->persist($config_autorsiation);
            $this->entityManager->flush();


            return $this->view($config_autorsiation, Response::HTTP_OK)->setContext((new Context())->setGroups(['config_autorisation']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
