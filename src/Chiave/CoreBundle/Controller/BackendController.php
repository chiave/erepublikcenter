<?php

namespace Chiave\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class BackendController extends BaseController
{
    //    /**
//     * login Action
//     *
//     * @Route("/login", name="login")
//     */
//    public function loginAction(Request $request)
//    {
//        $session = $request->getSession();
//
//        // get the login error if there is one
//        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
//            $error = $request->attributes->get(
//                SecurityContext::AUTHENTICATION_ERROR
//            );
//        } else {
//            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
//            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
//        }
//
//        return $this->render(
//            'ChiaveCoreBundle:Backend:login.html.twig',
//            array(
//                // last username entered by the user
//                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
//                'error'         => $error,
//            )
//        );
//    }

    /**
     * dashboard Action
     *
     * @Route("/admin", name="admin_dashboard")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function dashboardAction()
    {
        // $em = $this->getManager();
        // $citizen = $em
        //     ->getRepository('Chiave\ErepublikScrobblerBundle\Entity\Citizen')
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
        // var_dump(
        //     $this->container
        //         ->get('egov_nationalraport_fetcher')
        //         ->showRawData(1)
        // );
        // // // RAW DATA FOR USER
        // $citizen = $em
        //     ->getRepository('Chiave\ErepublikScrobblerBundle\Entity\Citizen')
        //     ->findOneByCitizenId(4241769)
        // ;
        // $this->container
        //     ->get('erepublik_citizen_scrobbler')
        //     ->updateCitizen($citizen)
        // ;
        // $this->container
        //     ->get('egov_nationalraport_fetcher')
        //     ->updateCitizens()
        // ;
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
        //         ->get('doctrine.orm.entity_manager')
        //         ->getClassMetadata('Chiave\ErepublikScrobblerBundle\Entity\Citizen')
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
        // echo $this->container
        //     ->get('date_time')
        //     ->getErepublikDate();
        // var_dump(
        //      $this->container
        //         ->get('doctrine.orm.entity_manager')
        //         ->getClassMetadata('Chiave\ErepublikScrobblerBundle\Entity\Citizen')
        //         ->getFieldNames()
        // );

        return $this->render(
                        'ChiaveCoreBundle:Admin:dashboard.html.twig'
        );
    }
}
