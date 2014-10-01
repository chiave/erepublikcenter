<?php

namespace Chiave\StaticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FrontendController extends Controller {

    /**
     * Static pages render action
     *
     * @Route("/{slug}", name="chiave_static")
     */
    public function indexAction($slug = 'home') {
        return $this->render('ChiaveStaticBundle:Frontend:' . $slug . '.html.twig');
    }

}
