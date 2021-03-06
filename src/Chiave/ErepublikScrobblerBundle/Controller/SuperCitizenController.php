<?php

namespace Chiave\ErepublikScrobblerBundle\Controller;

use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\ErepublikScrobblerBundle\Document\Citizen;

/**
 * Citizen controller.
 *
 * @Route("/super/citizens")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class SuperCitizenController extends BaseController
{
    /**
     * @Route("/scrobble", name="super_players_scrobble")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
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
    public function indexNextAction()
    {
        $nf = $this->get('egov_nationalraport_fetcher');
        $nf->fetchOldHistory(40);
        die;

//        return array(
//            'citizens' => $citizens,
//        );
    }

    /**
     * @Route("/fixer", name="super_history_fixer")
     * @Method("GET")
     * @Template()
     */
    public function fixerAction()
    {
        $nf = $this->get('egov_nationalraport_fetcher');
        $nf->fixer();
        die;

//        return array(
//            'citizens' => $citizens,
//        );
    }
}
