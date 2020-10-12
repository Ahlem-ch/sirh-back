<?php

namespace App\Controller;

use App\Entity\TypeDocument;
use App\Repository\DocumentRepository;
use App\Repository\TypeDocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TypeDocumentController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TypeDocumentRepository
     */
    private $typeDocumentRepository;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;


    /**
     * TypeCongeController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TypeDocumentRepository $typeDocumentRepository
     * @param DocumentRepository $documentRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TypeDocumentRepository $typeDocumentRepository, DocumentRepository $documentRepository)
    {
        $this->entityManager = $entityManager;
        $this->typeDocumentRepository = $typeDocumentRepository;
        $this->documentRepository = $documentRepository;
    }

    public function getTypesDocumentsAction(){

        $data = $this->typeDocumentRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesDocument']));

    }

    public function getTypesDocumentAction(int $id) {
        $data = $this->typeDocumentRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesDocument']));

    }

    public function deleteTypesDocumentAction(int $id) {
        $data = $this->typeDocumentRepository->findOneBy(['id'=>$id]);
        $document = $this->documentRepository->findOneBy(['typeDocument'=>$id]);

        if($document){
            $document->setTypeDocument(null);
        }
        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $types = $this->typeDocumentRepository->findAll();

        return $this->view($types,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesDocument']));

    }

    public function postTypesDocumentAction (Request $request) {

        $libelle = $request->get('libelle');

        $type_document = new TypeDocument();
        $type_document->setLibelle($libelle);


        $this->entityManager->persist($type_document);
        $this->entityManager->flush();

        $types = $this->typeDocumentRepository->findAll();

        return $this->view($types,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['typesDocument']));

    }


    public function patchTypesDocumentAction(Request $request, int $id)
    {

        $type_document = $this->typeDocumentRepository->findOneBy(['id' => $id]);

        $libelle = $request->get('libelle', $type_document->getLibelle());

        if ($type_document) {

            $type_document->setLibelle($libelle);

            $this->entityManager->persist($type_document);
            $this->entityManager->flush();

            $types = $this->typeDocumentRepository->findAll();


            return $this->view($types, Response::HTTP_OK)->setContext((new Context())->setGroups(['typesDocument']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['typesDocument']));
    }
}
