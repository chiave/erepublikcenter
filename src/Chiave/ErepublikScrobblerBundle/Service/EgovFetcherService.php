<?php

namespace Chiave\ErepublikScrobblerBundle\Service;

use Chiave\ErepublikScrobblerBundle\Libraries\CurlUtils;
use Chiave\MilitaryUnitBundle\Document\MilitaryUnit;
use Chiave\MilitaryUnitBundle\Document\MilitaryUnitHistory;
use Chiave\ErepublikScrobblerBundle\Document\Citizen;

/**
 * class EgovFetcherService
 *
 * class description here
 *
 * @author  Alphanumerix <>
 */
class EgovFetcherService extends CurlUtils {

    protected $container;

    public function setContainer($container) {
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

    public function fetchBluerosePlayers($period = 0) {
//        #NOTICE: First egov scrobblable day is 1712
        $dm = $this->getEm();

        for (; $period >= 0; $period--) {
            $allData = $this->getNationalRaportArray($period);
            $data = $allData['minisoldiersStats'];
            foreach ($data as $sd) {
                if ($sd['unit'] == 125 || $sd['army'] == 125) {
                    $citizen = $this
                            ->getRepo('ChiaveErepublikScrobblerBundle:Citizen')
                            ->findOneByCitizenId($sd['citizen']);
                    if (!($citizen instanceof Citizen)) {
                        $citizen = new Citizen();
                        $citizen->setCitizenId($sd['citizen']);

                        $dm->persist($citizen);

                        $firstHistory = $this->container
                                ->get('erepublik_citizen_scrobbler')
                                ->updateCitizenHistory($citizen);

                        $dm->flush();
                    }
                }
            }
        }

        return $data;
    }

    public function fixer() {
        $dm = $this->getEm();
        $citizens = $this->getRepo('ChiaveErepublikScrobblerBundle:Citizen')
                ->findAll();
        foreach ($citizens as $citizen) {
            $histories = $citizen->getAllHistory();
            $current = $citizen->getHistory();
            foreach ($histories as $history) {
                $history->setNick($current->getNick());
                $history->setAvatarUrl($current->getAvatarUrl());
                $history->setExperience($current->getExperience());
            }
            $dm->flush();
        }
    }

    public function fetchOldHistory($period = 0) {
//        #NOTICE: First egov scrobblable day is 1712
        $dm = $this->getEm();
        $citizens = $this->getRepo('ChiaveErepublikScrobblerBundle:Citizen')
                ->findAll();

        for ($i = 0; $i <= $period; $i++) {
            $allData = $this->getNationalRaportArray($i);
            $data = $allData['minisoldiersStats'];

            foreach ($data as $sd) {
                foreach ($citizens as $citizen) {
                    if ($sd['citizen'] == $citizen->getCitizenId()) {
                        $eday = $this->container
                                ->get('date_time')
                                ->getErepublikDate($i);

                        $history = $this->getQb('ChiaveErepublikScrobblerBundle:CitizenHistory')
                                ->field('citizen')->equals($citizen->getId())
                                ->field('eday')->equals((int) $eday)
                                ->limit(1)
                                ->getQuery()
                                ->getSingleResult();

                        if (!($history instanceof \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory)) {
                            $history = new \Chiave\ErepublikScrobblerBundle\Document\CitizenHistory($citizen, $eday);
                        }

                        $history->setEgovBattles($sd['battles']);
                        $history->setEgovHits($sd['hits']);
                        $history->setEgovInfluence($sd['influence']);
                        $history->setCitizen($citizen);
                        $dm->persist($history);
                    }
                }
            }

            $dm->flush();
        }
    }

//    public function countPastDataStats($modifyDays = 0, $citizen_id = 4241769, $countryCode = 35) {
//
//        $em = $this->getEm();
//
//        $battles = 0;
//        $hits = 0;
//        $influence = 0;
//
//        for (; $modifyDays >= 0; $modifyDays--) {
//
//
//            $data = $this->getNationalRaportArray($modifyDays, $countryCode);
//
//            foreach ($data['minisoldiersStats'] as $soldier) {
//                if ($soldier['citizen'] == $citizen_id) {
//                    $battles = $battles + $soldier['battles'];
//                    $hits = $hits + $soldier['hits'];
//                    $influence = $influence + $soldier['influence'];
//                }
//            }
//
//            $citizenrepo = $em->getRepository("Chiave\ErepublikScrobblerBundle\Document\Citizen");
//            $citizen = $citizenrepo->findOneByCitizenId($citizen_id);
//            // var_dump($citizen);
//            // $repo = $em->getRepository("Chiave\ErepublikScrobblerBundle\Document\CitizenHistory");
//            // $results = $repo->createQueryBuilder()
//            //     ->field("citizen.id")->equals($citizen->getId())
//            //     ->sort("createdAt","desc")
//            //     ->limit(1)
//            //     ->getQuery()
//            //     ->execute();
//            // foreach ($results as $result) {
//            //     var_dump($result);
//            //     die;
//            // }
//
//            $results = $citizen->getHistory();
//
//            $results->setEgovBattles($results->getEgovBattles() + $battles);
//            $results->setEgovHits($results->getEgovHits() + $hits);
//            $results->setEgovInfluence($results->getEgovInfluence() + $influence);
//
//            $em->persist($results);
//            $em->flush();
//        }
//}

    public function updateMilitaryUnits() {
        $em = $this->getEm();
        $nationalRaport = $this->getNationalRaportArray();

        foreach ($nationalRaport['miniarmiesStats'] as $muFreshData) {
            $mu = $this->getRepo()->findOneByUnitId($muFreshData['unit']);

            if ($mu == null) {
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

        foreach ($nationalRaport['minisoldiersStats'] as $citizenFreshData) {
            $citizen = $this
                    ->getRepo('ChiaveErepublikScrobblerBundle:Citizen')
                    ->findOneByCitizenId($citizenFreshData['citizen']);

            if ($citizen == null) {
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

    public function muExists() {
        $query = $this->_xpath->query("//div[@class='header_content']/h2/span");

        if ($query && $query->item(0) && $query->item(0)->nodeValue) {
            return true;
        }

        return false;
    }

//militaryunits pages requre login-in ;) dead-end, tank needed...
    public function parseMUProfile($id) {
        $this->_prepare($id);

        if (!$this->muExists()) {
            return;
        }

        echo $this->getName();
    }

    public function getId() {
        return $this->_id;
    }

    public function getName() {
        return trim($this->_xpath->query("//div[@class='header_content']/h2/span")
                        ->item(0)->nodeValue
        );
    }

    /**
     * @param integer $modifyDays = 0
     * @param integer $countryCode = 35
     * @return array
     */
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
    private function objectToArray($object) {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map(
                array(
            'Chiave\ErepublikScrobblerBundle\Service\EgovFetcherService',
            'objectToArray'
                ), $object
        );
    }

    private function getData($url) {
        $ch = curl_init();
        $timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $data = curl_exec($ch);
        curl_close($ch);

        return $this->objectToArray(json_decode($data));
    }

    private function getEm() {
        return $this->container->get('doctrine_mongodb')->getManager();
    }

    private function getRepo($class = 'ChiaveMilitaryUnitBundle:MilitaryUnit') {
        return $this->container
                        ->get('doctrine_mongodb')
                        ->getRepository($class)
        ;
    }

    /**
     * @param string $alias
     * @return mixed
     */
    public function get($alias) {
        return $this->container->get($alias);
    }

    /**
     * Get doctrine.
     * No matter if m*sql or mongo is used.
     *
     * @return mixed
     */
    protected function getDoctrine() {
        if ($this->container->has('doctrine_mongodb')) {
            return $this->container->get('doctrine_mongodb');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Get manager.
     * No matter if m*sql or mongo is used.
     *
     * @return mixed
     */
    protected function getManager() {
        return $this->getManager();
    }

    /**
     * getQb
     * No matter if m*sql or mongo is used.
     *
     * @param string $alias - alias for class
     * @return \Doctrine\ORM\QueryBuilder instance with an 'a' alias
     */
    protected function getQb($alias, $queryName = 'a') {
//        new \Doctrine\ORM\QueryBuilder
        return $this->getDoctrine()
                        ->getRepository($alias)
                        ->createQueryBuilder($queryName)
        ;
    }

    /**
     * add Flash Message
     * supported types:
     *      default     - blue
     *      success     - green
     *      warning     - orange
     *      info        - light blue
     *      alert       - red
     *      secondary   - gray
     *
     * @param string $message - autotranslated, if translation exists
     * @param string $type = 'notice'
     */
    protected function addFlashMsg($message, $type = 'default') {
        $this->container
                ->get('session')
                ->getFlashBag()
                ->add($type, $this->trans($message))
        ;
    }

    /**
     * Translate string
     *
     * @param string $message - translated, if translation exists
     */
    protected function trans($message) {
        return $this->container
                        ->get('translator')
                        ->trans($message)
        ;
    }

    private function _prepare($id) {
        $html = $this->_get(self::URL_MU_PROFILE . $id);
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
