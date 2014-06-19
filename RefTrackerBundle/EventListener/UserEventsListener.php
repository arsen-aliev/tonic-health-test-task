<?php

namespace Ars\RefTrackerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Ars\RefTrackerBundle\Services\RefTrackerManager;

class UserEventsListener implements EventSubscriberInterface
{
    /**
     * @var RefTrackerManager
     */
    private $refTrackerManager;

    /**
     * @param RefTrackerManager $manager
     */
    public function __construct(RefTrackerManager $manager)
    {
        $this->refTrackerManager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();
        $request = $event->getRequest();

        $this->refTrackerManager->assignCodeToUser($user);
        $this->refTrackerManager->assignRefToUser($request, $user);
    }

}