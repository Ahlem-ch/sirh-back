<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;


class NotificationController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * NotificationController constructor.
     * @param NotificationRepository $notificationRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(NotificationRepository $notificationRepository, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * List All Notifications
     *
     * @OA\Get(
     *   path="/notifications",
     *   summary="List all Notifications",
     *   tags = {"Notification"},
     *   operationId="indexNotification",
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="postes_list"),
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Poste")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/notifications/{id}/{role}", name="listNotification")
     * @param int $id
     * @param string $role
     * @return \FOS\RestBundle\View\View
     */
    public function getNotificationsAction(int $id, string $role)
    {

        $notifications = $this->notificationRepository->findAllOrderByDate();
        if ($role == 'admin') {
            $data = $this->notificationRepository->countNotificationAdminNotRead();
        } else {
            $data = $this->notificationRepository->countNotificationNotRead($id);
        }


        return $this->view([$notifications, $data], Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));
    }


    /**
     * Show an existing Notification
     *
     * @OA\Get(
     *   path="/notifications/{id}",
     *   summary="Show an existing Notification",
     *   tags = {"Notification"},
     *   operationId="showNotification",
     *   @OA\Parameter(name="id", in="path", description="ID of Poste to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="poste_details"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Poste")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @param int $id
     * @return \FOS\RestBundle\View\View
     */
    public function getNotificationnAction(int $id)
    {
        $data = $this->notificationRepository->findOneBy(['id' => $id]);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

    }


    /**
     * Delete an existing Notification
     *
     * @OA\Delete(
     *   path="/notification/{id}",
     *   summary="Delete an existing Notification",
     *   tags = {"Notification"},
     *   operationId="destroyNotification",
     *   @OA\Parameter(name="id", in="path", description="ID of Departement to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=204, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=204),
     *       @OA\Property(property="message", type="string", example="poste_deleted"),
     *       @OA\Property(property="data", @OA\Items()),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/notifications/{id}/{user_id}/{role}", name="deleteNotification")
     * @param int $id
     * @param int $user_id
     * @param string $role
     * @return \FOS\RestBundle\View\View
     */
    public function deleteNotificationAction(int $id, int $user_id, string $role)
    {
        $data = $this->notificationRepository->findOneBy(['id' => $id]);


        if ($data) {

            $this->entityManager->remove($data);
            $this->entityManager->flush();

            $notifications = $this->notificationRepository->findAllOrderByDate();

            if ($role == 'admin') {
                $data = $this->notificationRepository->countNotificationAdminNotRead();
            } else {
                $data = $this->notificationRepository->countNotificationNotRead($user_id);
            }


            return $this->view([$notifications, $data], Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

        }

        return $this->view(null, Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

    }


    /**
     * Create a new Notification
     *
     * @OA\Post(
     *   path="/notifications",
     *   summary="Create a new Notification",
     *   tags = {"Notification"},
     *   operationId="storePoste",
     *   requestBody={"$ref": "#/components/requestBodies/Poste"},
     *   @OA\Response(response=201, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=201),
     *       @OA\Property(property="message", type="string", example="poste_created"),
     *       @OA\Property(property="data", @OA\Property(property="id",type="integer"),@OA\Property(property="libelle_poste",type="string")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     * @throws \Exception
     */
    public function postNotificationAction(Request $request )
    {

        $titre = $request->get('titre');
        $commentaire = $request->get('commentaire');
        $is_read = $request->get('is_read');
        $user_id = $request->get('user');
        $send_to = $request->get('send_to', null);
        $type = $request->get('type', null);
        $role = $request->get('role', null);

        $user = $this->userRepository->findOneBy(['id' => $user_id]);
        $notification = new Notification();

        $notification->setTitre($titre);
        $notification->setCommentaire($commentaire);
        $notification->setIsRead($is_read);
        $notification->setCreatedAt(new \DateTime());
        $notification->setUser($user);
        if ($send_to){
            $notification->setSendTo($send_to);
        }
        if ($type){
            $notification->setType($type);
        }


        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        if ($role == 'admin') {
            $data = $this->notificationRepository->countNotificationAdminNotRead();
        } else {
            $data = $this->notificationRepository->countNotificationNotRead($user_id);
        }


        $notifications = $this->notificationRepository->findAllOrderByDate();

        return $this->view([$notifications, $data], Response::HTTP_CREATED)->setContext((new Context())->setGroups(['notification']));

    }


    /**
     * Update an existing Notification
     *
     * @OA\Patch(
     *   path="/notification/{id}",
     *   summary="Update an existing Notification",
     *   tags = {"Poste"},
     *   operationId="updateNotification",
     *   requestBody={"$ref": "#/components/requestBodies/Notification"},
     *   @OA\Parameter(name="id", in="path", description="ID of equipe to return", required=true,
     *     @OA\Schema(type="integer", format="int64")
     *   ),
     *   @OA\Response(response=200, description="successful operation",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="code", type="integer", example=200),
     *       @OA\Property(property="message", type="string", example="poste_updated"),
     *       @OA\Property(property="data", @OA\Items(ref="#/components/schemas/Poste")),
     *     ),
     *   ),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   security={
     *     {
     *       "Password Based": {*}
     *     }
     *   }
     * )
     * @Route("/api/update/notifications/{id}/{user_id}", name="updateNotification")
     * @param Request $request
     * @param int $id
     * @param int $user_id
     * @return \FOS\RestBundle\View\View
     */
    public function patchNotificationAction(Request $request, int $id, int $user_id)
    {


        $notification = $this->notificationRepository->findOneBy(['id' => $id]);

        if ($notification) {

            $titre = $request->get('titre', $notification->getTitre());
            $commentaire = $request->get('commentaire', $notification->getCommentaire());
            $is_read = $request->get('is_read', $notification->getIsRead());
            $send_to = $request->get('send_to', $notification->getSendTo());

            $notification->setTitre($titre);
            $notification->setCommentaire($commentaire);
            $notification->setIsRead($is_read);
            if ($send_to){
                $notification->setSendTo($send_to);
            }

            $this->entityManager->persist($notification);
            $this->entityManager->flush();
            $notifications = $this->notificationRepository->findAllOrderByDate();
            if ($send_to) {
                $data = $this->notificationRepository->countNotificationAdminNotRead();
            } else {
                $data = $this->notificationRepository->countNotificationNotRead($user_id);
            }


            return $this->view([$notifications, $data], Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

        }
        return $this->view(null, Response::HTTP_NO_CONTENT)->setContext((new Context())->setGroups(['notification']));
    }


    public function getNotificationCountAllAction()
    {
        $data = $this->notificationRepository->countAllNotification();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

    }

    public function getNotificationCountNotReadAction($id)
    {
        $data = $this->notificationRepository->countNotificationNotRead($id);
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

    }

    public function getNotificationCountAdminNotReadAction($id)
    {
        $data = $this->notificationRepository->countNotificationAdminNotRead();
        return $this->view($data, Response::HTTP_OK)->setContext((new Context())->setGroups(['notification']));

    }

}
