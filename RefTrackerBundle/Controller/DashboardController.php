<?php

namespace Ars\RefTrackerBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{

    /**
     * @Route("/dashboard")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getManager();

        $refCode = $em->getRepository('ArsRefTrackerBundle:RefCode')->findOneByUserId($user->getId());

        $refHits = $em->getRepository('ArsRefTrackerBundle:RefHit')->findByRefCode($refCode);

        return array(
            'user' => $user,
            'queryParamName' => $this->container->getParameter('ars_ref_tracker.query_param_name'),
            'refCode' => $refCode,
            'refHits' => $refHits
        );
    }
}
