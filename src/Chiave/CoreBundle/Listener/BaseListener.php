<?php

namespace Chiave\CoreBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

//    #NOTICE: Common method

    /**
     * @param  string $alias
     * @return mixed
     */
    public function get($alias)
    {
        return $this->container->get($alias);
    }

    /**
     * Get doctrine.
     * No matter if m*sql or mongo is used.
     *
     * @return mixed
     */
    public function getDoctrine()
    {
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
    public function getManager()
    {
        return $this->getManager();
    }

    /**
     * Get repository.
     * No matter if m*sql or mongo is used.
     *
     * @param  string $alias
     * @return mixed
     */
    public function getRepo($alias)
    {
        return $this->getManager()->getRepository($alias);
    }

    /**
     * getQb
     * No matter if m*sql or mongo is used.
     *
     * @param  string                     $alias - alias for class
     * @return \Doctrine\ORM\QueryBuilder instance with an 'a' alias
     */
    public function getQb($alias, $queryName = 'a')
    {
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
     * @param string $type    = 'notice'
     */
    public function addFlashMsg($message, $type = 'default')
    {
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
    public function trans($message)
    {
        return $this->container
                        ->get('translator')
                        ->trans($message)
        ;
    }

//    #NOTICE: Unique methods
}
