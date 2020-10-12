<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Repository\DepenseRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;




class DepenseController extends AbstractFOSRestController
{
    /**
     * @var DepenseRepository
     */
    private $depenseRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DepenseController constructor.
     * @param DepenseRepository $depenseRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(DepenseRepository $depenseRepository, EntityManagerInterface $entityManager)
    {
        $this->depenseRepository = $depenseRepository;
        $this->entityManager = $entityManager;
    }

    public function getDepensesAction(){

        $data = $this->depenseRepository->findAll();
        return $this->view($data,Response::HTTP_OK);

    }



    public function getDepenseAction(int $id) {
        $data = $this->depenseRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK);

    }

    public function deleteDepenseAction(int $id) {
        $data = $this->depenseRepository->findOneBy(['id'=>$id]);

        if ($data){
            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $depenses = $this->depenseRepository->findAll();

            return $this->view($depenses,Response::HTTP_OK);
        }
        return $this->view(null,Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Route("/api/depense", name="addDepense")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function depense (Request $request, FileUploader $fileUploader) {

        $intitule = $request->get('intitule');
        $montant = $request->get('montant');
        $description = $request->get('description');
        $mode_paiement = $request->get('mode_paiement');
        $taxe = $request->get('taxe');
        $ttc = $request->get('ttc');
        $date = $request->get('date');
        $piece_jointe = $request->get('piece_jointe', null);


        $depense = new Depense();
        do {
            $random = random_int(1, 9999);
            $ref = 'fac' . $random;
            $array = $this->depenseRepository->findBy(['ref' => $ref] );
        } while ($array != null);

        if ($piece_jointe){
            $depense->setPieceJointe($piece_jointe);
        }

            //upload image
        $files = $fileUploader->upload($request);
        if (count($files) == 2 ) {
            $image = $files['piece_jointe'];
            $depense->setPieceJointe($image);
        }
        $depense->setRef($ref);
        $depense->setIntitule($intitule);
        $depense->setMontant($montant);
        $depense->setDescription($description);
        $depense->setModePaiement($mode_paiement);
        if($taxe && $taxe != "null") {
            $depense->setTaxe($taxe);
        }
        if($ttc && $ttc != "null") {
            $depense->setTtc($ttc);
        }
        $date_deb = new \DateTime($date);
        $depense->setDate($date_deb);


        $this->entityManager->persist($depense);
        $this->entityManager->flush();

        $depenses = $this->depenseRepository->findAll();

        return $this->view($depenses,  Response::HTTP_CREATED);

    }
    /**
    * @Route("/api/update/{id}/depense", name="updateDepense")
    * @param Request $request
    * @param FileUploader $fileUploader
    * @param int $id
    * @return \FOS\RestBundle\View\View
    * @throws \Exception
    */
    public function modifierDepense(Request $request, FileUploader $fileUploader, int $id)
    {

        $depense = $this->depenseRepository->findOneBy(['id' => $id]);

        $intitule = $request->get('intitule',$depense->getIntitule());
        $montant = $request->get('montant',$depense->getMontant());
        $description = $request->get('description',$depense->getDescription());
        $mode_paiement = $request->get('mode_paiement',$depense->getModePaiement());
        $taxe = $request->get('taxe',$depense->getTaxe());
        $ttc = $request->get('ttc',$depense->getTtc());
        $date = $request->get('date', $depense->getDate());


        if ($depense) {

            $depense->setIntitule($intitule);
            $depense->setMontant($montant);
            $depense->setDescription($description);
            $depense->setModePaiement($mode_paiement);
            if($taxe) {
                $depense->setTaxe($taxe);
            }
            if($ttc) {
                $depense->setTtc($ttc);
            }
            $date_deb = new \DateTime($date);
            $depense->setDate($date_deb);
            $files = $fileUploader->upload($request);
            if (count($files) == 2 ) {
                $image = $files['piece_jointe'];
                $depense->setPieceJointe($image);
            }

            $this->entityManager->persist($depense);
            $this->entityManager->flush();

            $depenses = $this->depenseRepository->findAll();

            return $this->view($depenses, Response::HTTP_OK);

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    public function postSommeDepensesAction(Request $request)
    {
        $min = $request->get('min', null);
        $max = $request->get('max', null);
        $data = $this->depenseRepository->sommeDepenses($min, $max);
        return $this->view($data, Response::HTTP_OK);

    }

    public function postFiltreDepensesAction(Request $request)
    {
        $min = $request->get('min', null);
        $max = $request->get('max', null);
        $data = $this->depenseRepository->filterDepenses($min, $max);
        return $this->view($data, Response::HTTP_OK);

    }

}
