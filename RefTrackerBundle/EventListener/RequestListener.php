<?php

namespace Ars\RefTrackerBundle\EventListener;

use Ars\RefTrackerBundle\Entity\RefData;
use Ars\RefTrackerBundle\Entity\RefHit;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class RequestListener
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $cookieName;

    /**
     * @var integer
     */
    private $cookieTtl;

    /**
     * @var string
     */
    private $queryParamName;

    /**
     * @var Request
     */
    private $request;

    public function __construct($queryParamName, $cookieName, $cookieTtl)
    {
        $this->queryParamName = $queryParamName;
        $this->cookieName = $cookieName;
        $this->cookieTtl = $cookieTtl;
    }

    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Request listener
     * check request for ref param
     * process data if real ref param preset
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // pass if sub request
            return;
        }

        $this->request = $event->getRequest();

        $code = $this->request->query->get($this->queryParamName);
        $refCode = $this->em->getRepository('ArsRefTrackerBundle:RefCode')->findOneByCode($code);

        if ($refCode) {//we have a real ref code

            //save some data about referer
            $refData = new RefData();
            $refData
                ->setIp($this->request->getClientIp())
                ->setReferer($this->request->headers->get('referer'))
                ->setDate(new \DateTime());

            $this->em->persist($refData);

            //save hit
            $refHit = new RefHit();
            $refHit
                ->setRefCode($refCode)
                ->setRefData($refData);

            $this->em->persist($refHit);
            $this->em->flush();

            //redirect
            $event->setResponse(
                $this->createResponse($refHit->getId())
            );

        }

    }

    /**
     * Create cookie, generate url and create redirect response
     *
     * @param integer $cookieValue
     * @return RedirectResponse
     */
    private function createResponse($cookieValue)
    {
        //create cookie to client with hitId
        $cookie = new Cookie($this->cookieName, $cookieValue, time() + $this->cookieTtl);

        //remove ref param
        $this->request->query->remove($this->queryParamName);

        $routerName = $this->request->get('_route');
        $routerParams = $this->request->get('_route_params');
        $queryParams = $this->request->query->all();

        //generate uri without ref param
        $uri = $this->router->generate($routerName, array_merge($routerParams, $queryParams));

        //create redirect response
        $response = new RedirectResponse($uri, 301);
        $response->headers->setCookie($cookie);

        return $response;
    }
}
