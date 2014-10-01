<?php

namespace Chiave\ErepublikScrobblerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks
 */
class CitizenHistory {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    private $id;

    /**
     * @MongoDB\Int
     */
    private $eday;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Citizen", inversedBy="history", simple=true)
     */
    private $citizen;

    /**
     * @MongoDB\String
     */
    private $nick;

    /**
     * @MongoDB\String
     */
    private $avatarUrl;

    /**
     * @MongoDB\String
     */
    private $experience;

    /**
     * @MongoDB\Float
     */
    private $strength;

    /**
     * @MongoDB\String
     */
    private $rankPoints;

    /**
     * @MongoDB\String
     */
    private $truePatriot;

    /**
     * @MongoDB\Date
     */
    private $ebirth;

    /**
     * @MongoDB\String
     */
    private $country;

    /**
     * @MongoDB\String
     */
    private $region;

    /**
     * @MongoDB\String
     */
    private $citizenship;

    /**
     * @MongoDB\Int
     */
    private $nationalRank;

    /**
     * @MongoDB\Int
     */
    private $partyId;

    /**
     * @MongoDB\String
     */
    private $partyName;

    /**
     * @MongoDB\Int
     */
    private $militaryUnitId;

    /**
     * @MongoDB\String
     */
    private $militaryUnitName;


    // *
    //  * MongoDB\Collection
    // // private $achievements;

    /**
     * @MongoDB\Int
     */
    private $smallBombs = 0;

    /**
     * @MongoDB\Int
     */
    private $bigBombs = 0;

    /**
     * @MongoDB\String
     */
    private $lastUsedMsg = '';

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
     * @MongoDB\Int
     */
    private $dof = 0;

    //counted

    /**
     * @MongoDB\Int
     */
    private $division;

    /**
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @MongoDB\Date
     */
    private $updatedAt;

    public function __construct($citizen, $eday) {
        $this->citizen = $citizen;
        $this->eday = $eday;
    }

    public function __toString() {
        return $this->nick;
        // return $this->influence ? $this->influence : '0';
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
     *
     * @param integer $eday
     * @return
     */
    public function setEday($eday) {
        $this->eday = $eday;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEday() {
        return $this->eday;
    }

    /**
     * Set citizen
     *
     * @param \Chiave\ErepublikScrobblerBundle\Document\Citizen $citizen
     * @return CitizenHistory
     */
    public function setCitizen(\Chiave\ErepublikScrobblerBundle\Document\Citizen $citizen = null) {
        $this->citizen = $citizen;

        return $this;
    }

    /**
     * Get citizen
     *
     * @return \Chiave\ErepublikScrobblerBundle\Document\Citizen
     */
    public function getCitizen() {
        return $this->citizen;
    }

    /**
     * Set nick
     *
     * @param string $nick
     * @return Player
     */
    public function setNick($nick) {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick() {
        return $this->nick;
    }

    /**
     * Set avatarUrl
     *
     * @param string $avatarUrl
     * @return Player
     */
    public function setAvatarUrl($avatarUrl) {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Get avatarUrl
     *
     * @return string
     */
    public function getAvatarUrl() {
        return $this->avatarUrl;
    }

    /**
     * Get avatarUrl
     *
     * @return string
     */
    public function getLargeAvatarUrl() {
        return $this->avatarUrl;
    }

    /**
     * Get medium avatarUrl
     *
     * @return string
     */
    public function getMediumAvatarUrl() {
        $filename = $this->avatarUrl;
        $extension_pos = strrpos($filename, '.');

        return substr($filename, 0, $extension_pos) .
                '_142x142' . substr($filename, $extension_pos);
    }

    /**
     * Get small avatarUrl
     *
     * @return string
     */
    public function getSmallAvatarUrl() {
        $filename = $this->avatarUrl;
        $extension_pos = strrpos($filename, '.');

        return substr($filename, 0, $extension_pos) .
                '_55x55' . substr($filename, $extension_pos);
    }

    /**
     * Set experience
     *
     * @param string $experience
     * @return Player
     */
    public function setExperience($experience) {
        $this->experience = $experience;
        $this->division = $this->getDivision();

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience() {
        return $this->experience;
    }

    public function getLevel() {
        $levelData = $this->getLevelData($this->getExperience());

        return $levelData['level'];
    }

    public function getHp() {
        $levelData = $this->getLevelData($this->getExperience());

        return $levelData['hp'];
    }

    public function getDivision() {
        $levelData = $this->getLevelData($this->getExperience());

        return $this->countDivision($levelData['level']);
    }

    public function getDivisionText($mode = 'arabic') {
        return $this->romanNumerals($this->getDivision());
    }

    /**
     * Set strength
     *
     * @param integer $strength
     * @return Player
     */
    public function setStrength($strength) {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return integer
     */
    public function getStrength() {
        return $this->strength;
    }

    /**
     * Set rankPoints
     *
     * @param string $rankPoints
     * @return Player
     */
    public function setRankPoints($rankPoints) {
        $this->rankPoints = $rankPoints;

        return $this;
    }

    /**
     * Get rankPoints
     *
     * @return string
     */
    public function getRankPoints() {
        return $this->rankPoints;
    }

    public function getRankLevel() {
        $rankData = $this->getRankData($this->getRankPoints());

        return $rankData['level'];
    }

    public function getRankImageUrl() {
        $rankData = $this->getRankData($this->getRankPoints());

        return $rankData['image'];
    }

    public function getRankName() {
        $rankData = $this->getRankData($this->getRankPoints());

        return $rankData['name'];
    }

    /**
     * Set truePatriot
     *
     * @param string $truePatriot
     * @return CitizenHistory
     */
    public function setTruePatriot($truePatriot) {
        $this->truePatriot = $truePatriot;

        return $this;
    }

    /**
     * Get truePatriot
     *
     * @return string
     */
    public function getTruePatriot() {
        return $this->truePatriot;
    }

    /**
     * Set ebirth
     *
     * @param \DateTime $ebirth
     * @return CitizenHistory
     */
    public function setEbirth($ebirth) {
        $this->ebirth = $ebirth;

        return $this;
    }

    /**
     * Get ebirth
     *
     * @return \DateTime
     */
    public function getEbirth() {
        return $this->ebirth;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return CitizenHistory
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return CitizenHistory
     */
    public function setRegion($region) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set citizenship
     *
     * @param string $citizenship
     * @return CitizenHistory
     */
    public function setCitizenship($citizenship) {
        $this->citizenship = $citizenship;

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return string
     */
    public function getCitizenship() {
        return $this->citizenship;
    }

    /**
     * Set nationalRank
     *
     * @param integer $nationalRank
     * @return CitizenHistory
     */
    public function setNationalRank($nationalRank) {
        $this->nationalRank = $nationalRank;

        return $this;
    }

    /**
     * Get nationalRank
     *
     * @return integer
     */
    public function getNationalRank() {
        return $this->nationalRank;
    }

    /**
     * Set partyId
     *
     * @param integer $partyId
     * @return CitizenHistory
     */
    public function setPartyId($partyId) {
        $this->partyId = $partyId;

        return $this;
    }

    /**
     * Get partyId
     *
     * @return integer
     */
    public function getPartyId() {
        return $this->partyId;
    }

    /**
     * Set partyName
     *
     * @param string $partyName
     * @return CitizenHistory
     */
    public function setPartyName($partyName) {
        $this->partyName = $partyName;

        return $this;
    }

    /**
     * Get partyName
     *
     * @return string
     */
    public function getPartyName() {
        return $this->partyName;
    }

    /**
     * Set militaryUnitId
     *
     * @param integer $militaryUnitId
     * @return CitizenHistory
     */
    public function setMilitaryUnitId($militaryUnitId) {
        $this->militaryUnitId = $militaryUnitId;

        return $this;
    }

    /**
     * Get militaryUnitId
     *
     * @return integer
     */
    public function getMilitaryUnitId() {
        return $this->militaryUnitId;
    }

    /**
     * Set militaryUnitName
     *
     * @param string $militaryUnitName
     * @return CitizenHistory
     */
    public function setMilitaryUnitName($militaryUnitName) {
        $this->militaryUnitName = $militaryUnitName;

        return $this;
    }

    /**
     * Get militaryUnitName
     *
     * @return string
     */
    public function getMilitaryUnitName() {
        return $this->militaryUnitName;
    }

    /**
     * Set achievements
     *
     * @param array $achievements
     * @return CitizenHistory
     */
    public function setAchievements($achievements) {
        $this->achievements = $achievements;

        return $this;
    }

    /**
     * Get achievements
     *
     * @return array
     */
    public function getAchievements() {
        return $this->achievements;
    }

    /**
     * Set smallBombs
     *
     * @param integer $smallBombs
     * @return CitizenHistory
     */
    public function setSmallBombs($smallBombs) {
        $this->smallBombs = $smallBombs;

        return $this;
    }

    /**
     * Get smallBombs
     *
     * @return integer
     */
    public function getSmallBombs() {
        return $this->smallBombs;
    }

    /**
     * Set bigBombs
     *
     * @param integer $bigBombs
     * @return CitizenHistory
     */
    public function setBigBombs($bigBombs) {
        $this->bigBombs = $bigBombs;

        return $this;
    }

    /**
     * Get bigBombs
     *
     * @return integer
     */
    public function getBigBombs() {
        return $this->bigBombs;
    }

    /**
     * Set lastUsedMsg
     *
     * @param string $lastUsedMsg
     * @return CitizenHistory
     */
    public function setLastUsedMsg($lastUsedMsg) {
        $this->lastUsedMsg = $lastUsedMsg;

        return $this;
    }

    /**
     * Get lastUsedMsg
     *
     * @return string
     */
    public function getLastUsedMsg() {
        return $this->lastUsedMsg;
    }

    /**
     * Set egovBattles
     *
     * @param integer $egovBattles
     * @return CitizenHistory
     */
    public function setEgovBattles($egovBattles) {
        $this->egovBattles = $egovBattles;

        return $this;
    }

    /**
     * Get egovBattles
     *
     * @return integer
     */
    public function getEgovBattles() {
        return $this->egovBattles;
    }

    /**
     * Set egovHits
     *
     * @param integer $egovHits
     * @return CitizenHistory
     */
    public function setEgovHits($egovHits) {
        $this->egovHits = $egovHits;

        return $this;
    }

    /**
     * Get egovHits
     *
     * @return integer
     */
    public function getEgovHits() {
        return $this->egovHits;
    }

    /**
     * Set egovInfluence
     *
     * @param string $egovInfluence
     * @return CitizenHistory
     */
    public function setEgovInfluence($egovInfluence) {
        $this->egovInfluence = $egovInfluence;

        return $this;
    }

    /**
     * Get egovInfluence
     *
     * @return string
     */
    public function getEgovInfluence() {
        return $this->egovInfluence;
    }

    /**
     * Set dof
     *
     * @param smallint $dof
     * @return CitizenHistory
     */
    public function setDof($dof) {
        $this->dof = $dof;

        return $this;
    }

    /**
     * Get dof
     *
     * @return smallint
     */
    public function getDof() {
        return $this->dof;
    }

    /**
     * Is dof
     *
     * @return smallint
     */
    public function getDofText() {
        if ($this->dof == 1) {
            return 'wydany';
        } elseif ($this->dof == 0) {
            return 'niewydany';
        } elseif ($this->dof == -1) {
            return 'pominięty';
        } else {
            return 'stan nieznany';
        }
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

    public function getDate() {
        $date = $this->getCreatedAt();

        $erepZeroDay = new \DateTime('2007-11-20 9:00:00');
        $interval = $date->diff($erepZeroDay);

        return $interval->format('%a');
    }

    /**
     * Count hit
     *
     * @return integer
     */
    public function getHit($weaponsQuality = 7) {
        $hit = 10 *
                (1 + $this->getStrength() / 400) *
                (1 + $this->getRankLevel() / 5) *
                (1 + $this->getWeaponsFirePower($weaponsQuality) / 100)
        ;

        return round($hit);
    }

    /**
     * Count egovHit
     *
     * @return integer
     */
    public function getEgovQWeaponHit($weaponsQuality = 7) {
        $hit = $this->egovHits * $this->getHit($weaponsQuality);

        return round($hit);
    }

    /**
     * Count egovHit
     *
     * @return integer
     */
    public function getTanksToPay($dofAmount = 1.5) {
        return ($this->egovHits / 10) * $dofAmount;
    }

    /**
     * Count influence
     *
     * @return integer
     */
    public function getInfluence() {
        if ($this->getCreatedAt()) {
            $endRankPoints = $this->getRankPoints();
            $startRankPoints = $this->citizen
                    ->getHistoryByDate($this->getCreatedAt()->modify('-1 day'))
                    ->getRankPoints()
            ;

            return ($endRankPoints - $startRankPoints) * 10;
        }

        return 0;
    }

    private function countDivision($level) {
        if ($level >= 70) {
            return 4;
        } else if ($level >= 50) {
            return 3;
        } else if ($level >= 35) {
            return 2;
        }

        return 1;
    }

    private function romanNumerals($num) {
        $n = intval($num);
        $res = '';

        $roman_numerals = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1);

        foreach ($roman_numerals as $roman => $number) {
            $matches = intval($n / $number);
            $res .= str_repeat($roman, $matches);
            $n = $n % $number;
        }

        return $res;
    }

    private function getWeaponsFirePower($weaponsQuality) {
        $weaponsFirepowerArray = $this->getWeaponsFirepowerArray();

        return $weaponsFirepowerArray[$weaponsQuality];
    }

    private function getWeaponsFirepowerArray() {
        return array(
            1 => 20,
            2 => 40,
            3 => 60,
            4 => 80,
            5 => 100,
            6 => 120,
            7 => 200,
        );
    }

    private function getRankData($rankPoints) {
        $rankArray = $this->getRankArray();

        foreach ($rankArray as $key => $value) {
            if ($rankPoints >= $key) {
                return $value;
            }
        }

        //NOTICE: Just in case...
        return -1;
    }

    private function getRankArray() {
        return array(
            10000000000 => array('level' => 69, 'image' => 'http//wiki.erepublik.com/images/6/63/Icon_rank_Titan%2A%2A%2A.png', 'name' => '69. Titan***'),
            4000000000 => array('level' => 68, 'image' => 'http://wiki.erepublik.com/images/9/96/Icon_rank_Titan%2A%2A.png', 'name' => '68. Titan**'),
            2000000000 => array('level' => 67, 'image' => 'http://wiki.erepublik.com/images/9/94/Icon_rank_Titan%2A.png', 'name' => '67. Titan*'),
            1000000000 => array('level' => 66, 'image' => 'http://wiki.erepublik.com/images/a/a4/Icon_rank_Titan.png', 'name' => '66. Titan'),
            500000000 => array('level' => 65, 'image' => 'http://wiki.erepublik.com/images/9/9d/Icon_rank_God_of_War%2A%2A%2A.png', 'name' => '65. God_of_War***'),
            200000000 => array('level' => 64, 'image' => 'http://wiki.erepublik.com/images/1/18/Icon_rank_God_of_War%2A%2A.png', 'name' => '64. God_of_War**'),
            100000000 => array('level' => 63, 'image' => 'http://wiki.erepublik.com/images/b/bd/Icon_rank_God_of_War%2A.png', 'name' => '63. God_of_War*'),
            50000000 => array('level' => 62, 'image' => 'http://wiki.erepublik.com/images/f/f5/Icon_rank_God_of_War.png', 'name' => '62. God_of_War'),
            43000000 => array('level' => 61, 'image' => 'http://wiki.erepublik.com/images/5/5c/Icon_rank_Legendary_Force%2A%2A%2A.png', 'name' => '61. Legendary_Force***'),
            37000000 => array('level' => 60, 'image' => 'http://wiki.erepublik.com/images/f/fc/Icon_rank_Legendary_Force%2A%2A.png', 'name' => '60. Legendary_Force**'),
            31500000 => array('level' => 59, 'image' => 'http://wiki.erepublik.com/images/2/2c/Icon_rank_Legendary_Force%2A.png', 'name' => '59. Legendary_Force*'),
            26500000 => array('level' => 58, 'image' => 'http://wiki.erepublik.com/images/b/b5/Icon_rank_Legendary_Force.png', 'name' => '58. Legendary_Force'),
            22000000 => array('level' => 57, 'image' => 'http://wiki.erepublik.com/images/b/b2/Icon_rank_World_Class_Force%2A%2A%2A.png', 'name' => '57. World_Class_Force***'),
            18000000 => array('level' => 56, 'image' => 'http://wiki.erepublik.com/images/a/a1/Icon_rank_World_Class_Force%2A%2A.png', 'name' => '56. World_Class_Force**'),
            14500000 => array('level' => 55, 'image' => 'http://wiki.erepublik.com/images/3/39/Icon_rank_World_Class_Force%2A.png', 'name' => '55. World_Class_Force*'),
            11500000 => array('level' => 54, 'image' => 'http://wiki.erepublik.com/images/d/de/Icon_rank_World_Class_Force.png', 'name' => '54. World_Class_Force'),
            9000000 => array('level' => 53, 'image' => 'http://wiki.erepublik.com/images/9/98/Icon_rank_National_Force%2A%2A%2A.png', 'name' => '53. National_Force***'),
            7000000 => array('level' => 52, 'image' => 'http://wiki.erepublik.com/images/8/84/Icon_rank_National_Force%2A%2A.png', 'name' => '52. National_Force**'),
            5800000 => array('level' => 51, 'image' => 'http://wiki.erepublik.com/images/6/6c/Icon_rank_National_Force%2A.png', 'name' => '51. National_Force*'),
            4900000 => array('level' => 50, 'image' => 'http://wiki.erepublik.com/images/0/0a/Icon_rank_National_Force.png', 'name' => '50. National_Force'),
            4150000 => array('level' => 49, 'image' => 'http://wiki.erepublik.com/images/0/06/Icon_rank_Supreme_Marshal%2A%2A%2A.png', 'name' => '49. Supreme_Marshal***'),
            3500000 => array('level' => 48, 'image' => 'http://wiki.erepublik.com/images/4/4b/Icon_rank_Supreme_Marshal%2A%2A.png', 'name' => '48. Supreme_Marshal**'),
            3000000 => array('level' => 47, 'image' => 'http://wiki.erepublik.com/images/8/86/Icon_rank_Supreme_Marshal%2A.png', 'name' => '47. Supreme_Marshal*'),
            2550000 => array('level' => 46, 'image' => 'http://wiki.erepublik.com/images/a/ab/Icon_rank_Supreme_Marshal.png', 'name' => '46. Supreme_Marshal'),
            2185000 => array('level' => 45, 'image' => 'http://wiki.erepublik.com/images/3/30/Icon_rank_Field_Marshal%2A%2A%2A.png', 'name' => '45. Field_Marshal***'),
            1875000 => array('level' => 44, 'image' => 'http://wiki.erepublik.com/images/5/59/Icon_rank_Field_Marshal%2A%2A.png', 'name' => '44. Field_Marshal**'),
            1600000 => array('level' => 43, 'image' => 'http://wiki.erepublik.com/images/7/7e/Icon_rank_Field_Marshal%2A.png', 'name' => '43. Field_Marshal*'),
            1350000 => array('level' => 42, 'image' => 'http://wiki.erepublik.com/images/b/bf/Icon_rank_Field_Marshal.png', 'name' => '42. Field_Marshal'),
            1140000 => array('level' => 41, 'image' => 'http://wiki.erepublik.com/images/9/92/Icon_rank_General%2A%2A%2A.png', 'name' => '41. General***'),
            950000 => array('level' => 40, 'image' => 'http://wiki.erepublik.com/images/6/68/Icon_rank_General%2A%2A.png', 'name' => '40. General**'),
            800000 => array('level' => 39, 'image' => 'http://wiki.erepublik.com/images/6/6d/Icon_rank_General%2A.png', 'name' => '39. General*'),
            660000 => array('level' => 38, 'image' => 'http://wiki.erepublik.com/images/7/75/Icon_rank_General.png', 'name' => '38. General'),
            540000 => array('level' => 37, 'image' => 'http://wiki.erepublik.com/images/4/42/Icon_rank_Colonel%2A%2A%2A.png', 'name' => '37. Colonel***'),
            435000 => array('level' => 36, 'image' => 'http://wiki.erepublik.com/images/d/d4/Icon_rank_Colonel%2A%2A.png', 'name' => '36. Colonel**'),
            355000 => array('level' => 35, 'image' => 'http://wiki.erepublik.com/images/f/f7/Icon_rank_Colonel%2A.png', 'name' => '35. Colonel*'),
            285000 => array('level' => 34, 'image' => 'http://wiki.erepublik.com/images/a/ad/Icon_rank_Colonel.png', 'name' => '34. Colonel'),
            225000 => array('level' => 33, 'image' => 'http://wiki.erepublik.com/images/5/5d/Icon_rank_Lt_Colonel%2A%2A%2A.png', 'name' => '33. Lt_Colonel***'),
            180000 => array('level' => 32, 'image' => 'http://wiki.erepublik.com/images/2/2b/Icon_rank_Lt_Colonel%2A%2A.png', 'name' => '32. Lt_Colonel**'),
            140000 => array('level' => 31, 'image' => 'http://wiki.erepublik.com/images/e/e3/Icon_rank_Lt_Colonel%2A.png', 'name' => '31. Lt_Colonel*'),
            110000 => array('level' => 30, 'image' => 'http://wiki.erepublik.com/images/0/06/Icon_rank_Lt_Colonel.png', 'name' => '30. Lt_Colonel'),
            85000 => array('level' => 29, 'image' => 'http://wiki.erepublik.com/images/5/55/Icon_rank_Commander%2A%2A%2A.png', 'name' => '29. Commander***'),
            67000 => array('level' => 28, 'image' => 'http://wiki.erepublik.com/images/8/89/Icon_rank_Commander%2A%2A.png', 'name' => '28. Commander**'),
            52000 => array('level' => 27, 'image' => 'http://wiki.erepublik.com/images/2/22/Icon_rank_Commander%2A.png', 'name' => '27. Commander*'),
            40000 => array('level' => 26, 'image' => 'http://wiki.erepublik.com/images/a/ad/Icon_rank_Commander.png', 'name' => '26. Commander'),
            31000 => array('level' => 25, 'image' => 'http://wiki.erepublik.com/images/b/bf/Icon_rank_Major%2A%2A%2A.png', 'name' => '25. Major***'),
            25000 => array('level' => 24, 'image' => 'http://wiki.erepublik.com/images/9/99/Icon_rank_Major%2A%2A.png', 'name' => '24. Major**'),
            20000 => array('level' => 23, 'image' => 'http://wiki.erepublik.com/images/a/ae/Icon_rank_Major%2A.png', 'name' => '23. Major*'),
            15500 => array('level' => 22, 'image' => 'http://wiki.erepublik.com/images/e/e3/Icon_rank_Major.png', 'name' => '22. Major'),
            12000 => array('level' => 21, 'image' => 'http://wiki.erepublik.com/images/c/c2/Icon_rank_Captain%2A%2A%2A.png', 'name' => '21. Captain***'),
            9000 => array('level' => 20, 'image' => 'http://wiki.erepublik.com/images/3/36/Icon_rank_Captain%2A%2A.png', 'name' => '20. Captain**'),
            6500 => array('level' => 19, 'image' => 'http://wiki.erepublik.com/images/1/12/Icon_rank_Captain%2A.png', 'name' => '19. Captain*'),
            5000 => array('level' => 18, 'image' => 'http://wiki.erepublik.com/images/3/33/Icon_rank_Captain.png', 'name' => '18. Captain'),
            3750 => array('level' => 17, 'image' => 'http://wiki.erepublik.com/images/d/dd/Icon_rank_Lieutenant%2A%2A%2A.png', 'name' => '17. Lieutenant***'),
            3000 => array('level' => 16, 'image' => 'http://wiki.erepublik.com/images/f/f3/Icon_rank_Lieutenant%2A%2A.png', 'name' => '16. Lieutenant**'),
            2350 => array('level' => 15, 'image' => 'http://wiki.erepublik.com/images/7/73/Icon_rank_Lieutenant%2A.png', 'name' => '15. Lieutenant*'),
            1850 => array('level' => 14, 'image' => 'http://wiki.erepublik.com/images/5/56/Icon_rank_Lieutenant.png', 'name' => '14. Lieutenant'),
            1400 => array('level' => 13, 'image' => 'http://wiki.erepublik.com/images/0/03/Icon_rank_Sergeant%2A%2A%2A.png', 'name' => '13. Sergeant***'),
            1000 => array('level' => 12, 'image' => 'http://wiki.erepublik.com/images/4/45/Icon_rank_Sergeant%2A%2A.png', 'name' => '12. Sergeant**'),
            800 => array('level' => 11, 'image' => 'http://wiki.erepublik.com/images/f/fc/Icon_rank_Sergeant%2A.png', 'name' => '11. Sergeant*'),
            600 => array('level' => 10, 'image' => 'http://wiki.erepublik.com/images/a/a1/Icon_rank_Sergeant.png', 'name' => '10. Sergeant'),
            450 => array('level' => 9, 'image' => 'http://wiki.erepublik.com/images/9/98/Icon_rank_Corporal%2A%2A%2A.png', 'name' => '9. Corporal***'),
            350 => array('level' => 8, 'image' => 'http://wiki.erepublik.com/images/4/4b/Icon_rank_Corporal%2A%2A.png', 'name' => '8. Corporal**'),
            250 => array('level' => 7, 'image' => 'http://wiki.erepublik.com/images/3/31/Icon_rank_Corporal%2A.png', 'name' => '7. Corporal*'),
            170 => array('level' => 6, 'image' => 'http://wiki.erepublik.com/images/8/8b/Icon_rank_Corporal.png', 'name' => '6. Corporal'),
            120 => array('level' => 5, 'image' => 'http://wiki.erepublik.com/images/7/74/Icon_rank_Private%2A%2A%2A.png', 'name' => '5. Private***'),
            80 => array('level' => 4, 'image' => 'http://wiki.erepublik.com/images/e/e3/Icon_rank_Private%2A%2A.png', 'name' => '4. Private**'),
            45 => array('level' => 3, 'image' => 'http://wiki.erepublik.com/images/f/f1/Icon_rank_Private*.png', 'name' => '3. Private*'),
            15 => array('level' => 2, 'image' => 'http://wiki.erepublik.com/images/4/4c/Icon_rank_Private.png', 'name' => '2. Private'),
            0 => array('level' => 1, 'image' => 'http://wiki.erepublik.com/images/2/20/Icon_rank_Recruit.png', 'name' => '1. Recruit'),
                )
        ;
    }

    private function getLevelData($experience) {
        if ($experience >= 15000) {
            $level = ((int) ($experience / 5000)) + 25;
            return array('level' => $level, 'hp' => 500);
        } else {
            $levelArray = $this->getLevelArray();
            foreach ($levelArray as $key => $value) {
                if ($experience >= $key) {
                    return $value;
                }
            }
        }
    }

    private function getLevelArray() {
        return array(
            10000 => array('level' => 27, 'hp' => 500),
            7000 => array('level' => 26, 'hp' => 500),
            5000 => array('level' => 25, 'hp' => 500),
            3000 => array('level' => 24, 'hp' => 500),
            2000 => array('level' => 23, 'hp' => 500),
            1500 => array('level' => 22, 'hp' => 500),
            1000 => array('level' => 21, 'hp' => 500),
            500 => array('level' => 20, 'hp' => 480),
            450 => array('level' => 19, 'hp' => 460),
            410 => array('level' => 18, 'hp' => 440),
            370 => array('level' => 17, 'hp' => 420),
            335 => array('level' => 16, 'hp' => 400),
            300 => array('level' => 15, 'hp' => 380),
            270 => array('level' => 14, 'hp' => 360),
            240 => array('level' => 13, 'hp' => 340),
            210 => array('level' => 12, 'hp' => 320),
            180 => array('level' => 11, 'hp' => 300),
            150 => array('level' => 10, 'hp' => 280),
            130 => array('level' => 9, 'hp' => 260),
            110 => array('level' => 8, 'hp' => 240),
            90 => array('level' => 7, 'hp' => 220),
            70 => array('level' => 6, 'hp' => 200),
            50 => array('level' => 5, 'hp' => 180),
            35 => array('level' => 4, 'hp' => 160),
            20 => array('level' => 3, 'hp' => 140),
            10 => array('level' => 2, 'hp' => 120),
            0 => array('level' => 1, 'hp' => 100),
                )
        ;
    }

}
