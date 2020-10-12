<?php

namespace App\Controller;

use App\Entity\TypeConge;
use App\Repository\CongeRepository;
use App\Repository\TypeCongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TypeCongeController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TypeCongeRepository
     */
    private $typeCongeRepository;
    /**
     * @var CongeRepository
     */
    private $congeRepository;

    /**
     * TypeCongeController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TypeCongeRepository $typeCongeRepository
     * @param CongeRepository $congeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TypeCongeRepository $typeCongeRepository, CongeRepository $congeRepository)
    {
        $this->entityManager = $entityManager;
        $this->typeCongeRepository = $typeCongeRepository;
        $this->congeRepository = $congeRepository;
    }

    public function getTypesCongeAction(){

        $data = $this->typeCongeRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesConge']));

    }

    public function getTypeCongeAction(int $id) {
        $data = $this->typeCongeRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesConge']));

    }

    public function deleteTypeCongeAction(int $id) {
        $data = $this->typeCongeRepository->findOneBy(['id'=>$id]);
        $experience = $this->congeRepository->findOneBy(['typeConge'=>$id]);

        if($experience){
            $experience->setTypeConge(null);
        }
        $this->entityManager->remove($data);
        $this->entityManager->flush();

        $types = $this->typeCongeRepository->findAll();

        return $this->view($types,Response::HTTP_OK)->setContext((new Context())->setGroups(['typesConge']));

    }

    public function postTypesCongeAction (Request $request) {

        $libelle = $request->get('label');

        $type_conge = new TypeConge();
        $type_conge->setLabel($libelle);


        $this->entityManager->persist($type_conge);
        $this->entityManager->flush();

        $types = $this->typeCongeRepository->findAll();

        return $this->view($types,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['typesConge']));

    }


    public function patchTypeCongeAction(Request $request, int $id)
    {

        $type_conge = $this->typeCongeRepository->findOneBy(['id' => $id]);

        $libelle = $request->get('label', $type_conge->getLabel());

        if ($type_conge) {

            $type_conge->setLabel($libelle);

            $this->entityManager->persist($type_conge);
            $this->entityManager->flush();

            $types = $this->typeCongeRepository->findAll();


            return $this->view($types, Response::HTTP_OK)->setContext((new Context())->setGroups(['typesConge']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['typesConge']));
    }
}
