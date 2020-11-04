<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Repository\ProjetRepository;
use App\Repository\RessourceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class JiraController extends AbstractFOSRestController
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
     * @var RessourceRepository
     */
    private $ressourceRepository;


    /**
     * JiraController constructor.
     * @param EntityManagerInterface $entityManager
     * @param ProjetRepository $projetRepository
     * @param RessourceRepository $ressourceRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ProjetRepository $projetRepository, RessourceRepository $ressourceRepository)
    {

        $this->entityManager = $entityManager;
        $this->projetRepository = $projetRepository;
        $this->ressourceRepository = $ressourceRepository;
    }



    /**
     * @Rest\Route("/api/projets/base", name="showProjetsFromBase")
     */
    public function getRessourceFromBaseAction(){
        $datas = $this->ressourceRepository->findAll();
        $arraytest=[];
        $now = new DateTime();
        foreach($datas as $key=>$data){
            if($data->getStatusProject() === 'clôturé') {
                $progressBar = 100;
                $arraytest[]=["id"=>$data->getId(),
                    "accountId"=>$data->getAccountId(),
                    "project"=>$data->getProject(),
                    "issue"=>$data->getIssue(),
                    "summary"=>$data->getSummary(),
                    "status"=>$data->getStatus(),
                    "name"=>$data->getName(),
                    "dateStart"=>$data->getDateStart(),
                    "dateEnd"=>$data->getDateEnd(),
                    "dateStartProject"=>$data->getDateStartProject(),
                    "dateEndProject"=>$data->getDateEndProject(),
                    "status_project"=>$data->getStatusProject(),
                    "progressBar"=> $progressBar];
                $datas[$key]=$data;
            } else if ($data->getDateStartProject() !== null  && $data->getDateEndProject() !== null) {
                $origin = $data->getDateStartProject();
                $target = $data->getDateEndProject();
                $interval = $origin->diff($target);
                $interval2 = $origin->diff($now);
                $val1 =$interval->format('%R%a') * 24;
                $val2 = $interval2->format('%R%a') * 24;

                if($val2>$val1){
                    $progressBar = 100;
                } else {
                    $progressBar = ($val2 * 100) / $val1;
                }
                $arraytest[]=["id"=>$data->getId(),
                    "accountId"=>$data->getAccountId(),
                    "project"=>$data->getProject(),
                    "issue"=>$data->getIssue(),
                    "summary"=>$data->getSummary(),
                    "status"=>$data->getStatus(),
                    "name"=>$data->getName(),
                    "dateStart"=>$data->getDateStart(),
                    "dateEnd"=>$data->getDateEnd(),
                    "dateStartProject"=>$data->getDateStartProject(),
                    "dateEndProject"=>$data->getDateEndProject(),
                    "status_project"=>$data->getStatusProject(),
                    "progressBar"=> $progressBar];
                $datas[$key]=$data;
            } else {
                $arraytest[]=["id"=>$data->getId(),
                    "accountId"=>$data->getAccountId(),
                    "project"=>$data->getProject(),
                    "issue"=>$data->getIssue(),
                    "summary"=>$data->getSummary(),
                    "status"=>$data->getStatus(),
                    "name"=>$data->getName(),
                    "dateStart"=>$data->getDateStart(),
                    "dateEnd"=>$data->getDateEnd(),
                    "dateStartProject"=>$data->getDateStartProject(),
                    "dateEndProject"=>$data->getDateEndProject(),
                    "status_project"=>$data->getStatusProject(),
                    "progressBar"=> 100];
                $datas[$key]=$data;
            }
        }


        return $this->view($arraytest,Response::HTTP_OK);
    }
    /**
     * @Rest\Route("/api/projets/jira", name="showProjets")
     */
    public function getProjetsAction(){


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://agenceinspire.atlassian.net/rest/api/2/project",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic bWFoZGkuem5haWRpQGFnZW5jZS1pbnNwaXJlLmNvbTpVamE4N09Pa2tnRTlCVHluR3ZNRDBFNDY=",
                "Cookie: atlassian.xsrf.token=3148cef8-ac6a-4f78-9215-3bf6d6d1e11a_606004c37432dc7f54aa094b550faa78eae89933_lin"
            ),
        ));

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);

        curl_close($curl);


        return json_decode($response, true);

    }

    /**
     * @Rest\Route("/api/projets/filtres", name="showProjetsFiltres")
     */
    public function getProjetsFiltresAction(){

        $listTickets = [];
        $listSousTickets = [];

        $listProjets = $this->getProjetsAction();


        foreach ($listProjets as $p) {

            $curl2 = curl_init();

            curl_setopt_array($curl2, array(
                CURLOPT_URL => "https://agenceinspire.atlassian.net/rest/api/2/search?jql=project=".$p['key'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic bWFoZGkuem5haWRpQGFnZW5jZS1pbnNwaXJlLmNvbTpVamE4N09Pa2tnRTlCVHluR3ZNRDBFNDY=",
                    "Cookie: atlassian.xsrf.token=3148cef8-ac6a-4f78-9215-3bf6d6d1e11a_606004c37432dc7f54aa094b550faa78eae89933_lin"
                ),
            ));
            curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, 0);

            $response2 = curl_exec($curl2);

            curl_close($curl2);

            $data2 = json_decode($response2, true);
            if (count($data2)>2){
                array_push($listTickets, $data2['issues']);

            }

        }


        foreach ($listTickets as $ticket) {
            foreach ($ticket as $t) {
                array_push($listSousTickets, $t['key']);
            }
        }


        return  $this->getRessourcesAction($listSousTickets);


    }


    /**
     * @Rest\Route("/api/noms/ressources", name="showIssues")
     * @param array $list
     * @return mixed
     */
    public function getRessourcesAction(array $list){

        $list2= [];

        foreach ($list as $l) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://agenceinspire.atlassian.net/rest/api/2/issue/".$l."?expand=changlog",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic bWFoZGkuem5haWRpQGFnZW5jZS1pbnNwaXJlLmNvbTpVamE4N09Pa2tnRTlCVHluR3ZNRDBFNDY=",
                    "Cookie: atlassian.xsrf.token=3148cef8-ac6a-4f78-9215-3bf6d6d1e11a_8016ef48ea2caf85c45d33f51bb778c20482da02_lin"
                ),
            ));

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            $response = curl_exec($curl);

            curl_close($curl);

            $data = json_decode($response, true);

            if($data['fields']['status']['name']==='En cours') {

                array_push($list2, ['project' => $data['fields']['project']['key'],
                    'issue' => $data['key'],
                    'summary' => $data['fields']['summary'],
                    'date_start' => $data['fields']['customfield_10015'],
                    'date_end' => $data['fields']['duedate'],
                    'status' => $data['fields']['status']['name'],
                    'name' => $data['fields']['assignee']['displayName'],
                    'account_id' => $data['fields']['assignee']['accountId']
                ]);
            }
        }


        $statitique = $this->projetRepository->FindAllArray();
        $list3 = [];
        foreach ($list2 as $l){
            $key = array_search($l['project'], array_column($statitique, 'key_projet'));
            if( false !== $key){
                array_push($list3, ['project' => $l['project'],
                    'issue' => $l['issue'],
                    'summary' => $l['summary'],
                    'date_start' => $l['date_start'],
                    'date_end' => $l['date_end'],
                    'status' => $l['status'],
                    'name' => $l['name'],
                    'account_id' => $l['account_id'],
                    'date_start_project' => $statitique[$key]['date_debut'],
                    'date_end_project' => $statitique[$key]['date_fin'],
                    'status_project' => $statitique[$key]['status']
                ]);
            } else {
                array_push($list3, ['project' => $l['project'],
                    'issue' => $l['issue'],
                    'summary' => $l['summary'],
                    'date_start' => $l['date_start'],
                    'date_end' => $l['date_end'],
                    'status' => $l['status'],
                    'name' => $l['name'],
                    'account_id' => $l['account_id'],
                ]);
            }

        }

        return  $list3;

    }


    /**
     * @Rest\Route("/tickets/{issue}", name="showIssues")
     * @param string $issue
     * @return mixed
     */
    public function getIssuesAction(string $issue){

        $list =[];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://agenceinspire.atlassian.net/rest/api/2/search?jql=project=".$issue,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic bWFoZGkuem5haWRpQGFnZW5jZS1pbnNwaXJlLmNvbTpVamE4N09Pa2tnRTlCVHluR3ZNRDBFNDY=",
                "Cookie: atlassian.xsrf.token=3148cef8-ac6a-4f78-9215-3bf6d6d1e11a_606004c37432dc7f54aa094b550faa78eae89933_lin"
            ),
        ));

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        if (count($data)>2){
            array_push($list,$data['issues']);
        }


        return $list;

    }

    /**
     * @Rest\Route("/api/ajout/ressources", name="ajoutRessources")
     * @return mixed
     * @throws \Exception
     */
    public function updateRessources()
    {

        $old_response = $this->ressourceRepository->findAll();

        foreach ($old_response as $old) {
            $ress = $this->ressourceRepository->findOneBy(['id' => $old->getId()]);

            $this->entityManager->remove($ress);
            $this->entityManager->flush();
        }

        $response = $this->getProjetsFiltresAction();

        if ($response) {

            foreach ($response as $rep) {
                $project = $rep['project'];
                $issue = $rep['issue'];
                $summary = $rep['summary'];
                $name = $rep['name'];
                $status = $rep['status'];
                $account_id = $rep['account_id'];
                $date_start = $rep['date_start'];
                $date_end = $rep['date_end'];
                $date_start_project = $rep['date_start_project'];
                $date_end_project = $rep['date_end_project'];
                $status_project = $rep['status_project'];


                $ressource = new Ressource();

                $ressource->setProject($project);
                $ressource->setIssue($issue);
                $ressource->setSummary($summary);
                $ressource->setStatus($status);
                $ressource->setAccountId($account_id);
                $ressource->setName($name);
                if ($date_start) {
                    $dateDeb = new DateTime($date_start);
                    $ressource->setDateStart($dateDeb);
                }
                if ($date_end) {
                    $dateEnd = new DateTime($date_end);
                    $ressource->setDateEnd($dateEnd);
                }
                if ($date_start_project) {
                    $ressource->setDateStartProject($date_start_project);
                }
                if ($date_end_project) {
                    $ressource->setDateEndProject($date_end_project);
                }
                if ($status_project) {
                    $ressource->setStatusProject($status_project);
                }

                $this->entityManager->persist($ressource);
                $this->entityManager->flush();
            }

            $ressources = $this->ressourceRepository->findAll();

            return $this->view($ressources, Response::HTTP_CREATED);
        }
    }


    /**
     * @Rest\Route("/api/issues/sendMail", name="sendMailIssues")
     */
    public function getIssueToSendAction(){

        $listTickets = [];
        $listSousTickets = [];

        $listProjets = $this->getProjetsAction();


        foreach ($listProjets as $p) {

            $curl2 = curl_init();

            curl_setopt_array($curl2, array(
                CURLOPT_URL => "https://agenceinspire.atlassian.net/rest/api/2/search?jql=project=".$p['key'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic bWFoZGkuem5haWRpQGFnZW5jZS1pbnNwaXJlLmNvbTpVamE4N09Pa2tnRTlCVHluR3ZNRDBFNDY=",
                    "Cookie: atlassian.xsrf.token=3148cef8-ac6a-4f78-9215-3bf6d6d1e11a_606004c37432dc7f54aa094b550faa78eae89933_lin"
                ),
            ));
            curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, 0);

            $response2 = curl_exec($curl2);

            curl_close($curl2);

            $data2 = json_decode($response2, true);
            if (count($data2)>2){
                array_push($listTickets, $data2['issues']);

            }

        }


        foreach ($listTickets as $ticket) {
            foreach ($ticket as $t) {
                array_push($listSousTickets, $t['key']);
            }
        }
        return  $this->sendMailIssueAction($listSousTickets);


    }

    /**
     * @Rest\Route("/api/send-mail/issues", name="sendMailIssue")
     * @param array $list
     * @return mixed
     * @throws \Exception
     */
    public function sendMailIssueAction(array $list){

        $listTest = [];

        foreach ($list as $l) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://agenceinspire.atlassian.net/rest/api/2/issue/".$l."?expand=changlog",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic bWFoZGkuem5haWRpQGFnZW5jZS1pbnNwaXJlLmNvbTpVamE4N09Pa2tnRTlCVHluR3ZNRDBFNDY=",
                    "Cookie: atlassian.xsrf.token=3148cef8-ac6a-4f78-9215-3bf6d6d1e11a_8016ef48ea2caf85c45d33f51bb778c20482da02_lin"
                ),
            ));

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            $response = curl_exec($curl);

            curl_close($curl);

            $data = json_decode($response, true);

            $date1 = new DateTime();
            $date2 =new DateTime($data['fields']['created']);
            $date3 = $date1->format("Y-m-d");
            $date4 = $date2->format("Y-m-d");
            $jmoins = ((strtotime($date3)) - strtotime($date4));
            $jmoins = round($jmoins / (60*60*24));
            if ($jmoins == 1) {
                array_push($listTest, ['issue' => $data['key'],
                    'summary' => $data['fields']['summary'],
                    'created_at' => $date4,
                    'name' => $data['fields']['assignee']['displayName'],
                    'status' => $data['fields']['status']['name']
                ]);
            }
        }

        return  $listTest;

    }

    /**
     * @param Swift_Mailer $mailer
     * @Route("/send-mail-issues", name="issuesSend")
     * @return View
     */
    public function postSendMail( Swift_Mailer $mailer) {

        $response = $this->getIssueToSendAction();

        $dateNow = new DateTime();
        $dateToday = $dateNow->modify('+1 day')->format("Y-m-d");
        $body = '<table  border=1>'.'<tr>'.'<th>Ticket</th>'.'<th>Description</th>'.'<th>Employé</th>'.'</tr>';
        for($x=0;$x<count($response);$x++)
        {
            $body .='<tr>'.'<td>'.$response[$x]["issue"].'</td>'.'<td>'.$response[$x]["summary"].'</td>'.'<td>'.$response[$x]["name"].'</td>'.'</tr>';
        }
        $body.='</table>';
        $message = (new Swift_Message('Tickets ('.$dateToday. ')'))
            ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
            ->setTo(['hamdi.arbi@agence-inspire.com'])
            ->setBody($body, 'html')
            ->setContentType('text/html');
        $mailer->send($message);


        return $this->view(null, Response::HTTP_OK);
    }



}
