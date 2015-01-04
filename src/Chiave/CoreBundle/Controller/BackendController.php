<?php

namespace Chiave\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BackendController extends BaseController
{
    /**
     * dashboard Action
     *
     * @Route("/admin", name="admin_dashboard")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function dashboardAction()
    {
        // $this->get('erepublik_citizen_scrobbler')->showRawData(4241769);
        // die;

        return $this->render(
                        'ChiaveCoreBundle:Admin:dashboard.html.twig'
        );
    }
}
