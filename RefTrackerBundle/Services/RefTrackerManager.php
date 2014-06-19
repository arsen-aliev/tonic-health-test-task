<?php

namespace Ars\RefTrackerBundle\Services;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Cookie;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserInterface;
use Ars\RefTrackerBundle\Entity\RefData;
use Ars\RefTrackerBundle\Entity\RefHit;
use Ars\RefTrackerBundle\Entity\RefCode;
use Ars\RefTrackerBundle\Entity\RefUser;


/**
 * Class  RefTrackerManager
 *
 * Manager for data collection, assigning, etc
 *
 * @package Ars\RefTrackerBundle\Services
 */

class RefTrackerManager
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UniqIdGenerator
     */
    private $uniqIdGenerator;

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

    public function setUniqIdGenerator(UniqIdGenerator $uniqIdGenerator)
    {
        $this->uniqIdGenerator = $uniqIdGenerator;
    }

    public function assignCodeToUser(UserInterface $user)
    {
        do {//while code is not uniq - generate next one
            $uniqId = $this->uniqIdGenerator->generate();
            $isUsed = (bool) $this->em->getRepository('ArsRefTrackerBundle:RefCode')->findOneByCode($uniqId);
        } while ($isUsed);

        //assign new refCode to new user
        $refCode = new RefCode();
        $refCode->setCode($uniqId)
            ->setUser($user);

        $this->em->persist($refCode);
        $this->em->flush();
    }

    public function assignRefToUser(Request $request, UserInterface $user)
    {
        //assign ref hit (ref data + ref code) for user if preset
        $cookieValue = $request->cookies->get($this->cookieName);

        $refHit = $this->em->getRepository('ArsRefTrackerBundle:RefHit')->find($cookieValue);
        if ($refHit) {
            $refUser = new RefUser();
            $refUser
                ->setRefHit($refHit)
                ->setRefUser($user);

            $this->em->persist($refUser);
            $this->em->flush();
        }
    }

    /**
     * Create cookie, generate url and create redirect response
     *
     * @param Request $request
     * @param Router $router
     * @param integer $cookieValue
     * @return RedirectResponse
     */
    public function createResponse(Request $request, Router $router, $cookieValue)
    {
        //create cookie to client with hitId
        $cookie = new Cookie($this->cookieName, $cookieValue, time() + $this->cookieTtl);

        //remove ref param
        $request->query->remove($this->queryParamName);

        $routerName = $request->get('_route');
        $routerParams = $request->get('_route_params');
        $queryParams = $request->query->all();

        //generate uri without ref param
        $uri = $router->generate($routerName, array_merge($routerParams, $queryParams));

        //create redirect response
        $response = new RedirectResponse($uri, 301);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * Save ref hit (visit) if present ref and return hitId
     *
     * @param Request $request
     * @return integer|null $hitId
     */
    public function saveRefVisit(Request $request)
    {

        $code = $request->query->get($this->queryParamName);
        $refCode = $this->em->getRepository('ArsRefTrackerBundle:RefCode')->findOneByCode($code);

        if ($refCode) {//we have a real ref code

            //save some data about referer
            $refData = new RefData();
            $refData
                ->setIp($request->getClientIp())
                ->setReferer($request->headers->get('referer'))
                ->setDate(new \DateTime());

            $this->em->persist($refData);

            //save hit
            $refHit = new RefHit();
            $refHit
                ->setRefCode($refCode)
                ->setRefData($refData);

            $this->em->persist($refHit);
            $this->em->flush();

            return $refHit->getId();

        }

        return null;
    }

}