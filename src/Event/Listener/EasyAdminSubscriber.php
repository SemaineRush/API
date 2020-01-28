<?php

namespace App\Event\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\BlogPost;
use App\Entity\Election;
use App\Service\NotificationSender;

class EasyAdminSubscriber implements EventSubscriberInterface
{


    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('setNotif'),
        );
    }

    public function setNotif(GenericEvent $event, NotificationSender $sender)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Election)) {
            return;
        }

        $dateEnd = $entity->getEndduration();
        $sender->sendNotif("Election finie", $dateEnd);
        $event['entity'] = $entity;
    }
}
