<?php

namespace App\Controller;

use App\Entity\AugmentationContrat;
use App\Repository\AugmentationContratRepository;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AugmentationContratController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContratRepository
     */
    private $contratRepository;
    /**
     * @var AugmentationContratRepository
     */
    private $augmentationContratRepository;


    /**
     * AugmentationContratController constructor.
     * @param AugmentationContratRepository $augmentationContratRepository
     * @param ContratRepository $contratRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AugmentationContratRepository $augmentationContratRepository, ContratRepository $contratRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->contratRepository = $contratRepository;
        $this->augmentationContratRepository = $augmentationContratRepository;
    }


    public function getAugmentationsAction()
    {
        $data = $this->augmentationContratRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['augmentation']));

    }



    public function getAugmentationAction(int $id)
    {
        $data = $this->augmentationContratRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['augmentation']));

    }


    public function deleteAugmentationAction(int $id)
    {
        $data = $this->augmentationContratRepository->findOneBy(['id' => $id]);

        if ($data) {
            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $augmmentations = $this->augmentationContratRepository->findAll();

        }
        return $this->view($augmmentations, Response::HTTP_OK)->setContext((new Context())->setGroups(['augmentation']));

    }


    public function postAugmentationAction(Request $request)
    {

        $montant = $request->get('montant');
        $date = $request->get('date');
        $contrat_id= $request->get('contrat');
        $contrat = $this->contratRepository->findOneBy(['id'=> $contrat_id]);

        $augmentation = new AugmentationContrat();

        $augmentation->setContrat($montant);
        $augmentation->setDate(new \DateTime($date));
        $augmentation->setContrat($contrat);

        $this->entityManager->persist($augmentation);
        $this->entityManager->flush();

        $augmentations = $this->contratRepository->findAll();

        return $this->view($augmentations, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['augmentation']));

    }


    public function patchAugmentationAction(Request $request, int $id)
    {

        $augmentation = $this->augmentationContratRepository->findOneBy(['id'=> $id]);

        if ($augmentation) {

            $montant = $request->get('montant', $augmentation->getMontant());
            $date = $request->get('date', $augmentation->getDate());

            $augmentation->setMontant($montant);
            $augmentation->setDate(new \DateTime($date));

            $this->entityManager->persist($augmentation);
            $this->entityManager->flush();

            $augmentations = $this->contratRepository->findAll();

            return $this->view($augmentations, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['augmentation']));
        }

        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['augmentation']));

    }


}