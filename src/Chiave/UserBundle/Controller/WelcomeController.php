<?php

namespace Chiave\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Welcome controller.
 *
 * @Route("")
 */
class WelcomeController extends Controller
{
	/**
     * @Route("", name="welcome")
     */
	public function indexAction()
	{
		$user = $this->getUser();
		// $user = $this->container->get('fos_user.user_menager')
		// 	->findUserByName('Konrad');

		var_dump($user);die;
	}



}