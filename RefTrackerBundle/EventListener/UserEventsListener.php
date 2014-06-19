<?php

namespace Ars\RefTrackerBundle\EventListener;

use Ars\RefTrackerBundle\ArsRefTrackerBundle;
use Ars\RefTrackerBundle\Entity\RefUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Doctrine\ORM\EntityManager;
use Ars\RefTrackerBundle\Entity\RefCode;
use Ars\RefTrackerBundle\Services\UniqIdGenerator;

class UserEventsListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UniqCodeGenerator
     */
    private $uniqIdGenerator;

    /**
     * @var string
     */
    private $cookieName;

    public function __construct($cookieName)
    {
        $this->cookieName = $cookieName;
    }

    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setUniqIdGenerator(UniqIdGenerator $uniqIdGenerator)
    {
        $this->uniqIdGenerator = $uniqIdGenerator;
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

        do {//while code is not uniq - generate next one
            $uniqId = $this->uniqIdGenerator->generate();
            $isUsed = (bool) $this->em->getRepository('ArsRefTrackerBundle:RefCode')->findOneByCode($uniqId);
        } while ($isUsed);

        //assign new refCode to new user
        $refCode = new RefCode();
        $refCode->setCode($uniqId)
                ->setUser($user);

        $this->em->persist($refCode);

        //assign ref hit (ref data + ref code) for user if preset
        $cookieValue = $request->cookies->get($this->cookieName);
        $refHit = $this->em->getRepository('ArsRefTrackerBundle:RefHit')->findOne($cookieValue);
        if ($refHit) {
            $refUser = new RefUser();
            $refUser->setRefHit($refHit)
                    ->setRefUser($user);

            $this->em->persist($refUser);
        }

        $this->em->flush();

    }

}