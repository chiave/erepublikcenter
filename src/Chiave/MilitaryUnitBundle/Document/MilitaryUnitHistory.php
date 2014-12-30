<?php

namespace Chiave\MilitaryUnitBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class MilitaryUnitHistory
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="MilitaryUnit", inversedBy="history")
     * */
    private $militaryUnit;

    /**
     * @MongoDB\Int
     */
    private $battles;

    /**
     * @MongoDB\Int
     */
    private $hits;

    /**
     * @MongoDB\String
     */
    private $influence = 0;

    /**
     * @MongoDB\Int
     */
    private $soldiers;

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;

    public function __construct($militaryUnit)
    {
        $this->militaryUnit = $militaryUnit;
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
     * Set militaryUnit
     *
     * @param  \Chiave\MilitaryUnitBundle\Document\MilitaryUnit $militaryUnit
     * @return MilitaryUnitHistory
     */
    public function setMilitaryUnit(MilitaryUnit $militaryUnit = null)
    {
        $this->militaryUnit = $militaryUnit;

        return $this;
    }

    /**
     * Get militaryUnit
     *
     * @return \Chiave\MilitaryUnitBundle\Document\MilitaryUnit
     */
    public function getMilitaryUnit()
    {
        return $this->militaryUnit;
    }

    /**
     * Set battles
     *
     * @param  integer             $battles
     * @return MilitaryUnitHistory
     */
    public function setBattles($battles)
    {
        $this->battles = $battles;

        return $this;
    }

    /**
     * Get battles
     *
     * @return integer
     */
    public function getBattles()
    {
        return $this->battles;
    }

    /**
     * Set hits
     *
     * @param  integer             $hits
     * @return MilitaryUnitHistory
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set influence
     *
     * @param  string              $influence
     * @return MilitaryUnitHistory
     */
    public function setInfluence($influence)
    {
        $this->influence = $influence;

        return $this;
    }

    /**
     * Get influence
     *
     * @return string
     */
    public function getInfluence()
    {
        return $this->influence;
    }

    /**
     * Set soldiers
     *
     * @param  integer             $soldiers
     * @return MilitaryUnitHistory
     */
    public function setSoldiers($soldiers)
    {
        $this->soldiers = $soldiers;

        return $this;
    }

    /**
     * Get soldiers
     *
     * @return integer
     */
    public function getSoldiers()
    {
        return $this->soldiers;
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
