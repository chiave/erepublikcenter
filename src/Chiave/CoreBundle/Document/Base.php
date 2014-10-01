<?php

namespace Chiave\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Base
 *
 * @MongoDB\Document(collection="base")
 * @MongoDB\HasLifecycleCallbacks
 * @MongoDB\MappedSuperclass
 */
class Base {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $createdAt;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $updatedAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
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
     * Get updatedAt
     *
     * @return date $updatedAt
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
