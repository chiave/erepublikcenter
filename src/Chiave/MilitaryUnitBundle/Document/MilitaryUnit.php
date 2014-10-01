<?php

namespace Chiave\MilitaryUnitBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document
 */
class MilitaryUnit {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\Int
     */
    private $unitId;

    /**
     * @MongoDB\String
     */
    private $name = 'EDIT ME';

    /**
     * @MongoDB\ReferenceMany(
     *   targetDocument="MilitaryUnitHistory",
     *   mappedBy="militaryUnit",
     *   cascade="all",
     *   sort={"createdAt": "DESC"}
     *  )
     */
    private $history;

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;

    public function __construct($unitId) {
        $this->unitId = $unitId;
        $this->history = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set unitId
     *
     * @param integer $unitId
     * @return MilitaryUnit
     */
    public function setUnitId($unitId) {
        $this->unitId = $unitId;

        return $this;
    }

    /**
     * Get unitId
     *
     * @return integer
     */
    public function getUnitId() {
        return $this->unitId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MilitaryUnit
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param \Chiave\MilitaryUnitBundle\Entity\MilitaryUnitHistory $history
     * @return MilitaryUnit
     */
    public function addHistory(MilitaryUnitHistory $history) {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \Chiave\MilitaryUnitBundle\Entity\MilitaryUnitHistory $history
     */
    public function removeHistory(MilitaryUnitHistory $history) {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllHistory() {
        return $this->history;
    }

    /**
     * Get single history
     *
     * @return \Chiave\MilitaryUnitBundle\Entity\MilitaryUnitHistory
     */
    public function getHistory($modifyDays = 0) {
        //same logic as in DateTimeService:getDayChange()
        $startDC = new \DateTime('now');
        $startDC->modify("-$modifyDays days");
        if ($startDC->format('G') < 9) {
            $startDC->modify('-1 day');
        }

        $startDC->setTime(9, 0);

        $endDC = clone $startDC;
        $endDC->modify('+1 day');

        $histories = $this->history->filter(
                function($history) use ($startDC, $endDC) {
            return $history->getCreatedAt() >= $startDC &&
                    $history->getCreatedAt() <= $endDC
            ;
        }
        );

        if ($histories->isEmpty()) {
            return new \Chiave\MilitaryUnitBundle\Document\MilitaryUnitHistory($this);
        }

        return $histories->first();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Pages
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function setInitialTimestamps() {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Pages
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @MongoDB\PreUpdate
     */
    public function setUpdatedTimestamps() {
        $this->updatedAt = new \DateTime('now');
    }

}
