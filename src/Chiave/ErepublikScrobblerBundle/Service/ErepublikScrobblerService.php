<?php

namespace Chiave\ErepublikScrobblerBundle\Service;

use Chiave\ErepublikScrobblerBundle\Libraries\CurlUtils;
use Chiave\ErepublikScrobblerBundle\Document\Citizen;
use Chiave\ErepublikScrobblerBundle\Document\CitizenChange;

/**
 * class ErepublikScrobblerService
 *
 * class description here
 *
 * @author  Sowx <>
 * @author  Alphanumerix <>
 */
class ErepublikScrobblerService extends CurlUtils {

    protected $container;

    public function setContainer($container) {
        $this->container = $container;
    }

    CONST URL_PROFILE = 'http://www.erepublik.com/en/citizen/profile/';

    private $_xpath;
    private $_id;

    public function showRawData($id) {
        $this->_prepare($id);

        if ($this->citizenExists()) {
            echo '<br>nick: ' . $this->getName();
            echo '<br>avatar url: ' . $this->getAvatar();
            echo '<br>avatar large url: ' . $this->getAvatar('large');
            echo '<br>avatar medium url: ' . $this->getAvatar('medium');
            echo '<br>avatar small url: ' . $this->getAvatar('small');
            echo '<br>lvl: ' . $this->getLvl();
            echo '<br>exp: ' . $this->getExp();
            echo '<br>str: ' . $this->getStr();
            echo '<br>rank: ' . $this->getRank();
            echo '<br>rank points: ' . $this->getRankPoints();
            echo '<br>rank image url: ' . $this->getRankImage();
            echo '<br>tp: ' . $this->getTruePatriot();
            echo '<br>';
            echo '<br>eUrodziny: ' . $this->getEBirthday()->format('Y-m-d');
            echo '<br>panstwo: ' . $this->getCountry();
            echo '<br>region: ' . $this->getRegion();
            echo '<br>obywatelstwo: ' . $this->getCitizenship();

            echo '<br>national rank: ' . $this->getNationalRank();
            echo '<br>';
            echo '<br>partia: ' . $this->getParty();
            echo '<br>id partii: ' . $this->getPartyId();
            echo '<br>mu: ' . $this->getMilitaryUnit();
            echo '<br>id mu: ' . $this->getMilitaryUnitId();
            echo '<br>';
            echo '<br>';
            var_dump($this->getMedals());
            echo '<br>';
            echo '<br>';
            echo '<br>small bombs: ' . $this->getSmallBombs();
            echo '<br>big bombs: ' . $this->getBigBombs();
            echo '<br>last used: ' . $this->getLastUsedMsg();
        } else {
            echo '<br />User o podanym ID prawdopodobnie nie istnieje.';
        }
    }

    public function updateCitizens() {


        $em = $this->getEm();

        $citizens = $em
                ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
                ->findAll()
        ;

        foreach ($citizens as $citizen) {
            $this->updateCitizenHistory($citizen);
        }
    }

    public function updateCitizen($citizenInGameId) {

        $em = $this->getEm();

        $citizen = $em
                ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
                ->findOneByCitizenId($citizenInGameId)
        ;


        if ($citizen == null) {
            echo '<error>There is no citizen in the system with such id!<error>';
            return -1;
        }

        $this->updateCitizenHistory($citizen);

        return $citizen;
    }

    public function updateCitizenHistory($citizen) {
        $this->_prepare($citizen->getCitizenId());

        if (!$this->citizenExists()) {
            return null;
        }

        $history = $citizen->getHistory();

        $history->setNick($this->getName());
        $history->setAvatarUrl($this->getAvatar());
        $history->setExperience($this->getExp());
        $history->setStrength($this->getStr());
        $history->setRankPoints($this->getRankPoints());
        $history->setTruePatriot($this->getTruePatriot());
        $history->setEbirth($this->getEBirthday());
        $history->setCountry($this->getCountry());
        $history->setRegion($this->getRegion());
        $history->setCitizenship($this->getCitizenship());
        $history->setNationalRank($this->getNationalRank());
        $history->setPartyId($this->getPartyId());
        $history->setPartyName($this->getParty());
        $history->setMilitaryUnitId($this->getMilitaryUnitId());
        $history->setMilitaryUnitName($this->getMilitaryUnit());
        // $history->setAchievements($this->getMedals());
        $history->setSmallBombs($this->getSmallBombs());
        $history->setBigBombs($this->getBigBombs());
        $history->setLastUsedMsg($this->getLastUsedMsg());

        $em = $this->getEm();

        // TODO

        if (!$citizen->getAllHistory()->count()) {
            $yestereday = $history->getEday() - 1;
            $zeroHistory = clone $history;
            $zeroHistory->setEday($yestereday);
            $em->persist($zeroHistory);
        }

        $em->persist($history);
        $em->flush();

        if (!$citizen->getAllHistory()->count()) {
            $zeroHistory->setCreatedAt($history->getCreatedAt()->modify('-1 day'));
            $em->flush();
        }

        return $history;
    }

    public function citizenExists() {
        $query = $this->_xpath->query("//div[@class='citizen_profile_header']/h2");

        if ($query && $query->item(0) && $query->item(0)->nodeValue) {
            return true;
        }

        return false;
    }

    public function getId() {
        return $this->_id;
    }

    public function getName() {
        return trim($this->_xpath->query("//div[@class='citizen_profile_header']/h2")
                        ->item(0)->nodeValue
        );
    }

    public function getAvatar($size = 'large') {
        return trim($this->_getImage(
                        $this->_xpath->query("//img[@class='citizen_avatar']/@style")
                                ->item(0)->nodeValue, $size
        ));
    }

    public function getLvl() {
        return trim($this->_xpath->query("//div[@class='citizen_experience']/strong[@class='citizen_level']")
                        ->item(0)->nodeValue
        );
    }

    public function getExp() {
        return trim($this->_formatNumber($this->_getBeforeSlash(
                                $this->_xpath->query("//div[@class='citizen_experience']/div/p")
                                        ->item(0)->nodeValue
        )));
    }

    public function getStr() {
        return trim($this->_formatNumber(
                        $this->_xpath->query("//div[@class='citizen_military'][1]/h4")
                                ->item(0)->nodeValue
        ));
    }

    public function getRank() {
        return trim($this->_xpath->query("//div[@class='citizen_military'][2]/h4/a")
                        ->item(0)->nodeValue
        );
    }

    public function getRankImage() {
        return trim($this->_xpath->query("//div[@class='citizen_military'][2]/h4/img/@src")
                        ->item(0)->nodeValue
        );
    }

    public function getRankPoints() {
        return trim($this->_formatNumber($this->_getBeforeSlash(
                                $this->_xpath->query("//div[@class='citizen_military'][2]/div[@class='stat']/small[2]/strong")
                                        ->item(0)->nodeValue
        )));
    }

    public function getTruePatriot() {
        $query = $this->_xpath->query("//div[@class='citizen_military'][3]/div[@class='stat']/small[2]/strong")
                ->item(0)
        ;

        if ($query) {
            return $this->_formatNumber(
                            $this->_getBeforeSlash(
                                    $query->nodeValue
            ));
        }

        return null;
    }

    public function getEBirthday() {
        $eBirthday = trim($this->_xpath->query("//div[@class='citizen_info']/div[@class='citizen_second']/p[2]")
                        ->item(0)->nodeValue);
        $dt = new \DateTime();
        $dt = $dt->createFromFormat('M d, Y', $eBirthday);
        return $dt;
    }

    public function getCountry() {
        return trim($this->_xpath->query("//div[@class='citizen_sidebar']/div[@class='citizen_info']/a[1]")
                        ->item(0)->nodeValue
        );
    }

    public function getRegion() {
        return trim($this->_xpath->query("//div[@class='citizen_sidebar']/div[@class='citizen_info']/a[2]")
                        ->item(0)->nodeValue
        );
    }

    public function getCitizenship() {
        return trim($this->_xpath->query("//div[@class='citizen_sidebar']/div[@class='citizen_info']/a[3]")
                        ->item(0)->nodeValue
        );
    }

    public function getNationalRank() {
        return trim($this->_xpath->query("//div[@class='citizen_second']/small[3]/strong")
                        ->item(0)->nodeValue
        );
    }

    public function getParty() {
        $partyString = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][1]/div/span/a")
                ->item(0)
        ;

        if ($partyString) {
            return trim($partyString->nodeValue);
        }

        return null;
    }

    public function getPartyId() {
        $partyString = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][1]/div/span/a/@href")
                ->item(0)
        ;

        if ($partyString) {
            $party = $partyString->nodeValue;
            preg_match('/(\d+)/', $party, $id);
            return $id[1];
        }

        return null;
    }

    public function getMilitaryUnit() {
        $mu = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][2]/div[@class='one_newspaper']/a")
                ->item(0)
        ;

        if ($mu) {
            return trim($mu->nodeValue);
        }

        return null;
    }

    public function getMilitaryUnitId() {
        $mu = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][2]/div[@class='one_newspaper']/a/@href")->item(0);

        if ($mu) {
            $result = $mu->nodeValue;
            $id = explode('/', $result);
            return end($id);
        }

        return null;
    }

    public function getMedals() {
        $allMedals = $this->_xpath->query("//ul[@id='achievment']/li");
        $medals = array();
        foreach ($allMedals as $medal) {
            $type = $this->_xpath->query(".//span/p/strong", $medal)
                            ->item(0)->nodeValue;
            $amount = $this->_xpath->query(".//div[@class='counter']", $medal);
            $medals[$type] = ($amount->length > 0 ? (int) $amount->item(0)->nodeValue : 0);
        }
        return $medals;
    }

    public function getSmallBombs() {
        return trim($this->_xpath->query("//div[@class='citizen_mass_destruction']/strong[1]/b")
                        ->item(0)->nodeValue
        );
    }

    public function getBigBombs() {
        return trim($this->_xpath->query("//div[@class='citizen_mass_destruction']/strong[2]/b")
                        ->item(0)->nodeValue
        );
    }

    public function getLastUsedMsg() {
        return trim($this->_xpath->query("//div[@class='citizen_mass_destruction']/em")
                        ->item(0)->nodeValue
        );
    }

    //helpers

    private function getEm() {
        return $this->container
                        ->get('doctrine_mongodb')
                        ->getManager()
        ;
    }

    //inits

    private function _prepare($id) {
        $html = $this->_get(self::URL_PROFILE . $id);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $this->_xpath = new \DOMXPath($dom);
        $this->_id = $id;
    }

    private function _formatNumber($number) {
        return str_replace(',', '', $number);
    }

    private function _getBeforeSlash($string) {
        $temp = explode('/', $string);
        return trim($temp[0]);
    }

    private function _getImage($string, $size) {
        preg_match('@((?:https?\:\/\/)(?:[a-zA-Z]{1}(?:[\w\-]+\.)+(?:[\w]{2,5}))(?:\:[\d]{1,5})?\/(?:[^\s\/]+\/)*(?:[^\s]+\.(?:jpe?g|gif|png))(?:\?\w+=\w+(?:&\w+=\w+)*)?)@', $string, $matches);
        if ($size == 'large')
            return str_replace('_142x142', '', $matches[1]);
        else if ($size == 'medium')
            return $matches[1];
        else if ($size == 'small')
            ;
        return str_replace('_142x142', '_55x55', $matches[1]);
    }

}
