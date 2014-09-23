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
     *  sort={"createdAt": "DESC"}
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


    public function __construct() {
        $this->history = new ArrayCollection();
    }

    public function __toString() {
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
     * @param integer $citizenId
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
     * @param \Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory $history
     * @return Citizen
     */
    public function addHistory(\Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory $history)
    {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory $history
     */
    public function removeHistory(\Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory $history)
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
     * @return \Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory
     */
    public function getHistory($modifyDays = 0)
    {
        $dayChange = new \DateTime('now');
        $dayChange->modify("-$modifyDays days");

        return $this->getHistoryByDate($dayChange);
    }

    /**
     * Get day from day $date
     *
     * @return \Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory
     */
    public function getHistoryByDate($startDC = null, $modify = 1)
    {
        if($startDC == null) {
            $startDC = new \DateTime('now');
        }

            if($startDC->format('G') < 9) {
                $startDC->modify('-1 day');
            }

            $startDC->setTime(9, 0);

        $endDC = clone $startDC;
        $endDC->modify("+$modify day");

        $histories = $this->history->filter(
            function($history) use ($startDC, $endDC) {
                return $history->getCreatedAt() >= $startDC &&
                    $history->getCreatedAt() <= $endDC
                ;
            }
        );

        if ($histories->isEmpty()) {
            return new \Chiave\ErepublikScrobblerBundle\Entity\CitizenHistory($this);
        }

        return $histories->last();
    }

    /**
     * Add rankingUsers
     *
     * @param \Chiave\StatsBundle\Entity\RankingUser $rankingUsers
     * @return Citizen
     */
    public function addRankingUser(\Chiave\StatsBundle\Entity\RankingUser $rankingUsers)
    {
        $this->rankingUsers[] = $rankingUsers;

        return $this;
    }

    /**
     * Remove rankingUsers
     *
     * @param \Chiave\StatsBundle\Entity\RankingUser $rankingUsers
     */
    public function removeRankingUser(\Chiave\StatsBundle\Entity\RankingUser $rankingUsers)
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
     * @param \DateTime $createdAt
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
     * @param \DateTime $updatedAt
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
