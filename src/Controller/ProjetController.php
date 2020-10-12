<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Repository\ProjetRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjetController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProjetRepository
     */
    private $projetRepository;
    /**
     * @var JiraController
     */
    private $jiraController;


    /**
     * ProjetController constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProjetRepository $projetRepository
     * @param JiraController $jiraController
     */
    public function __construct(EntityManagerInterface $entityManager, ProjetRepository $projetRepository, JiraController $jiraController)
    {
        $this->entityManager = $entityManager;
        $this->projetRepository = $projetRepository;
        $this->jiraController = $jiraController;
    }

    public function getProjetsAction(){

        $data = $this->projetRepository->findAll();
        return $this->view($data,Response::HTTP_OK);

    }

    public function getProjetAction(int $id) {
        $data = $this->projetRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK);

    }


    public function updateBaseProjectAction() {


        $response = $this->jiraController->getProjetsAction();



        if ($response) {

            foreach ($response as $rep) {

                $id = $rep['id'];
                $key = $rep['key'];
                $libelle = $rep['name'];

                $projet = $this->projetRepository->findOneBy(['id' => $id]);

                if ($projet) {

                    $projet->setId($id);
                    $projet->setKeyProjet($key);
                    $projet->setLibelle($libelle);


                    $this->entityManager->persist($projet);
                    $this->entityManager->flush();

                } else {

                    $p = new Projet();

                    $p->setId(intval($id));
                    $p->setKeyProjet($key);
                    $p->setLibelle($libelle);

                    $this->entityManager->persist($p);
                    $this->entityManager->flush();
                }
            }

            $projets = $this->projetRepository->findAll();

            return $this->view($projets, Response::HTTP_CREATED);
        }


    }

    public function deleteProjetAction(int $id) {
        $data = $this->projetRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $frees = $this->projetRepository->findAll();

            return $this->view(   $frees, Response::HTTP_OK);
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    public function patchProjetAction(Request $request, int $id)
    {

        $projet = $this->projetRepository->findOneBy(['id' => $id]);

        if ($projet) {

            $date_debut = $request->get('date_debut');
            $date_fin = $request->get('date_fin');
            $status = $request->get('status', $projet->getStatus());

            if ($date_debut){
                $dateDeb = new DateTime($date_debut);
                $projet->setDateDebut($dateDeb);

            }
            if ($date_fin){
                $dateFin = new DateTime($date_fin);
                $projet->setDateFin($dateFin);

            }
            if ($status){
                $projet->setStatus($status);
            }


            $this->entityManager->persist($projet);
            $this->entityManager->flush();

            $data = $this->projetRepository->findAll();

            return $this->view($data, Response::HTTP_OK);

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }


}