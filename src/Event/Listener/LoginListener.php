<?php

namespace App\Event\Listener;
// src/EventListener/LoginListener.php


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

        // Get the User entity.
        // $user = $event->getAuthenticationToken()->getUser();
        // // // $user->setIsEnable(TRUE);
        // var_dump($user->getIsEnable());
        // // die;
        // if ($user->getIsEnable() !== TRUE) {
        //     return new JsonResponse('Account not activated');
        // }
        // die;
    }
}
