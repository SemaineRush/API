<?php

namespace AppBundle\Event\Listener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
     * Replaces the data in the generated
     *
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */
        $user = $event->getUser();
        var_dump("Toto");
        die;
        // add new data
        $payload['userId'] = $user->getId();
        $payload['username'] = $user->getUsername();

        $event->setData($payload);
    }
}
