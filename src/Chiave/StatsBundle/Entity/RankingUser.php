<?php

namespace Chiave\StatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RankingUser
 *
 * @ORM\Table(name="ranking_user")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class RankingUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ranking", inversedBy="rankingUsers")
     * @ORM\JoinColumn(name="ranking_id", referencedColumnName="id")
     **/
    private $ranking;

    /**
     * @ORM\ManyToOne(targetEntity="Chiave\ErepublikScrobblerBundle\Entity\Citizen", inversedBy="rankingUsers")
     * @ORM\JoinColumn(name="citizen_id", referencedColumnName="id")
     **/
    private $citizen;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=64)
     */
    private $nick;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $egovBattles = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $egovHits = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1024)
     */
    private $egovInfluence = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;


    public function __construct($citizen, $ranking) {
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
     * @param string $nick
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
     * @param integer $egovBattles
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
     * @param integer $egovHits
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
     * @param string $egovInfluence
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
     * @param \Chiave\StatsBundle\Entity\Ranking $ranking
     * @return RankingUser
     */
    public function setRanking(\Chiave\StatsBundle\Entity\Ranking $ranking = null)
    {
        $this->ranking = $ranking;

        return $this;
    }

    /**
     * Get ranking
     *
     * @return \Chiave\StatsBundle\Entity\Ranking
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set citizen
     *
     * @param \Chiave\ErepublikScrobblerBundle\Entity\Citizen $citizen
     * @return RankingUser
     */
    public function setCitizen(\Chiave\ErepublikScrobblerBundle\Entity\Citizen $citizen = null)
    {
        $this->citizen = $citizen;

        return $this;
    }

    /**
     * Get citizen
     *
     * @return \Chiave\ErepublikScrobblerBundle\Entity\Citizen
     */
    public function getCitizen()
    {
        return $this->citizen;
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
     * @ORM\PrePersist
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
     * @ORM\PreUpdate
     */
    public function setUpdatedTimestamps()
    {
        $this->updatedAt = new \DateTime('now');
    }
}
