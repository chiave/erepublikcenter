<?php

namespace Chiave\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class BackendController extends Controller
{
    /**
     * dashboard Action
     *
     * @Route("/admin", name="admin_dashboard")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function dashboardAction()
    {


        // $em = $this->getDoctrine()->getManager();

        // $citizen = $em
        //     ->getRepository('Chiave\ErepublikScrobblerBundle\Document\Citizen')
        //     ->findOneByCitizenId(4241769)
        // ;
        // // var_dump($citizen);
        // $cScrobbler = $this->get('erepublik_citizen_scrobbler');

        // $cScrobbler->updateCitizen($citizen->getCitizenId());

        // die;
        // $index = 1;

        // $endHistory = $citizen->getHistory($index);
        // $endRankPoints = $endHistory->getRankPoints();
        // echo $endHistory->getId(), ' ', $endRankPoints, '<br />';

        // $startHistory = $endHistory->getCitizen()
        //     ->getHistoryByDate($endHistory->getCreatedAt()->modify('-1 day'));
        // $startRankPoints = $startHistory->getRankPoints();
        // echo $startHistory->getId(), ' ', $startRankPoints, '<br />';;

        // $result = ($endRankPoints-$startRankPoints)*10;
        // echo $result;

        // die;

        // var_dump($citizen->getInfluence());


        // echo $this->container
            // ->get('erepublik_citizen_scrobbler')
            // ->showRawData(4241769);

        // var_dump(
        //     $this->container
        //         ->get('egov_nationalraport_fetcher')
        //         ->showRawData(1)
        // );



        // // // RAW DATA FOR USER
        // $citizen = $em
        //     ->getRepository('Chiave\ErepublikScrobblerBundle\Document\Citizen')
        //     ->findOneByCitizenId(4241769)
        // ;

        // $this->container
        //     ->get('erepublik_citizen_scrobbler')
        //     ->showRawData(4241769)
        // ;


        $this->container
            ->get('egov_nationalraport_fetcher')
            ->countPastDataStats(14)
        ;

        //2494465 - djstrach
        //4241769 - aplhanumerix

        // // NEW FIELD UPDATER SNIPPET

        // $citizens = $em
        //     ->getRepository('ChiaveErepublikScrobblerBundle:Citizen')
        //     ->findAll()
        // ;

        // foreach ($citizens as $citizen) {
        //     $changes = $citizen->getChanges();

        //     foreach ($changes as $change) {
        //         if ($change->getValue() == '') {
        //             $em->remove($change);
        //         }
        //     }
        //     // $citizen->setRankPoints($citizen->getRankPoints());
        // }

        // $em->flush();

        // TESTING
        // FIELDNAMES FOR CLASS

        // var_dump(
        //      $this->container
        //         ->get('doctrine_mongodb')
        //         ->getClassMetadata('Chiave\ErepublikScrobblerBundle\Document\Citizen')
        //         ->getFieldNames()
        // );


        // // DATETIME PLAYGROUND

        // todays day change
        // $lastDayChange = new \DateTime('now');
        // $lastDayChange->modify('+8 hours');

        // if($lastDayChange->format('G') < 9) {
        //     $lastDayChange->modify('-1 day');
        // }

        // $lastDayChange->setTime(9, 0);

        // return $lastDayChange;

        // die;

        // var_dump(
        //      $this->container
        //         ->get('doctrine_mongodb')
        //         ->getClassMetadata('Chiave\ErepublikScrobblerBundle\Document\Citizen')
        //         ->getFieldNames()
        // );

        return array();
    }
}
