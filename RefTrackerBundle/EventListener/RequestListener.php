<?php

namespace Ars\RefTrackerBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Ars\RefTrackerBundle\Services\RefTrackerManager;

class RequestListener
{

    /**
     * @var RefTrackerManager
     */
    private $refTrackerManager;

    /**
     * @var Router
     */
    private $router;


    /**
     * @param RefTrackerManager $manager
     */
    public function __construct(RefTrackerManager $manager, Router $router)
    {
        $this->refTrackerManager = $manager;
        $this->router = $router;
    }

    /**
     * Request listener
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // pass if sub request
            return;
        }

        $request = $event->getRequest();

        $hitId = $this->refTrackerManager->saveRefVisit($request);

        if($hitId) {//redirect
            $event->setResponse(
                $this->refTrackerManager->createResponse($request, $this->router, $hitId)
            );
        }
    }

}
