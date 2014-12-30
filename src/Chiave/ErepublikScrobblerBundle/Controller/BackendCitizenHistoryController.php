<?php

namespace Chiave\ErepublikScrobblerBundle\Controller;

use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\ErepublikScrobblerBundle\Document\Citizen;

/**
 * Citizen controller.
 *
 * @Route("/admin/citizens/histories")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BackendCitizenHistoryController extends BaseController
{
    /**
     * Show all citizen changes.
     *
     * @Route("/{citizenId}", name="chiave_scrobbler_citizens_histories")
     * @Method("GET")
     * @Template()
     */
    public function showAction($citizenId)
    {
        $em = $this->get('doctrine_mongodb')->getManager();

        $citizen = $em
                ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
                ->find($citizenId)
        ;
        // $histories = $em
        //     ->getRepository('ChiaveErepublikScrobblerBundle:CitizenHistory')
        //     ->findByCitizen($citizenId)
        // ;

        return array(
            'citizen' => $citizen,
                // 'histories' => $histories,
        );
    }
}
