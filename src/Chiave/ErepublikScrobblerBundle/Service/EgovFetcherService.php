<?php

namespace Chiave\ErepublikScrobblerBundle\Service;

use Chiave\ErepublikScrobblerBundle\Libraries\CurlUtils;

use Chiave\MilitaryUnitBundle\Entity\MilitaryUnit;
use Chiave\MilitaryUnitBundle\Entity\MilitaryUnitHistory;

/**
 * class EgovFetcherService
 *
 * class description here
 *
 * @author  Alphanumerix <>
 */
class EgovFetcherService extends CurlUtils
{

    protected $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    //remember to add country code and erepublik day at the and
    CONST URL_EGOV_JSON = 'http://www.egov4you.info/operations/reportJson/';
    CONST URL_MU_PROFILE = 'http://www.erepublik.com/en/main/group-show/';


    private $_xpath;
    private $_id;

    public function showRawData($modifyDays = 0, $countryCode = 35) {

        $data = $this->getNationalRaportArray($modifyDays, $countryCode);

        var_dump($data);

        return $data;
    }

    public function countPastDataStats($modifyDays = 0,$citizen_id = 4241769 ,$countryCode = 35) {

        $em = $this->getEm();
        
        $battles = 0;
        $hits = 0;
        $influence = 0;

        for (; $modifyDays >= 0 ; $modifyDays--) { 
        

            $data = $this->getNationalRaportArray($modifyDays, $countryCode);
            // var_dump($data);
            // die;

            foreach ($data['minisoldiersStats'] as $soldier) {
                        if($soldier['citizen'] == $citizen_id){
                            $battles = $battles + $soldier['battles'];
                            $hits = $hits + $soldier['hits'];
                            $influence = $influence + $soldier['influence'];
                        }        
            }
        }
        $history = $mu->getHistory();

        $history->setBattles($battles);
        $history->setHits($hits);
        $history->setInfluence($influence);

        $em->persist($history);
        $em->flush();

    }


    public function updateMilitaryUnits() {
        $em = $this->getEm();
        $nationalRaport = $this->getNationalRaportArray();

        foreach($nationalRaport['miniarmiesStats'] as $muFreshData) {
            $mu = $this->getRepo()->findOneByUnitId($muFreshData['unit']);

            if($mu == null) {
                $mu = new MilitaryUnit($muFreshData['unit']);

                $em->persist($mu);
                $em->flush();
            }

            $history = $mu->getHistory();

            $history->setBattles($muFreshData['battles']);
            $history->setHits($muFreshData['hits']);
            $history->setInfluence($muFreshData['influence']);
            $history->setSoldiers($muFreshData['soldiers']);

            $em->persist($history);
            $em->flush();
        }
    }

    public function updateCitizens() {
        $em = $this->getEm();
        $nationalRaport = $this->getNationalRaportArray();

        foreach($nationalRaport['minisoldiersStats'] as $citizenFreshData) {
            $citizen = $this
                ->getRepo('ChiaveErepublikScrobblerBundle:Citizen')
                ->findOneByCitizenId($citizenFreshData['citizen']);

            if($citizen == null) {
                //TODO: Continue for now,
                //  create new citizen if part of bluerose in the future
                continue;
                // $citizen = new MilitaryUnit($citizenFreshData['unit']);

                // $em->persist($citizen);
                // $em->flush();
            }

            $history = $citizen->getHistory();
            $history->setEgovBattles($citizenFreshData['battles']);
            $history->setEgovHits($citizenFreshData['hits']);
            $history->setEgovInfluence($citizenFreshData['influence']);



            $em->persist($history);
            $em->flush();
        }
    }



    public function muExists()
    {
        $query = $this->_xpath->query("//div[@class='header_content']/h2/span");

        if ($query && $query->item(0) && $query->item(0)->nodeValue) {
            return true;
        }

        return false;
    }

    //militaryunits pages requre login-in ;) dead-end, tank needed...
    public function parseMUProfile($id)
    {
        $this->_prepare($id);

        if (!$this->muExists()) {
            return;
        }

        echo $this->getName();
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getName()
    {
        return trim($this->_xpath->query("//div[@class='header_content']/h2/span")
            ->item(0)->nodeValue
        );
    }

    // public function getAvatar($size = 'large')
    // {
    //     return trim($this->_getImage(
    //         $this->_xpath->query("//img[@class='citizen_avatar']/@style")
    //         ->item(0)->nodeValue, $size
    //     ));

    // }

    // public function getLvl()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_experience']/strong[@class='citizen_level']")
    //         ->item(0)->nodeValue
    //     );
    // }

    // public function getExp()
    // {
    //     return trim($this->_formatNumber($this->_getBeforeSlash(
    //         $this->_xpath->query("//div[@class='citizen_experience']/div/p")
    //         ->item(0)->nodeValue
    //     )));
    // }

    // public function getStr()
    // {
    //     return trim($this->_formatNumber(
    //         $this->_xpath->query("//div[@class='citizen_military'][1]/h4")
    //         ->item(0)->nodeValue
    //     ));
    // }

    // public function getRank()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_military'][2]/h4/a")
    //         ->item(0)->nodeValue
    //     );
    // }

    // public function getRankImage()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_military'][2]/h4/img/@src")
    //         ->item(0)->nodeValue
    //     );
    // }

    // public function getRankPoints()
    // {
    //     return trim($this->_formatNumber($this->_getBeforeSlash(
    //         $this->_xpath->query("//div[@class='citizen_military'][2]/div[@class='stat']/small[2]/strong")
    //         ->item(0)->nodeValue
    //     )));
    // }

    // public function getTruePatriot()
    // {
    //     $query = $this->_xpath->query("//div[@class='citizen_military'][3]/div[@class='stat']/small[2]/strong")
    //                 ->item(0)
    //     ;

    //     if ($query) {
    //         return $this->_formatNumber(
    //             $this->_getBeforeSlash(
    //                $query->nodeValue
    //         ));
    //     }

    //     return null;
    // }

    // public function getEBirthday()
    // {
    //     $eBirthday = trim($this->_xpath->query("//div[@class='citizen_info']/div[@class='citizen_second']/p[2]")
    //         ->item(0)->nodeValue);
    //     $dt = new \DateTime();
    //     $dt = $dt->createFromFormat('M d, Y', $eBirthday);
    //     return $dt;
    // }


    // public function getCountry()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_sidebar']/div[@class='citizen_info']/a[1]")
    //         ->item(0)->nodeValue
    //     );
    // }

    // public function getRegion()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_sidebar']/div[@class='citizen_info']/a[2]")
    //         ->item(0)->nodeValue
    //     );
    // }

    // public function getCitizenship()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_sidebar']/div[@class='citizen_info']/a[3]")
    //         ->item(0)->nodeValue
    //     );
    // }

    // public function getNationalRank()
    // {
    //     return trim($this->_xpath->query("//div[@class='citizen_second']/small[3]/strong")
    //         ->item(0)->nodeValue
    //     );

    // }

    // public function getParty()
    // {
    //     $partyString = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][1]/div/span/a")
    //         ->item(0)
    //     ;

    //     if ($partyString) {
    //         return trim($partyString->nodeValue);
    //     }

    //     return null;
    // }

    // public function getPartyId()
    // {
    //     $partyString = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][1]/div/span/a/@href")
    //         ->item(0)
    //     ;

    //     if ($partyString) {
    //         $party = $partyString->nodeValue;
    //         preg_match('/(\d+)/', $party, $id);
    //         return $id[1];
    //     }

    //     return null;
    // }

    // public function getMilitaryUnit()
    // {
    //     $mu = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][2]/div[@class='one_newspaper']/a")
    //         ->item(0)
    //     ;

    //     if ($mu) {
    //         return trim($mu->nodeValue);
    //     }

    //     return null;
    // }

    // public function getMilitaryUnitId()
    // {
    //     $mu = $this->_xpath->query("//div[@class='citizen_activity']/div[@class='place'][2]/div[@class='one_newspaper']/a/@href")->item(0);

    //     if ($mu) {
    //         $result = $mu->nodeValue;
    //         $id = explode('/', $result);
    //         return end($id);
    //     }

    //     return null;
    // }

    // public function getMedals()
    // {
    //     $allMedals = $this->_xpath->query("//ul[@id='achievment']/li");
    //     $medals = array();
    //     foreach ($allMedals as $medal) {
    //         $type = $this->_xpath->query(".//span/p/strong", $medal)
    //             ->item(0)->nodeValue;
    //         $amount = $this->_xpath->query(".//div[@class='counter']", $medal);
    //         $medals[$type] = ($amount->length > 0 ? (int)$amount->item(0)->nodeValue : 0);
    //     }
    //     return $medals;
    // }




    public function getNationalRaportArray($modifyDays = 0, $countryCode = 35) {

        $erepDay = $this->container->get('date_time')->getErepublikDate($modifyDays);

        $url = self::URL_EGOV_JSON . "$countryCode/$erepDay/";

        $data = $this->getData($url);

        return $data;
    }

    /**
    *
    * Convert recursively an object to an array
    *
    * @param    object  $object The object to convert
    * @return      array
    *
    */
    private function objectToArray($object)
    {
        if(!is_object($object) && !is_array($object))
        {
            return $object;
        }
        if(is_object($object))
        {
            $object = get_object_vars($object);
        }
        return array_map(
            array(
                'Chiave\ErepublikScrobblerBundle\Service\EgovFetcherService',
                'objectToArray'
            ),
            $object
        );
    }

    private function getData($url)
    {
        $ch = curl_init();
        $timeout = 5;

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);

        $data = curl_exec($ch);
        curl_close($ch);

        return $this->objectToArray(json_decode($data));
    }

    private function getEm()
    {
        return $this->container->get('doctrine_mongodb')->getManager();
        ;
    }

    private function getRepo($class = 'ChiaveMilitaryUnitBundle:MilitaryUnit')
    {
        return $this->container
            ->get('doctrine_mongodb')
            ->getRepository($class)
        ;
    }

    private function _prepare($id)
    {
        $html = $this->_get(self::URL_MU_PROFILE . $id);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $this->_xpath = new \DOMXPath($dom);
        $this->_id = $id;
    }

    private function _formatNumber($number)
    {
        return str_replace(',', '', $number);
    }

    private function _getBeforeSlash($string)
    {
        $temp = explode('/', $string);
        return trim($temp[0]);
    }

    private function _getImage($string, $size)
    {
        preg_match('@((?:https?\:\/\/)(?:[a-zA-Z]{1}(?:[\w\-]+\.)+(?:[\w]{2,5}))(?:\:[\d]{1,5})?\/(?:[^\s\/]+\/)*(?:[^\s]+\.(?:jpe?g|gif|png))(?:\?\w+=\w+(?:&\w+=\w+)*)?)@', $string, $matches);
        if ($size == 'large')
            return str_replace('_142x142', '', $matches[1]);
        else if ($size == 'medium')
            return $matches[1];
        else if ($size == 'small');
            return str_replace('_142x142', '_55x55', $matches[1]);
    }
}

