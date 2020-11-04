<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Repository\CongeRepository;
use App\Repository\TypeCongeRepository;
use App\Repository\UserRepository;
use DateTime;
use Swift_Message;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CongeController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CongeRepository
     */
    private $congeRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TypeCongeRepository
     */
    private $typeCongeRepository;

    /**
     * CongeController constructor.
     * @param EntityManagerInterface $entityManager
     * @param CongeRepository $congeRepository
     * @param UserRepository $userRepository
     * @param TypeCongeRepository $typeCongeRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CongeRepository $congeRepository, UserRepository $userRepository, TypeCongeRepository $typeCongeRepository)
    {
        $this->entityManager = $entityManager;
        $this->congeRepository = $congeRepository;
        $this->userRepository = $userRepository;
        $this->typeCongeRepository = $typeCongeRepository;
    }

    public function getCongesAction(){

        $data = $this->congeRepository->findAll();
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));

    }

    public function getCongeAction(int $id) {
        $data = $this->congeRepository->findOneBy(['id'=>$id]);
        return $this->view($data,Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));

    }


    public function deleteCongeAction(int $id) {
        $data = $this->congeRepository->findOneBy(['id'=>$id]);

        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $conges = $this->congeRepository->findAll();

            return $this->view(   $conges, Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));
        }
        return $this->view(null, Response::HTTP_NO_CONTENT);

    }

    /**
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @Route("/api/demande/conge", name="demande_conge")
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postCongeAction (Request $request, Swift_Mailer $mailer) {

        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');
        $duree = $request->get('duree');
        $type_id = $request->get('type');
        $dates = $request->get('dates');
        $statut	 = $request->get('statut');
        $mois	 = $request->get('num_mois');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);
        $type = $this->typeCongeRepository->findOneBy(['id' => $type_id]);
        $email = $request->get('to');


        $conge = new Conge();

        $date_deb = new \DateTime($date_debut);
        $date_f = new \DateTime($date_fin);
        $conge->setDateDebut($date_deb);
        $conge->setDateFin($date_f);
        $conge->setDuree($duree);
        $conge->setTypeConge($type);
        $conge->setDates($dates);
        $conge->setStatut("En Attente");
        $conge->setNumMois($mois);
        $conge->setEmploye($user);

        $this->entityManager->persist($conge);
        $this->entityManager->flush();


        //send Mail

        $dateNow = new DateTime();
        $dateToday = $dateNow->format("Y-m-d");

        $body = 'Une demande de congés a été envoyée le '.$dateToday.' par '.$user->getNom().' '.$user->getPrenom().''.' est en attente';
        $message = (new Swift_Message('Demande de congé ('.$user->getNom().''.$user->getPrenom().')'))
            ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
            ->setTo([$email])
            ->setBody($body, 'html')
            ->setContentType('text/html');
        $mailer->send($message);

        $data = $this->congeRepository->findAll();

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['conge']));

    }

    /**
     * @param Request $request
     * @param int $id
     * @param Swift_Mailer $mailer
     * @return \FOS\RestBundle\View\View
     */
    public function patchCongeAction(Request $request, int $id , Swift_Mailer $mailer)
    {

        $conge = $this->congeRepository->findOneBy(['id' => $id]);

        if ($conge) {

            $duree = $request->get('duree', $conge->getDuree());
            $type = $request->get('type',$conge->getTypeConge());
            $date_debut = $request->get('date_debut', $conge->getDateDebut());
            $date_fin = $request->get('date_fin', $conge->getDateFin());
            $statut	 = $request->get('statut', $conge->getStatut());
            $dates = $request->get('dates', $conge->getDates());
            $cause = $request->get('cause_refus', $conge->getTypeConge());
            $email = $request->get('to');

            $conge->setDateDebut($date_debut);
            $conge->setDateFin($date_fin);
            $conge->setDuree($duree);
            $conge->setTypeConge($type);
            $conge->setStatut($statut);
            $conge->setDates($dates);
            $conge->setCauseRefus($cause);

            $this->entityManager->persist($conge);
            $this->entityManager->flush();



            $date_deb_format= $date_debut->format("Y-m-d");
            $date_fin_format = $date_fin->format("Y-m-d");

            if($statut === 'Validé'){

                if($duree == 1 ) {

                    $body = ' Nous avons bien reçu votre demande dans lequel vous nous indiquez que vous souhaitez prendre votre congé principal 
              le ' . $date_deb_format. '<br>' .
                        'Nous vous informons que nous vous donnons notre accord pour cette date.' . '<br>' .

                        'Nous vous prions d’agréer, Madame / Monsieur, nos respectueuses salutations.' . '<br>' .

                        'Sevice RH';
                } else  {
                    $body = ' Nous avons bien reçu votre demande dans lequel vous nous indiquez que vous souhaitez prendre votre congé principal 
              du ' . $date_deb_format . '  au ' . $date_fin_format . '<br>' .
                        'Nous vous informons que nous vous donnons notre accord pour ces dates de congés.' . '<br>' .

                        'Nous vous prions d’agréer, Madame / Monsieur, nos respectueuses salutations.' . '<br>' .

                        'Sevice RH';
                }
            $message = (new Swift_Message('Demande de congé payé'))
                ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
                ->setTo([$email])
                ->setBody($body, 'html')
                ->setContentType('text/html');
            $mailer->send($message);

            } else if ($statut === 'Refusé') {

                if($duree == 1 ) {

                    $body = ' Nous avons bien reçu votre demande dans lequel vous nous indiquez que vous souhaitez prendre votre congé principal 
              le ' . $date_deb_format. '<br>' .
                        'Nous vous informons que nous somme désolé de refusé votre demande pour cette date.' . '<br>' .

                        'Nous vous prions d’agréer, Madame / Monsieur, nos respectueuses salutations.' . '<br>' .

                        'Sevice RH';
                } else  {
                    $body = ' Nous avons bien reçu votre demande dans lequel vous nous indiquez que vous souhaitez prendre votre congé principal 
              du ' . $date_deb_format . '  au ' . $date_fin_format . '<br>' .
                        'Nous vous informons que nous somme désolé de refusé votre demande pour ces dates de congés.' . '<br>' .

                        'Nous vous prions d’agréer, Madame / Monsieur, nos respectueuses salutations.' . '<br>' .

                        'Sevice RH';
                }
                $message = (new Swift_Message('Demande de congé payé'))
                    ->setFrom(['no-reply@agence-inspire.com' => 'Agence Inspire'])
                    ->setTo([$email])
                    ->setBody($body, 'html')
                    ->setContentType('text/html');
                $mailer->send($message);

            }


            $data = $this->congeRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['conge']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }



}
