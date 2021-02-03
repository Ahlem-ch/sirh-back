<?php

namespace App\Controller;

use App\Repository\CongeRepository;
use App\Repository\ContratRepository;
use App\Repository\DepartementRepository;
use App\Repository\PosteRepository;
use App\Repository\TechnologieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;


class StatistiqueController extends AbstractFOSRestController
{

    /**
     * @var ContratRepository
     */
    private $contratRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var DepartementRepository
     */
    private $departementRepository;
    /**
     * @var PosteRepository
     */
    private $posteRepository;
    /**
     * @var CongeRepository
     */
    private $congeRepository;
    /**
     * @var TechnologieRepository
     */
    private $technologieRepository;

    /**
     * StatistiqueController constructor.
     * @param ContratRepository $contratRepository
     * @param UserRepository $userRepository
     * @param DepartementRepository $departementRepository
     * @param PosteRepository $posteRepository
     * @param CongeRepository $congeRepository
     * @param TechnologieRepository $technologieRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContratRepository $contratRepository, UserRepository $userRepository, DepartementRepository $departementRepository,
                                PosteRepository $posteRepository, CongeRepository $congeRepository, TechnologieRepository $technologieRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->contratRepository = $contratRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->departementRepository = $departementRepository;
        $this->posteRepository = $posteRepository;
        $this->congeRepository = $congeRepository;
        $this->technologieRepository = $technologieRepository;
    }

    public function getSalaireStatAction()
    {
        $dataTunisie = (int)$this->contratRepository->sumSalaireTunisie();
        $dataFrance = (int)$this->contratRepository->sumSalaireFrance();
        $avgTunisie = (int)$this->contratRepository->avgSalaireTunise();
        $avgFrance = (int)$this->contratRepository->avgSalaireFrance();
        return $this->view(['tunisie' => $dataTunisie , 'france' => $dataFrance , 'moyenne_tunisie' => $avgTunisie , 'moyenne_france' => $avgFrance], Response::HTTP_OK);

    }

    public function getEtatCivileAction()
    {
        $celibatire = (int)$this->userRepository->countCelibataire();
        $marie = (int)$this->userRepository->countMarie();
        $total = (int)$this->userRepository->countEmployes();
        return $this->view(['totale' => $total, 'celibataire' => $celibatire , 'marie' => $marie ], Response::HTTP_OK);

    }

    public function getSexeAction()
    {
        $femme = (int)$this->userRepository->countFemme();
        $homme = (int)$this->userRepository->countHomme();
        $total = (int)$this->userRepository->countEmployes();
        return $this->view(['totale' => $total, 'hommes' => $homme , 'femmes' => $femme ], Response::HTTP_OK);

    }


    public function getMoyenneAgeAction()
    {
        $list = [];
        $list_naissances = $this->userRepository->listDatesNaissance();
        foreach ($list_naissances as $item)
        {

            $newDate = new \DateTime() ;
            $date_now = $newDate->format('Y');
            $date_naissanse = $item['date_naissance']->format('Y');
            $result = (int)$date_now - (int)$date_naissanse;
            array_push($list, $result );
        }

        $somme_notes = 0;
        $i = 0;
        foreach($list as $cle=>$valeur)
        {
            $i++;
            $somme_notes+=$valeur;
        }
        $moyenne = $somme_notes / $i;
        return $this->view(['moyenne_age' => (int)$moyenne], Response::HTTP_OK);

    }

    public function getTypeContratAction()
    {
        $cdi = (int) $this->contratRepository->countContratType('CDI');
        $cdd =  (int) $this->contratRepository->countContratType('CDD');
        $stage =(int) $this->contratRepository->countContratType('Stage');
        $sivp = (int) $this->contratRepository->countContratType('SIVP');
        return $this->view(['cdi' => $cdi, 'cdd' => $cdd , 'stage' => $stage, 'sivp' => $sivp], Response::HTTP_OK);

    }

    public function getEmployesDepartementAction()
    {
        $list = $this->departementRepository->listDepartement();
        $list2 = [];
        foreach ($list as $item) {
            array_push($list2, $item['libelle_departement']);
        }
        $list3 = [];
        foreach ($list2 as $key) {
            $val = (int) $this->userRepository->listDepartement($key);
            array_push($list3,['libelle' => $key ,'count' => $val]);
        }
        return $this->view($list3, Response::HTTP_OK);

    }

    public function getEmployesPosteAction()
    {
        $list = $this->posteRepository->listPoste();
        $list2 = [];
        foreach ($list as $item) {
            array_push($list2, $item['libelle_poste']);
        }
        $list3 = [];
        foreach ($list2 as $key) {
            $val = (int) $this->userRepository->listPostes($key);
            array_push($list3,['libelle' => $key ,'count' => $val]);
        }
        return $this->view($list3, Response::HTTP_OK);

    }

    public function getEmployesTechnologiesAction()
    {
        $list = $this->technologieRepository->listeTechnologie();
        $list2 = [];
        foreach ($list as $item) {
            array_push($list2, $item['libelle']);
        }
        $list3 = [];
        foreach ($list2 as $key) {
            $val = (int) $this->userRepository->listeTechnologies($key);
            array_push($list3,['libelle' => $key ,'count' => $val]);
        }

        return $this->view($list3, Response::HTTP_OK);

    }

    public function getStatCongeAction()
    {
        $mois = [ 1,2,3,4,5,6,7,8,9,10,11,12];
        $list = [];
        foreach ($mois as $key){
            $val =  $this->congeRepository->countDureeConge(1,1,$key);
            array_push($list, ['conge' => $val[0]] );
        }
            $list2 = [];
        foreach ($mois as $key){
            $val =  $this->congeRepository->countDureeConge(1,3,$key);
            array_push($list2, ['conge' => $val[0]] );
        }
        $list3 = [];
        foreach ($mois as $key){
            $val =  $this->congeRepository->countDureeConge(3,5,$key);
            array_push($list3, ['conge' => $val[0]] );
        }
        $list4 = [];
        foreach ($mois as $key){
            $val =  $this->congeRepository->countDureeConge(5,50,$key);
            array_push($list4,  ['conge' => $val[0]] );
        }
        return $this->view([['duree1' =>$list,'duree2' =>$list2,'duree3' =>$list3,'duree4' =>$list4]], Response::HTTP_OK);

    }

}
