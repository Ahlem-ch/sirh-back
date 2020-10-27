<?php

namespace App\Controller;

use App\Entity\Teletravail;
use App\Repository\TeletravailRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeletravailController extends AbstractFOSRestController
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
     * @var TeletravailRepository
     */
    private $teletravailRepository;


    /**
     * teletravailController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TeletravailRepository $teletravailRepository
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, TeletravailRepository $teletravailRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->teletravailRepository = $teletravailRepository;
    }

    public function getTeletravailsAction(){

        $data = $this->teletravailRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['teletravail']));

    }

    public function getTeletravailAction(int $id) {
        $data = $this->teletravailRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['teletravail']));

    }


    public function deleteTeletravailAction(int $id) {
        $data = $this->teletravailRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $teletravails = $this->teletravailRepository->findAll();

            return $this->view(   $teletravails, Response::HTTP_OK)->setContext((new Context())->setGroups(['teletravail']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @Route("/api/demande/teletravail", name="demande_teletravail")
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postTeletravailAction (Request $request, Swift_Mailer $mailer) {

        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');
        $duree = $request->get('duree');
        $dates = $request->get('dates');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);
        $email = $request->get('to');


        $teletravail = new Teletravail();

        $date_deb = new \DateTime($date_debut);
        $date_f = new \DateTime($date_fin);
        $teletravail->setDateDebut($date_deb);
        $teletravail->setDateFin($date_f);
        $teletravail->setDuree($duree);
        $teletravail->setDates($dates);
        $teletravail->setStatut("En attente");
        $teletravail->setCollaborateur($user);

        $this->entityManager->persist($teletravail);
        $this->entityManager->flush();


        //send Mail

        $dateNow = new DateTime();
        $dateToday = $dateNow->format("Y-m-d");

        $body = 'Une demande de télétravail a été envoyée le '.$dateToday.' par '.$user->getNom().' '.$user->getPrenom().''.' est en attente';
        $message = (new Swift_Message('Demande de télétravail ('.$user->getNom().''.$user->getPrenom().')'))
            ->setFrom(['mahdi.znaidi@agence-inspire.com' => 'Agence Inspire'])
            ->setTo([$email])
            ->setBody($body, 'html')
            ->setContentType('text/html');
        $mailer->send($message);

        $data = $this->teletravailRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['teletravail']));

    }

    /**
     * @param Request $request
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function patchTeletravailAction(Request $request, int $id)
    {

        $teletravail = $this->teletravailRepository->findOneBy(['id' => $id]);

        if ($teletravail) {

            $duree = $request->get('duree', $teletravail->getDuree());
            $date_debut = $request->get('date_debut', $teletravail->getDateDebut());
            $date_fin = $request->get('date_fin', $teletravail->getDateFin());
            $statut	 = $request->get('statut', $teletravail->getStatut());
            $dates = $request->get('dates', $teletravail->getDates());
            $cause = $request->get('cause_refus', $teletravail->getCauseRefus());

            $teletravail->setDateDebut($date_debut);
            $teletravail->setDateFin($date_fin);
            $teletravail->setDuree($duree);
            $teletravail->setStatut($statut);
            $teletravail->setDates($dates);
            $teletravail->setCauseRefus($cause);

            $this->entityManager->persist($teletravail);
            $this->entityManager->flush();

            $data = $this->teletravailRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['teletravail']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
