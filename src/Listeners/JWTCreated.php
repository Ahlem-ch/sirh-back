<?php
namespace App\Listeners;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreated
{

    public function onJWTCreated(JWTCreatedEvent $event)
    {

        $user = $event->getUser();

// add new data
        $payload['user_id'] = $user->getId();
        $payload['location'] = $user->getLocation();
        $payload['username'] = $user->getUsername();
        $payload['roles'] = $user->getRoles();

        $event->setData($payload);
    }
}
