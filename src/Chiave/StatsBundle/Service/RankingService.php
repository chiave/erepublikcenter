<?php

namespace Chiave\StatsBundle\Service;

use Chiave\StatsBundle\Entity\Ranking;
use Chiave\StatsBundle\Entity\RankingUser;

/**
 * class RankingService
 *
 * class description here
 *
 * @author  Alphanumerix <>
 */
class RankingService
{

    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function updateRankingUsers()
    {
        $lastRankingTime = $this->container->get('date_time')->getRankingTime();

        $em = $this->getEm();
        //find or create latest ranking
        $ranking = $em
            ->getRepository('ChiaveStatsBundle:Ranking')
            ->createQueryBuilder('r')
                ->where('r.createdAt >= :lastRankingTime')
                    ->setParameter('lastRankingTime', $lastRankingTime)
                ->orderBy('r.createdAt', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
        ;

        if ($ranking == null) {
            $ranking = new Ranking();
            $em->persist($ranking);
            $em->flush();
        }

        $timeMaster = $this->container->get('date_time');

        $startDate = $timeMaster->getDayChange(1);
        $endDate = $timeMaster->getDayChange();


        $query = $em
            ->getRepository('ChiaveErepublikScrobblerBundle:CitizenHistory')
            ->createQueryBuilder('ch')
            ->where('ch.createdAt >= :startDate')
            ->andWhere('ch.createdAt < :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
            ->andWhere('ch.egovBattles != 0')
            // ->orderBy('ch.nick', 'ASC')
            ;

        $histories = $query->getQuery()->getResult();

        foreach ($histories as $history) {
            $rankingUser = $em
                ->getRepository('ChiaveStatsBundle:RankingUser')
                ->createQueryBuilder('ru')
                    ->where('ru.createdAt >= :lastRankingTime')
                        ->setParameter('lastRankingTime', $lastRankingTime)
                    ->andWhere('ru.citizen = :citizen')
                        ->setParameter('citizen', $history->getCitizen())
                    ->orderBy('ru.nick', 'ASC')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult()
            ;

            if ($rankingUser == null) {
                $rankingUser = new RankingUser(
                    $history->getCitizen(),
                    $ranking
                );
            }

            $this->updateRankingUser($rankingUser, $history);
        }

        $em->persist($ranking);
        $em->flush();

        return $ranking;
    }

    public function updateRankingUser($rankingUser, $history) {

        //TODO: It can be almost real-time if connected
        //          with egovFetcher
        //TODO: Move EgovFetcher to separate bundle
        //          and probably create new entity - nationalRaport
        $rankingUser->setNick($history->getNick());
        $rankingUser->setEgovBattles(
            $rankingUser->getEgovBattles() + $history->getEgovBattles()
        );
        $rankingUser->setEgovHits(
            $rankingUser->getEgovHits() + $history->getEgovHits()
        );
        $rankingUser->setEgovInfluence(
            $rankingUser->getEgovInfluence() + $history->getEgovInfluence()
        );

        // $rankingUser->setAvatarUrl($history->getAvatarUrl());
        // $rankingUser->setExperience($history->getExperience());
        // $rankingUser->setStrength($history->getStrength());
        // $rankingUser->setRankPoints($history->getRankPoints());
        // $rankingUser->setTruePatriot($history->getTruePatriot());
        // $rankingUser->setEbirth($history->getEbirth());
        // $rankingUser->setCountry($history->getCountry());
        // $rankingUser->setRegion($history->getRegion());
        // $rankingUser->setCitizenship($history->getCitizenship());
        // $rankingUser->setNationalRank($history->getNationalRank());
        // $rankingUser->setPartyId($history->getPartyId());
        // $rankingUser->setPartyName($history->getPartyName());
        // $rankingUser->setMilitaryUnitId($history->getMilitaryUnitId());
        // $rankingUser->setMilitaryUnitName($history->getMilitaryUnitName());
        // $rankingUser->setAchievements($history->getAchievements());
        // $rankingUser->setSmallBombs($history->getSmallBombs());
        // $rankingUser->setBigBombs($history->getBigBombs());
        // $rankingUser->setLastUsedMsg($history->getLastUsedMsg());

        $em = $this->getEm();

        $em->persist($rankingUser);
        $em->flush();

        return $rankingUser;
    }

    private function getEm()
    {
        return $this->container
            ->get('doctrine_mongodb')
        ;
    }
}

