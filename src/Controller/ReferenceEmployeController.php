<?php

namespace App\Controller;

use App\Entity\ReferenceEmp;
use App\Repository\ReferenceEmpRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReferenceEmployeController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ReferenceEmpRepository
     */
    private $referenceEmpRepository;


    /**
     * @param EntityManagerInterface $entityManager
     * @param ReferenceEmpRepository $referenceEmpRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ReferenceEmpRepository $referenceEmpRepository)
    {
        $this->entityManager = $entityManager;
        $this->referenceEmpRepository = $referenceEmpRepository;
    }

    public function getReferencesAction(){

        $data = $this->referenceEmpRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['reference']));

    }

    public function getReferenceAction(int $id) {
        $data = $this->referenceEmpRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['reference']));

    }


    public function deleteReferenceAction(int $id) {
        $data = $this->referenceEmpRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $Formations = $this->referenceEmpRepository->findAll();

            return $this->view(   $Formations, Response::HTTP_OK)->setContext((new Context())->setGroups(['reference']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postReferenceAction (Request $request) {

        $libelle = $request->get('libelle');
        $value_min = $request->get('value_min');
        $value_max = $request->get('value_max');

        $reference = new ReferenceEmp();

        $reference->setLibelle($libelle);
        $reference->setValueMin($value_min);
        $reference->setValueMax($value_max);

        $this->entityManager->persist($reference);
        $this->entityManager->flush();

        return $this->view($reference,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['reference']));

    }

    /**
     * @param Request $request
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchReferenceAction(Request $request, int $id)
    {

        $reference = $this->referenceEmpRepository->findOneBy(['id' => $id]);

        if ($reference) {

            $libelle = $request->get('libelle', $reference->getLibelle());
            $value_min = $request->get('value_min', $reference->getValueMin());
            $value_max = $request->get('value_max', $reference->getValueMax());

            $reference->setLibelle($libelle);
            $reference->setValueMin($value_min);
            $reference->setValueMax($value_max);


            $this->entityManager->persist($reference);
            $this->entityManager->flush();


            return $this->view($reference, Response::HTTP_OK)->setContext((new Context())->setGroups(['reference']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
