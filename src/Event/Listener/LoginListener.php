<?php

namespace App\Event\Listener;
// src/EventListener/LoginListener.php


use Exception;
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
        $user = $event->getAuthenticationToken()->getUser();
        // // $user->setIsEnable(TRUE);
        if ($user->getIsEnable() !== TRUE) {
            header("HTTP/1.1 401 Unauthorized");
            // throw new Exception("Account not enable");

            exit;
        }
    }
}
