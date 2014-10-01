<?php

namespace Chiave\ErepublikScrobblerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\ErepublikScrobblerBundle\Document\CitizenHistory;
use Chiave\ErepublikScrobblerBundle\Form\DofType;

/**
 * BackendDofController.
 *
 * @Route("/admin/dof")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BackendDofController extends BaseController {

    /**
     * Lists all citizens.
     *
     * @Route("s/", name="chiave_dofs")
     * @Template()
     */
    public function indexAction(Request $request) {

        $timeMaster = $this->container->get('date_time');

        $todayDay = $timeMaster->getErepublikDate(1);

        $formData = $request->query->all();

        isset($formData['startDay']) && $formData['startDay'] != null ?
                        $data['startDay'] = $formData['startDay'] :
                        $data['startDay'] = $todayDay
        ;
        isset($formData['endDay']) && $formData['endDay'] != null ?
                        $data['endDay'] = $formData['endDay'] :
                        $data['endDay'] = $todayDay
        ;
        isset($formData['div']) && $formData['div'] != null ?
                        $data['div'] = $formData['div'] :
                        $data['div'] = null
        ;
        isset($formData['status']) && $formData['status'] != null ?
                        $data['status'] = $formData['status'] :
                        $data['status'] = ''
        ;

        $searchForm = $this->createSearchForm($data);
//        var_dump($data);
//        $startDate = $timeMaster->getDateByDay($data['startDay']);
//        $endDate = $timeMaster->getDateByDay($data['endDay'])->modify('+1 day');
        $qb = $this->getQb('ChiaveErepublikScrobblerBundle:CitizenHistory');
//        $qb = new \Doctrine\ODM\MongoDB\Query\Builder;
        $qb
                ->field('eday')->gte((int) $data['startDay'])
                ->field('eday')->lte((int) $data['endDay'])
        ;


//        $qb->addAnd(
//                $qb->expr()->field('eday')->gte((int) $data['startDay'])
//                        ->lte((int) $data['startDay'])
//        );
//
//
//
//        if ($data['div'] != null) {
//            $query->field('division')->equals($data['div']);
//        }
//        if ($data['status'] !== '') {
//            $query->field('dof')->equals($data['status']);
//        }
//        $qb
////                ->field('egovBattles')->notEqual(0)
//                ->sort('nick', 'asc')
//                ->sort('eday', 'desc')
//        ;

        $histories = $qb->getQuery()->execute();
//        var_dump($histories);
//        die;
//        $results = array();
//
//        foreach ($histories as $history) {
////            $results[$history->getNick()] = null;
//        }
//        var_dump($results);
//        die;

        return array(
            'searchForm' => $searchForm->createView(),
            'histories' => $histories,
        );
    }

    /**
     * Change dof status.
     *
     * @Route("/{historyId}/change/{status}", name="chiave_dof_change")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function changeAction(Request $request, $historyId, $status) {
        $result = new \stdClass();
        $result->success = false;

        $em = $this->getEm();
        $history = $em
                ->getRepository('ChiaveErepublikScrobblerBundle:CitizenHistory')
                ->find($historyId);

        if (!$history) {
            // throw $this->createNotFoundException('Unable to find Categories.');
            $result->error = 'Unable to find Citizen.';
        } else {
            $history->setDof($status);
            $em->flush();

            $result->success = true;
            $result->dofStatus = $history->getDofText();
        }

        return new JsonResponse($result);
    }

    /**
     * Creates a form for dof picking.
     *
     * @return \Symfony\Component\Form\Form Form
     */
    public function createSearchForm($data) {
//        return $this->createFormBuilder(null, array('csrf_protection' => false))
        return $this->get('form.factory')
                        ->createNamedBuilder(null, 'form', array(), array(
                            'csrf_protection' => false,
                        ))
                        ->setMethod('GET')
                        ->add('startDay', 'integer', array(
                            'precision' => 0,
                            'data' => $data['startDay'],
                            'required' => false,
                        ))
                        ->add('endDay', 'integer', array(
                            'precision' => 0,
                            'data' => $data['endDay'],
                            'required' => false,
                        ))
//                        ->add('div', 'choice', array(
//                            'choices' => array(
//                                '' => 'Wszystkie',
//                                '1' => 'I',
//                                '2' => 'II',
//                                '3' => 'III',
//                                '4' => 'IV',
//                            ),
//                            'data' => $data['div'],
//                            'required' => false,
//                        ))
//                        ->add('status', 'choice', array(
//                            'choices' => array(
//                                '0' => 'niewydane',
//                                '1' => 'wydane',
//                                '-1' => 'pominięte',
//                                '' => 'wszystkie',
//                            ),
//                            'data' => $data['status'],
//                            'required' => false,
//                        ))
                        // ->add('div', 'integer', array(
                        // 'precision' => 0,
                        // 'data'      => $data['div'],
                        // // 'mapped'    => false,
                        // 'required'  => false,
                        // ))
                        ->add('send', 'submit')
                        ->getForm();
    }

    private function getEm() {
        return $this->get('doctrine_mongodb')->getManager();
    }

}
