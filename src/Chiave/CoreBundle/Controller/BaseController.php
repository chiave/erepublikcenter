<?php

namespace Chiave\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//
use Chiave\SocialEventBundle\Helper\Extension\CommonExtensionHelper;

//use Doctrine\ODM\MongoDB\Query\Builder;
// use Symfony\Component\DependencyInjection\ContainerAware;
// extends ContainerAware
class BaseController extends Controller {
//    #NOTICE: Common method

    /**
     * @param string $alias
     * @return mixed
     */
    public function get($alias) {
        return $this->container->get($alias);
    }

    /**
     * Get doctrine.
     * No matter if m*sql or mongo is used.
     *
     * @return mixed
     */
    public function getDoctrine() {
        if ($this->container->has('doctrine_mongodb')) {
            return $this->container->get('doctrine_mongodb');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Get manager.
     * No matter if m*sql or mongo is used.
     *
     * @return mixed
     */
    public function getManager() {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Get repository.
     * No matter if m*sql or mongo is used.
     *
     * @param string $alias
     * @return mixed
     */
    public function getRepo($alias) {
        return $this->getManager()->getRepository($alias);
    }

    /**
     * getQb
     * No matter if m*sql or mongo is used.
     *
     * @param string $alias - alias for class
     * @return Doctrine\ODM\MongoDB\Query\Builder
     * return \Doctrine\ORM\QueryBuilder instance with an 'a' alias
     */
    public function getQb($alias, $queryName = 'a') {
//        new \Doctrine\ORM\QueryBuilder
        return $this->getDoctrine()
                        ->getRepository($alias)
                        ->createQueryBuilder($queryName)
        ;
    }

    /**
     * add Flash Message
     * supported types:
     *      default     - blue
     *      success     - green
     *      warning     - orange
     *      info        - light blue
     *      alert       - red
     *      secondary   - gray
     *
     * @param string $message - autotranslated, if translation exists
     * @param string $type = 'notice'
     */
    public function addFlashMsg($message, $type = 'default') {
        $this->container
                ->get('session')
                ->getFlashBag()
                ->add($type, $this->trans($message))
        ;
    }

    /**
     * Translate string
     *
     * @param string $message - translated, if translation exists
     */
    public function trans($message) {
        return $this->container
                        ->get('translator')
                        ->trans($message)
        ;
    }

//    #NOTICE: Unique methods
}
