<?php

namespace Chiave\StaticBundle\Controller;

use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FrontendController extends BaseController {

    /**
     * Static pages render action
     *
     * @Route("/{slug}", name="chiave_static")
     */
    public function indexAction($slug = 'home') {
        return $this->render('ChiaveStaticBundle:Frontend:' . $slug . '.html.twig');
    }

}
