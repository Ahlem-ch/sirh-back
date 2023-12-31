<?php

namespace App\Controller;

use App\Entity\NoteFrais;
use App\Repository\NoteFraisRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoteFraisController extends AbstractFOSRestController
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
     * @var NoteFraisRepository
     */
    private $noteFraisRepository;

    /**
     * NoteFraisController constructor.
     * @param NoteFraisRepository $noteFraisRepository
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(NoteFraisRepository $noteFraisRepository, EntityManagerInterface $entityManager, UserRepository $userRepository){

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->noteFraisRepository = $noteFraisRepository;
    }

    public function getNotesAction()
    {

        $data = $this->noteFraisRepository->findAll();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['note']));

    }

    public function getNoteAction(int $id)
    {
        $data = $this->noteFraisRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['note']));

    }

    public function deleteNoteAction(int $id)
    {
        $data = $this->noteFraisRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($data);
        $this->entityManager->flush();
        $notes = $this->noteFraisRepository->findAll();


        return $this->view($notes, Response::HTTP_OK)->setContext((new Context())->setGroups(['note']));

    }

    /**
     * @Route("/api/note", name="addNote")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param Swift_Mailer $mailer
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function noteAction(Request $request, FileUploader $fileUploader, Swift_Mailer $mailer)
    {

        $date = $request->get('date');
        $description = $request->get('description');
        $type = $request->get('type');
        $HT = $request->get('HT', null);
        $TVA = $request->get('TVA', null);
        $TTC = $request->get('TTC', null);
        $remboursement = $request->get('remboursement', null);
        $statut = $request->get('statut', 'En attente');
        $equipe = $request->get('equipe', null);
        $from = $request->get('from');
        $to = $request->get('to');
        $user_id = $request->get('user');
        $user = $this->userRepository->findOneBy(['id' => $user_id]);

        $note = new NoteFrais();
        do {
            $random = random_int(1, 9999);
            $ref = 'note' . $random;
            $array = $this->noteFraisRepository->findBy(['ref' => $ref] );
        } while ($array != null);

        $datee = new \DateTime($date);
        $note->setDate($datee);
        $files = $fileUploader->upload($request);
        if (count($files) == 2 ) {
            $image = $files['piece_jointe'];
            $note->setPieceJointe($image);
        }
        $note->setRef($ref);
        $note->setDescription($description);
        $note->setCreatedAt(new \DateTime());
        $note->setUser($user);
        $note->setStatut($statut);
        $note->setEquipe($equipe);
        $note->setType($type);
        if($HT){
            $note->setMontantHT($HT);
        }
        if($TVA){
            $note->setTVA($TVA);
        }
        if($TTC!= "null"){
            $note->setTTC($TTC);
        }
        if($remboursement!= ""){
            $note->setRemboursement($remboursement);
        }
        $this->entityManager->persist($note);
        $this->entityManager->flush();

        $data = $this->noteFraisRepository->findAll();

        if($user) {

            $dateNow = new DateTime();
            $dateToday = $dateNow->format("Y-m-d");

            if ($from === 'user') {

                if ($equipe === 'France'){
                    $to = 'esma.bensaid@agence-inspire.com';
                } else if ($equipe === 'Tunisie') {
                    $to = 'najla.romdhane@agence-inspire.com';
                }
                // contenu du mail

                $body = 'Je vous prie de bien vouloir procéder au remboursement  de l\'ensemble des frais engagés dans le cadre de cette mission professionnelle.<br> Envoyée le ' . $dateToday . ' par ' . $user->getNom() . ' ' . $user->getPrenom() . '' . ' est en attente';

                $message = (new Swift_Message('Remboursement de frais (' . $user->getNom() . '' . $user->getPrenom() . ')'))
                    ->setFrom(['dev@agence-inspire.com' => 'Agence Inspire'])
                    ->setTo([$to])
                    ->setBody($body, 'html')
                    ->setContentType('text/html');
                $mailer->send($message);

            } else if ($from === 'admin') {

                // contenu du mail

                $body = 'Votre demande au remboursement a été validée le ' . $dateToday . '<br><br><br>
                    Service RH INSPIRE';

                $message = (new Swift_Message('Validation de la demande au remboursement des frais'))
                    ->setFrom(['dev@agence-inspire.com' => 'Agence Inspire'])
                    ->setTo([$user->getEmail()])
                    ->setBody($body, 'html')
                    ->setContentType('text/html');
                $mailer->send($message);

            }
        }

        return $this->view($data,  Response::HTTP_CREATED)->setContext((new Context())->setGroups(['note']));

    }

    /**
     * @Route("/api/update/{id}/note", name="updateNote")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param int $id
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function modifierNoteAction(Request $request, int $id, FileUploader $fileUploader)
    {

        $note = $this->noteFraisRepository->findOneBy(['id' => $id]);

        if ($note) {
            $date = $request->get('date', $note->getDate());
            $description = $request->get('description');
            $user_id = $request->get('user', null);
            if ($user_id) {
                $user = $this->userRepository->findOneBy(['id' => $user_id]);
                $note->setUser($user);
            }

            $datee = new \DateTime($date);
            $note->setDate($datee);
            $note->setDescription($description);
            $files = $fileUploader->upload($request);
            if (count($files) == 2 ) {
                $image = $files['piece_jointe'];
                $note->setPieceJointe($image);
            }

            $this->entityManager->persist($note);
            $this->entityManager->flush();

            $data = $this->noteFraisRepository->findAll();

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['note']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/notes/{id}/validation", name="validationNote")
     * @param Request $request
     * @param int $id
     * @param Swift_Mailer $mailer
     * @return \FOS\RestBundle\View\View
     */
    public function patchNoteValidationAction(Request $request, int $id, Swift_Mailer $mailer)
    {

        $note = $this->noteFraisRepository->findOneBy(['id' => $id]);

        if ($note) {

            $statut = $request->get('statut', $note->getStatut());
            $cause = $request->get('cause_refus', $note->getCauseRefus());

            $note->setStatut($statut);
            $note->setCauseRefus($cause);

            $this->entityManager->persist($note);
            $this->entityManager->flush();

            $data = $this->noteFraisRepository->findAll();

            $dateNow = new DateTime();
            $dateToday = $dateNow->format("Y-m-d");

            if ( $statut === 'Validée') {

                $body = 'Votre demande au remboursement a été validée le ' . $dateToday. '<br><br><br>
                    Service RH INSPIRE';

                $message = (new Swift_Message('Retour de la demande de note de frais'))
                    ->setFrom(['dev@agence-inspire.com' => 'Agence Inspire'])
                    ->setTo([$note->getUser()->getEmail()])
                    ->setBody($body, 'html')
                    ->setContentType('text/html');
                $mailer->send($message);
            } else if ($statut === 'Refusée') {
                $body = 'Votre demande au remboursement a été refusée le ' . $dateToday. '<br><br><br>
                    Service RH INSPIRE';

                $message = (new Swift_Message('Retour de la demande de note de frais'))
                    ->setFrom(['dev@agence-inspire.com' => 'Agence Inspire'])
                    ->setTo([$note->getUser()->getEmail()])
                    ->setBody($body, 'html')
                    ->setContentType('text/html');
                $mailer->send($message);
            }

            return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['note']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

}
