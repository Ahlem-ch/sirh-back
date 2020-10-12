<?php

namespace App\Controller;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use App\Repository\TypeDocumentRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;


class DocumentController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;
    /**
     * @var TypeDocumentRepository
     */
    private $typeDocumentRepository;


    public function __construct(DocumentRepository $documentRepository,UserRepository $userRepository,
                                TypeDocumentRepository $typeDocumentRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->documentRepository = $documentRepository;
        $this->typeDocumentRepository = $typeDocumentRepository;
    }


    /**
     * List All Documents
     *
     * @OA\Get(
     *   path="/documents",
     *   summary="List all Documents",
     *   tags = {"Document"},
     *   operationId="indexDocument",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="documents_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Document")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     *
     */
    public function getDocumentsAction(){

        $data = $this->documentRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['document']));

    }

    /**
     * Show an existing Document
     *
     * @OA\Get(
     *   path="/documents/{id}",
     *   summary="Show an existing Document",
     *   tags = {"Document"},
     *   operationId="showDocument",
     *   @OA\Parameter(name="id", in="path", description="ID of Document to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="document_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Document")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     */
    public function getDocumentAction(int $id) {
        $data = $this->documentRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['document']));

    }


    /**
     * Delete an existing Document
     *
     * @OA\Delete(
     *   path="/documents/{id}",
     *   summary="Delete an existing Document",
     *   tags = {"Document"},
     *   operationId="destroyDocument",
     *   @OA\Parameter(name="id", in="path", description="ID of Document to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="document_deleted"),
     *       @OA\Property(property="data", @OA\Items()),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteDocumentAction(int $id) {
        $data = $this->documentRepository->findOneBy(['id'=>$id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $data= $this->documentRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['document']));

    }


    /**
     * Create a new Document
     *
     * @OA\Post(
     *   path="/documents",
     *   summary="Create a new Document",
     *   tags = {"Document"},
     *   operationId="storeDocument",
     *   requestBody={"$ref": "#/components/requestBodies/Document"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="Document_created"),
     *       @OA\Property(property="data",  @OA\Items(ref="#/components/schemas/Document")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     */

    /**
     * @Route("/api/document", name="addDocument")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function document (Request $request, FileUploader $fileUploader) {

        $libelleDocument = $request->get('libelle_document');
        $type_id = $request->get('type');
        $visibilte = $request->get('visibilite');
        $equipe = $request->get('equipe');
        $type = $this->typeDocumentRepository->findOneBy(['id' => $type_id]);
        $user_id = $request->get('user',null);


        //upload image
        $files = $fileUploader->upload($request);
        $image = $files['csv'];

        $document = new Document();
        do {
            $random = random_int(1, 9999);
            $ref = 'doc' . $random;
            $array = $this->documentRepository->findBy(['ref' => $ref] );
        } while ($array != null);
        $document->setRef($ref);
        $document->setLibelleDocument($libelleDocument);
        $document->setTypeDocument($type);
        $document->setVisibilite($visibilte);
        $document->setEquipe($equipe);
        $document->setImage($image);
        if ($user_id) {
            $user = $this->userRepository->findOneBy(['id' => $user_id]);
            $document->setUser($user);
        }

        $this->entityManager->persist($document);
        $this->entityManager->flush();
        $data= $this->documentRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['document']));

    }

    /**
     * Update an existing Document
     *
     * @OA\Patch(
     *   path="/documents/{id}",
     *   summary="Update an existing Document",
     *   tags = {"Document"},
     *   operationId="updateDocuments",
     *   requestBody={"$ref": "#/components/requestBodies/Document"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="document_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Document")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @param Request $request
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function patchDocumentAction(Request $request, int $id)
    {

        $document = $this->documentRepository->findOneBy(['id' => $id]);
        if ($document) {
            $libelleDocument = $request->get('libelle_document',$document->getLibelleDocument());
            $type_id = $request->get('type',$document->getTypeDocument());
            $visibilte = $request->get('visibilite', $document->getVisibilite());

            $type = $this->typeDocumentRepository->findOneBy(['id' => $type_id]);

            $document->setLibelleDocument($libelleDocument);
            $document->setTypeDocument($type);
            $document->setVisibilite($visibilte);

            $this->entityManager->persist($document);
            $this->entityManager->flush();
            $data= $this->documentRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['document']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['document']));
    }
}
