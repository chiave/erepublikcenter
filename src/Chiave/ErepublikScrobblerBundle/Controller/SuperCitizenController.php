<?php

namespace Chiave\ErepublikScrobblerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\ErepublikScrobblerBundle\Document\Citizen;
use Chiave\ErepublikScrobblerBundle\Form\CitizenType;

/**
 * Citizen controller.
 *
 * @Route("/super/citizens")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class SuperCitizenController extends BaseController {

    /**
     * @Route("/scrobble", name="super_players_scrobble")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {

        $nf = $this->get('egov_nationalraport_fetcher');
        $nf->fetchBluerosePlayers(100);
        die;

//        return array(
//            'citizens' => $citizens,
//        );
    }

    /**
     * @Route("/scrobblehistory", name="super_history_scrobble")
     * @Method("GET")
     * @Template()
     */
    public function indexNextAction() {

        $nf = $this->get('egov_nationalraport_fetcher');
        $nf->fetchOldHistory(40);
        die;

//        return array(
//            'citizens' => $citizens,
//        );
    }

}
