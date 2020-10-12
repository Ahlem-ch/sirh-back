<?php

namespace App\Controller;

use App\Entity\DemandeDocument;
use App\Repository\DemandeDocumentRepository;
use App\Repository\TypeDocumentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DemandeDocumentController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DemandeDocumentRepository
     */
    private $demandeDocumentRepository;
    /**
     * @var TypeDocumentRepository
     */
    private $typeDocumentRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CongeController constructor.
     * @param EntityManagerInterface $entityManager
     * @param DemandeDocumentRepository $demandeDocumentRepository
     * @param TypeDocumentRepository $typeDocumentRepository
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, DemandeDocumentRepository $demandeDocumentRepository,
                                TypeDocumentRepository $typeDocumentRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->demandeDocumentRepository = $demandeDocumentRepository;
        $this->typeDocumentRepository = $typeDocumentRepository;
        $this->userRepository = $userRepository;
    }

    public function getDemandeDocumentsAction(){

        $data = $this->demandeDocumentRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['demande_document']));

    }

    public function getDemandeDocumentAction(int $id) {
        $data = $this->demandeDocumentRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['demande_document']));

    }


    public function deleteDemandeDocumentAction(int $id) {
        $data = $this->demandeDocumentRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $conges = $this->demandeDocumentRepository->findAll();

            return $this->view(   $conges, Response::HTTP_OK)->setContext((new Context())->setGroups(['demande_document']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postDemandeDocumentAction (Request $request) {

        $description = $request->get('description');
        $type_id = $request->get('type');
        $type = $this->typeDocumentRepository->findOneBy(['id' => $type_id]);
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);


        $demande = new DemandeDocument();

        $demande->setDescription($description);
        $demande->setType($type);
        $demande->setStatut("En attente");
        $demande->setCreatedAt(new \DateTime());
        $demande->setUser($user);

        $this->entityManager->persist($demande);
        $this->entityManager->flush();
        $data = $this->demandeDocumentRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['demande_document']));

    }

    /**
     * @param Request $request
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchDemandeDocumentAction(Request $request, int $id)
    {

        $document = $this->demandeDocumentRepository->findOneBy(['id' => $id]);

        if ($document) {

            $description = $request->get('description', $document->getDescription());
            $statut	 = $request->get('statut', $document->getStatut());
            $type_id = $request->get('type', null);
            if ($type_id) {
                $type = $this->typeDocumentRepository->findOneBy(['id' => $type_id]);
                $document->setType($type);
            }

            $document->setDescription($description);
            $document->setStatut($statut);

            $this->entityManager->persist($document);
            $this->entityManager->flush();

            $data = $this->demandeDocumentRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['demande_document']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
