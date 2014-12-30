<?php

namespace Chiave\StatsBundle\Service;

use Chiave\ErepublikScrobblerBundle\Document\CitizenHistory;

/**
 * class CitizenInfluenceService
 *
 * class description here
 *
 * @author  Alphanumerix <>
 */
class CitizenInfluenceService
{
    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function update($citizen)
    {
        $dayChange = $this->container->get('date_time')->getDayChange();

        $em = $this->getEm();

        $currentHistory = $citizen->gethistory();
        $previousHistory = $citizen->gethistory(1);

        $endRankPoints = $citizen->getRankPoints();

        if ($previousHistory->getRankPoints() == 0) {
            $previousHistory->setRankPoints($endRankPoints);
        }

        $startRankPoints = $previousHistory->getRankPoints();

        //influence counting
        $rankPointsDifference = $endRankPoints - $startRankPoints;
        $influenceValue = $rankPointsDifference*10;

        $currentHistory->setInfluence($influenceValue);

        $em->persist($currentHistory);
        $em->persist($previousHistory);
        $em->flush();

        return $currentHistory;

        // $history = $em
        //     ->getRepository('ChiaveErepublikScrobblerBundle:CitizenHistory')
        //     ->createQueryBuilder('cih')
        //         ->where('cih.citizen = :citizen')
        //             ->setParameter('citizen', $citizen)
        //         ->andWhere('cih.createdAt >= :dayChange')
        //             ->setParameter('dayChange', $dayChange)
        //         ->setMaxResults(1)
        //         ->getQuery()
        //         ->getOneOrNullResult()
        // ;

        // if ($history == null) {
        //     $history = new CitizenHistory($citizen);
        // }

        // //last influence from day before
        // $rankPointsChange = $em
        //     ->getRepository('ChiaveErepublikScrobblerBundle:CitizenChange')
        //     ->createQueryBuilder('cc')
        //         ->where('cc.citizen = :citizen')
        //             ->setParameter('citizen', $citizen)
        //         ->andWhere('cc.changedAt < :dayChange')
        //             ->setParameter('dayChange', $dayChange)
        //         ->andWhere('cc.field = \'RankPoints\'')
        //         ->orderBy('cc.changedAt', 'DESC')
        //         ->setMaxResults(1)
        //         ->getQuery()
        //         ->getOneOrNullResult()
        // ;

        // //for citizen that joined system today
        // //  where stats are for today
        //  if ($rankPointsChange == null) {
        //     $rankPointsChange = $em
        //         ->getRepository('ChiaveErepublikScrobblerBundle:CitizenChange')
        //         ->createQueryBuilder('cc')
        //             ->where('cc.citizen = :citizen')
        //                 ->setParameter('citizen', $citizen)
        //             ->andWhere('cc.changedAt >= :dayChange')
        //                 ->setParameter('dayChange', $dayChange)
        //             ->andWhere('cc.field = \'RankPoints\'')
        //             ->orderBy('cc.changedAt', 'ASC')
        //             ->setMaxResults(1)
        //             ->getQuery()
        //             ->getOneOrNullResult()
        //     ;
        //  }

        // $influenceValue = 0;

        // if ($rankPointsChange != null) {
        //     $startRankPoints = $rankPointsChange->getValue();
        //     $endRankPoints = $citizen->getRankPoints();
        //     $rankPointsDifference = $endRankPoints - $startRankPoints;

        //     $influenceValue = $rankPointsDifference*10;
        // }

        // $history->setInfluence($influenceValue);

        // $em->persist($history);
        // $em->flush();

        return $history;
    }

    private function getEm()
    {
        return $this->container
            ->get('doctrine_mongodb')
        ;
    }
}
