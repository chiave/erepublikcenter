<?php

namespace Chiave\StatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontendController extends BaseController {

    /**
     * @Route("/zbiorki")
     * @Template()
     */
    public function gatheringAction() {
        $em = $this->get('doctrine_mongodb')->getManager();

        $militaryUnits = $em
                ->getRepository('ChiaveMilitaryUnitBundle:MilitaryUnit')
                ->findAll()
        ;

        $citizens = $em
                ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
                ->findAll()
        ;

        // $dayChange = $this->container->get('date_time')->getDayChange(1);
        // $now = new \DateTime('now');
        // $citizenHistories = $em
        //     ->getRepository('ChiaveErepublikScrobblerBundle:CitizenHistory')
        //     ->createQueryBuilder('ch')
        //         ->where('ch.egovBattles != 0')
        //         ->andWhere('ch.createdAt >= :dayChange')
        //             ->setParameter('dayChange', $dayChange)
        //         ->andWhere('ch.createdAt <= :now')
        //             ->setParameter('now', $now)
        //         ->orderBy('ch.createdAt', 'ASC')
        //         ->getQuery()
        //         ->getResult()
        // ;
        // return array(
        //     'citizenHistories' => $citizenHistories,
        //     'militaryUnits' => $militaryUnits,
        // );

        return array(
            'citizens' => $citizens,
            'militaryUnits' => $militaryUnits,
        );
    }

    /**
     * @Route("/ranking")
     * @Template()
     */
    public function rankingAction() {
        $em = $this->getEm();

        $lastRankingTime = $this->container->get('date_time')->getRankingTime();

        $rankings = $em
                ->getRepository('ChiaveStatsBundle:Ranking')
                ->findBy(
                array(), array('createdAt' => 'DESC')
                )
        ;

        $ranking = $rankings[0];

        return array(
            'ranking' => $ranking,
        );
    }

    private function getEm() {
        return $this->getManager();
    }

}
