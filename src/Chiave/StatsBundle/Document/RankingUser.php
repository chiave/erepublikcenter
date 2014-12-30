<?php

namespace Chiave\StatsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class RankingUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\ReferenceMany(
     *  targetDocument="Ranking",
     *  inversedBy="ranking_id"
     * )
     */
    private $ranking;

    /**
     * @MongoDB\ReferenceMany(
     *  targetDocument="Chiave\ErepublikScrobblerBundle\Document\Citizen",
     *  inversedBy="ranking_id"
     * )
     */
    private $citizen;

    /**
     * @MongoDB\String
     */
    private $nick;

    /**
     * @MongoDB\Int
     */
    private $egovBattles = 0;

    /**
     * @MongoDB\Int
     */
    private $egovHits = 0;

    /**
     * @MongoDB\String
     */
    private $egovInfluence = '0';

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;

    public function __construct($citizen, $ranking)
    {
        $this->citizen = $citizen;
        $this->ranking = $ranking;
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
     * Set nick
     *
     * @param  string      $nick
     * @return RankingUser
     */
    public function setNick($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Set egovBattles
     *
     * @param  integer     $egovBattles
     * @return RankingUser
     */
    public function setEgovBattles($egovBattles)
    {
        $this->egovBattles = $egovBattles;

        return $this;
    }

    /**
     * Get egovBattles
     *
     * @return integer
     */
    public function getEgovBattles()
    {
        return $this->egovBattles;
    }

    /**
     * Set egovHits
     *
     * @param  integer     $egovHits
     * @return RankingUser
     */
    public function setEgovHits($egovHits)
    {
        $this->egovHits = $egovHits;

        return $this;
    }

    /**
     * Get egovHits
     *
     * @return integer
     */
    public function getEgovHits()
    {
        return $this->egovHits;
    }

    /**
     * Set egovInfluence
     *
     * @param  string      $egovInfluence
     * @return RankingUser
     */
    public function setEgovInfluence($egovInfluence)
    {
        $this->egovInfluence = $egovInfluence;

        return $this;
    }

    /**
     * Get egovInfluence
     *
     * @return string
     */
    public function getEgovInfluence()
    {
        return $this->egovInfluence;
    }

    /**
     * Set ranking
     *
     * @param  \Chiave\StatsBundle\Document\Ranking $ranking
     * @return RankingUser
     */
    public function setRanking(Ranking $ranking = null)
    {
        $this->ranking = $ranking;

        return $this;
    }

    /**
     * Get ranking
     *
     * @return \Chiave\StatsBundle\Document\Ranking
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set citizen
     *
     * @param  \Chiave\ErepublikScrobblerBundle\Document\Citizen $citizen
     * @return RankingUser
     */
    public function setCitizen(\Chiave\ErepublikScrobblerBundle\Document\Citizen $citizen = null)
    {
        $this->citizen = $citizen;

        return $this;
    }

    /**
     * Get citizen
     *
     * @return \Chiave\ErepublikScrobblerBundle\Document\Citizen
     */
    public function getCitizen()
    {
        return $this->citizen;
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
