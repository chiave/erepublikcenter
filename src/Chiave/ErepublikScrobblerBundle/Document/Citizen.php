<?php

namespace Chiave\ErepublikScrobblerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document
 */
class Citizen
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\Int
     */
    private $citizenId;

    /**
     * @MongoDB\ReferenceMany(
     *  targetDocument="CitizenHistory",
     *  mappedBy="citizen",
     *  cascade="all",
     *  sort={"createdAt": "DESC"},
     *  simple=true
     * )
     */
    private $history;

    /**
     * @MongoDB\ReferenceMany(
     *   targetDocument="Chiave\StatsBundle\Document\RankingUser",
     *   mappedBy="citizen",
     *   cascade="all",
     *   sort={"createdAt": "DESC"}
     *  )
     */
    private $rankingUsers;

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;

    public function __construct()
    {
        $this->history = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->gethistory()->getNick();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set citizenId
     *
     * @param  integer $citizenId
     * @return User
     */
    public function setCitizenId($citizenId)
    {
        $this->citizenId = $citizenId;

        return $this;
    }

    /**
     * Get citizenId
     *
     * @return integer
     */
    public function getCitizenId()
    {
        return $this->citizenId;
    }

    /**
     * Add history
     *
     * @param  \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory $history
     * @return Citizen
     */
    public function addHistory(\Chiave\ErepublikScrobblerBundle\Document\CitizenHistory $history)
    {
        $this->history[] = $history;
        $history->setCitizen($this);

        return $this;
    }

    /**
     * Remove history
     *
     * @param \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory $history
     */
    public function removeHistory(\Chiave\ErepublikScrobblerBundle\Document\CitizenHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllHistory()
    {
        return $this->history;
    }

    /**
     * Get single history from $modifyDays backward
     *
     * @return \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory
     */
    public function getHistory($eDay = null)
    {
        if (!$eDay) {
            $date = new \DateTime('now');
            $erepZeroDay = new \DateTime('2007-11-20 9:00:00');
            $eDay = $date->diff($erepZeroDay)->format('%a');
        }

        $histories = $this->history->filter(
                function ($history) use ($eDay) {
            return $history->getEday() == $eDay;
        });

        if ($histories->isEmpty()) {
            return new CitizenHistory($this, $eDay);
        }

        return $histories->first();
    }

//    /**
//     * Get day from day $date
//     *
//     * @return \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory
//     */
//    public function getHistoryByDate($startDC = null, $modify = 1) {
////        if ($startDC == null) {
////            $startDC = new \DateTime('now');
////        }
////
////        if ($startDC->format('G') < 9) {
////            $startDC->modify('-1 day');
////        }
////
////        $startDC->setTime(9, 0);
////
////        $endDC = clone $startDC;
////        $endDC->modify("+$modify day");
//        $date = new \DateTime('now');
////        $date->modify("-$startDC days");
//
//        $erepZeroDay = new \DateTime('2007-11-20 9:00:00');
//        $erepDay = $date->diff($erepZeroDay)->format('%a');
//
//        $histories = $this->history->filter(
//                function($history) use ($erepDay) {
//            return $history->getEday() == $erepDay;
//        }
//        );
//
//        if ($histories->isEmpty()) {
//            return new \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory($this, $erepDay);
//        }
//
//        return $histories->last();
//    }

    /**
     * Add rankingUsers
     *
     * @param  \Chiave\StatsBundle\Document\RankingUser $rankingUsers
     * @return Citizen
     */
    public function addRankingUser(\Chiave\StatsBundle\Document\RankingUser $rankingUsers)
    {
        $this->rankingUsers[] = $rankingUsers;

        return $this;
    }

    /**
     * Remove rankingUsers
     *
     * @param \Chiave\StatsBundle\Document\RankingUser $rankingUsers
     */
    public function removeRankingUser(\Chiave\StatsBundle\Document\RankingUser $rankingUsers)
    {
        $this->rankingUsers->removeElement($rankingUsers);
    }

    /**
     * Get rankingUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRankingUsers()
    {
        return $this->rankingUsers;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Pages
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function setInitialTimestamps()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Set updatedAt
     *
     * @param  \DateTime $updatedAt
     * @return Pages
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @MongoDB\PreUpdate
     */
    public function setUpdatedTimestamps()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
