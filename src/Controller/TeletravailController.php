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
        $data2 = array_reverse($data);
        return $this->view($data2,Response::HTTP_OK)->setContext((new Context())->setGroups(['teletravail']));

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


            return $this->getTeletravailsAction();
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
        $from = $request->get('from');


        $teletravail = new Teletravail();

        $date_deb = new \DateTime($date_debut);
        $date_f = new \DateTime($date_fin);
        $teletravail->setDateDebut($date_deb);
        $teletravail->setDateFin($date_f);
        $teletravail->setDuree($duree);
        $teletravail->setDates($dates);
        if($from == 'admin') {
            $teletravail->setStatut("Validé");
        } else if ($from == 'user') {
            $teletravail->setStatut("En attente");

            //send Mail
            $dateNow = new DateTime();
            $dateToday = $dateNow->format("Y-m-d");
            if($from == 'admin') {
                $body = 'Votre demande d\'autorisation d\'exercer tes activités dans le cadre du télétravail a été validée avec succès le '.$dateToday ;
            } else if ($from == 'user') {
                $body = 'Une demande de télétravail a été envoyée le '.$dateToday.' par '.$user->getNom().' '.$user->getPrenom().''.' est en attente';
            }
            $message = (new Swift_Message('Demande de télétravail ('.$user->getNom().''.$user->getPrenom().')'))
                ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
                ->setTo([$email])
                ->setBody($body, 'html')
                ->setContentType('text/html');
            $mailer->send($message);

        }
        $teletravail->setCollaborateur($user);

        $this->entityManager->persist($teletravail);
        $this->entityManager->flush();




        return $this->getTeletravailsAction();

    }

    /**
     * @param Request $request
     * @param int $id
     * @param Swift_Mailer $mailer
     * @return \FOS\RestBundle\View\View
     */
    public function patchTeletravailAction(Request $request, int $id, Swift_Mailer $mailer)
    {

        $teletravail = $this->teletravailRepository->findOneBy(['id' => $id]);

        if ($teletravail) {

            $duree = $request->get('duree', $teletravail->getDuree());
            $date_debut = $request->get('date_debut', $teletravail->getDateDebut());
            $date_fin = $request->get('date_fin', $teletravail->getDateFin());
            $statut	 = $request->get('statut', $teletravail->getStatut());
            $dates = $request->get('dates', $teletravail->getDates());
            $cause = $request->get('cause_refus', $teletravail->getCauseRefus());
            $to = $request->get('to');


            $teletravail->setDateDebut($date_debut);
            $teletravail->setDateFin($date_fin);
            $teletravail->setDuree($duree);
            $teletravail->setStatut($statut);
            $teletravail->setDates($dates);
            $teletravail->setCauseRefus($cause);

            $this->entityManager->persist($teletravail);
            $this->entityManager->flush();


            $dateNow = new DateTime();
            $dateToday = $dateNow->format("Y-m-d");
            $date_deb_format= $date_debut->format("Y-m-d");
            $date_fin_format = $date_fin->format("Y-m-d");

            if($statut === 'Validé'){

                $subject = 'Votre demande d\'autorisation d\'exercer tes activités dans le cadre du télétravail a été validée avec succès le '.$dateToday ;
                $body = ' Nous avons bien reçu votre demande  d\'autorisation d\'exercer vos activités dans le cadre du télétravail'. '<br>'.
                    ' à partir  de ' . $date_deb_format.  ' jusqu\'à le ' . $date_fin_format. '.<br>'.
                    'Nous vous informons que nous vous donnons notre accord pour cette date.' . '<br>' .

                    'Nous vous prions d’agréer, Madame / Monsieur, nos respectueuses salutations.' . '<br><br><br>' .

                    'Service RH INSPIRE';

            } else if ($statut === 'Refusé') {

                $subject = 'Votre demande d\'autorisation d\'exercer tes activités dans le cadre du télétravail a été refusée le '.$dateToday ;
                $body = ' Nous avons bien reçu votre demande  d\'autorisation d\'exercer vos activités dans le cadre du télétravail'. '<br>'.
                    ' à partir  de ' . $date_deb_format.  ' jusqu\'à le ' . $date_fin_format. '.<br>'.
                    'Nous vous informons que nous sommes désolé de refusé votre demande pour cette date.' . '<br>' .

                    'Nous vous prions d’agréer, Madame / Monsieur, nos respectueuses salutations.' . '<br><br><br>' .

                    'Service RH INSPIRE';
            }

            $message = (new Swift_Message($subject))
                ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
                ->setTo([$to])
                ->setBody($body, 'html')
                ->setContentType('text/html');
            $mailer->send($message);

            return $this->getTeletravailsAction();

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
