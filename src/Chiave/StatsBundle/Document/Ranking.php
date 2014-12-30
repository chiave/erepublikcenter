<?php

namespace Chiave\StatsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Ranking
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\ReferenceMany(
     *  targetDocument="RankingUser",
     *  mappedBy="ranking",
     *  cascade="all",
     *  sort={"createdAt": "desc"}
     * )
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rankingUsers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add rankingUsers
     *
     * @param  \Chiave\StatsBundle\Document\RankingUser $rankingUsers
     * @return Ranking
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
