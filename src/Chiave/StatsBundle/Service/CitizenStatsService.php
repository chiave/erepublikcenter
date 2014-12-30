<?php

namespace Chiave\StatsBundle\Service;

use Symfony\Component\HttpFoundation\Response;

/**
 * class CitizenStatsService
 *
 * class description here
 *
 * @author  Alphanumerix <>
 */
class CitizenStatsService
{
    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    // public function getTodayInfluence($citizenId)
    // {
    //     $lastDayChange = $this->container->get('date_time')->getDayChange();

    //     $citizen = $this->getEm()
    //         ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
    //         ->findOneByCitizenId($citizenId)
    //     ;

    //     $lastRankPointsChange = $this->getEm()
    //         ->getRepository('ChiaveErepublikScrobblerBundle:CitizenChange')
    //         ->createQueryBuilder('cc')
    //             ->where('cc.citizen = :citizen')
    //                 ->setParameter('citizen', $citizen)
    //             ->andWhere('cc.changedAt < :lasdDC')
    //                 ->setParameter('lasdDC', $lastDayChange)
    //             ->andWhere('cc.field = \'RankPoints\'')
    //             ->orderBy('cc.changedAt', 'DESC')
    //             ->setMaxResults(1)
    //             ->getQuery()
    //             ->getOneOrNullResult()
    //     ;

    //     $influence = 0;

    //     if ($lastRankPointsChange != null) {
    //         $oldRankPoints = $lastRankPointsChange->getValue();
    //         $currentRankPoints = $citizen->getRankPoints();

    //         if ($oldRankPoints && $currentRankPoints) {
    //             $RankPointsDifference = $currentRankPoints - $oldRankPoints;

    //             $influence = $RankPointsDifference*10;
    //         }
    //     }

    //     return new Response($influence);
    // }

    // //slow monster. Hopelly - not in use anymore :)
    // public function getInfluenceByDay($citizenId, $modify = 0)
    // {
    //     $dayChange = $this->container->get('date_time')->getDayChange($modify);

    //     // $dayChange->modify('+5 hours');

    //     // $dayChange = new \DateTime('now');
    //     // $dayChange->modify('-5 minutes');

    //     $citizen = $this->getEm()
    //         ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
    //         ->findOneByCitizenId($citizenId)
    //     ;

    //     $startRankPointsChange = $this->getEm()
    //         ->getRepository('ChiaveErepublikScrobblerBundle:CitizenChange')
    //         ->createQueryBuilder('cc')
    //             ->where('cc.citizen = :citizen')
    //                 ->setParameter('citizen', $citizen)
    //             ->andWhere('cc.changedAt < :dayChange')
    //                 ->setParameter('dayChange', $dayChange)
    //             ->andWhere('cc.field = \'RankPoints\'')
    //             ->orderBy('cc.changedAt', 'ASC')
    //             ->setMaxResults(1)
    //             ->getQuery()
    //             ->getOneOrNullResult()
    //     ;

    //     //for citizen that joined system today
    //     //  where stats are for today
    //      if ($startRankPointsChange == null && $modify == 0) {
    //         $startRankPointsChange = $this->getEm()
    //             ->getRepository('ChiaveErepublikScrobblerBundle:CitizenChange')
    //             ->createQueryBuilder('cc')
    //                 ->where('cc.citizen = :citizen')
    //                     ->setParameter('citizen', $citizen)
    //                 ->andWhere('cc.changedAt >= :dayChange')
    //                     ->setParameter('dayChange', $dayChange)
    //                 ->andWhere('cc.field = \'RankPoints\'')
    //                 ->orderBy('cc.changedAt', 'ASC')
    //                 ->setMaxResults(1)
    //                 ->getQuery()
    //                 ->getOneOrNullResult()
    //         ;
    //      }

    //     $endRankPointsChange = $this->getEm()
    //         ->getRepository('ChiaveErepublikScrobblerBundle:CitizenChange')
    //         ->createQueryBuilder('cc')
    //             ->where('cc.citizen = :citizen')
    //                 ->setParameter('citizen', $citizen)
    //             ->andWhere('cc.changedAt >= :dayChange')
    //                 ->setParameter('dayChange', $dayChange)
    //             ->andWhere('cc.field = \'RankPoints\'')
    //             ->orderBy('cc.changedAt', 'DESC')
    //             ->setMaxResults(1)
    //             ->getQuery()
    //             ->getOneOrNullResult()
    //     ;

    //     $influence = 0;

    //     // if ($startRankPointsChange != null)
    //     //     echo 'srp', $startRankPointsChange->getValue(), '<br />';
    //     // if ($endRankPointsChange != null)
    //     //     echo 'erp', $endRankPointsChange->getValue(), '<br />';

    //     // echo 'crp', $citizen->getRankPoints(), '<br />';

    //     if ($startRankPointsChange != null) {
    //         $startRankPoints = $startRankPointsChange->getValue();
    //         if($modify != 0 && $endRankPointsChange != null) {
    //             $endRankPoints = $endRankPointsChange->getValue();
    //         } else {
    //             $endRankPoints = $citizen->getRankPoints();
    //         }
    //         $rankPointsDifference = $endRankPoints - $startRankPoints;

    //         $influence = $rankPointsDifference*10;
    //     }

    //     return new Response($influence);
    // }

    private function getEm()
    {
        return $this->container
            ->get('doctrine_mongodb')
        ;
    }
}
