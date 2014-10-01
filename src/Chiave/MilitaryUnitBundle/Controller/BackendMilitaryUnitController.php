<?php

namespace Chiave\MilitaryUnitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\MilitaryUnitBundle\Entity\MilitaryUnit;
use Chiave\MilitaryUnitBundle\Form\MilitaryUnitType;

/**
 * MilitaryUnit controller.
 *
 * @Route("/admin/militaryUnit")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BackendMilitaryUnitController extends BaseController {

    /**
     * Lists all militaryUnits.
     *
     * @Route("s", name="chiave_militaryunits")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getManager();

        $militaryUnits = $em
                ->getRepository('ChiaveMilitaryUnitBundle:MilitaryUnit')
                ->findAll()
        ;

        return array(
            'militaryUnits' => $militaryUnits,
        );
    }

    // /**
    //  * @Route("/create", name="chiave_scrobbler_citizen_create")
    //  * @Method("POST")
    //  * @Security("has_role('ROLE_ADMIN')")
    //  * @Template("ChiaveErepublikScrobblerBundle:BackendCitizen:update.html.twig")
    //  */
    // public function createAction(Request $request)
    // {
    //     $citizen = new Citizen();
    //     $form = $this->createCitizenForm(
    //         $citizen,
    //         'chiave_scrobbler_citizen_create'
    //         );
    //     $form->handleRequest($request);
    //     if ($form->isValid()) {
    //         $em = $this->getManager();
    //         $citizen = $this
    //             ->get('erepublik_citizen_scrobbler')
    //             ->updateCitizen(
    //                 $citizen
    //             )
    //         ;
    //         // $em->persist($citizen);
    //         // $em->flush();
    //         return $this->redirect(
    //             $this->generateUrl('chiave_scrobbler_citizens')
    //         );
    //     }
    //     return array(
    //         'citizen' => $citizen,
    //         'form'   => $form->createView(),
    //     );
    // }
    // /**
    //  * @Route("/new", name="chiave_scrobbler_citizen_new")
    //  * @Method("GET")
    //  * @Security("has_role('ROLE_ADMIN')")
    //  * @Template("ChiaveErepublikScrobblerBundle:BackendCitizen:update.html.twig")
    //  */
    // public function newAction(Request $request)
    // {
    //     $citizen = new Citizen();
    //     $form = $this->createCitizenForm(
    //         $citizen,
    //         'chiave_scrobbler_citizen_create'
    //         );
    //     return array(
    //         'citizen' => $citizen,
    //         'form'   => $form->createView(),
    //     );
    // }

    /**
     * Displays a form to edit an existing militaryUnit.
     *
     * @Route("/{id}/edit", name="chiave_militaryunit_edit")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveMilitaryUnitBundle:BackendMilitaryUnit:update.html.twig")
     */
    public function editAction($id) {
        $em = $this->getManager();

        $militaryUnit = $em->getRepository('ChiaveMilitaryUnitBundle:MilitaryUnit')->find($id);

        if (!$militaryUnit) {
            throw $this->createNotFoundException('Unable to find MilitaryUnit.');
        }

        $form = $this->createMUForm(
                $militaryUnit, 'chiave_militaryunit_update'
        );

        return array(
            'militaryUnit' => $militaryUnit,
            'form' => $form->createView(),
        );
    }

    /**
     * Edits an existing militaryUnit.
     *
     * @Route("/{id}/update", name="chiave_militaryunit_update")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getManager();

        $militaryUnit = $em->getRepository('ChiaveMilitaryUnitBundle:MilitaryUnit')->find($id);

        if (!$militaryUnit) {
            throw $this->createNotFoundException('Unable to find MilitaryUnit.');
        }

        $form = $this->createMUForm(
                $militaryUnit, 'chiave_militaryunit_update'
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chiave_militaryunits'));
        }

        return array(
            'militaryUnit' => $militaryUnit,
            'form' => $form->createView(),
        );
    }

    // /**
    //  * Deletes citizen.
    //  *
    //  * @Route("/{id}/delete", name="chiave_scrobbler_citizen_delete")
    //  * @Method("POST")
    //  * @Security("has_role('ROLE_ADMIN')")
    //  */
    // public function deleteAction(Request $request, $id)
    // {
    //     $result = new \stdClass();
    //     $result->success = false;
    //     $em = $this->getManager();
    //     $citizen = $em->getRepository('ChiaveErepublikScrobblerBundle:Citizen')->find($id);
    //     if (!$citizen) {
    //         // throw $this->createNotFoundException('Unable to find Categories.');
    //         $result->error = 'Unable to find Citizen.';
    //     } else {
    //         $em->remove($citizen);
    //         $em->flush();
    //         $result->success = true;
    //     }
    //     return new JsonResponse($result);
    // }

    /**
     * Creates a form for militaryUnit.
     *
     * @param MilitaryUnit $militaryUnit
     * @param string $route
     *
     * @return \Symfony\Component\Form\Form Form for militaryUnit
     */
    public function createMUForm(MilitaryUnit $militaryUnit, $route) {
        return $this->createForm(
                        new MilitaryUnitType(), $militaryUnit, array(
                    'action' => $this->generateUrl(
                            $route, array(
                        'id' => $militaryUnit->getId(),
                    )),
                    'method' => 'post',
                        )
        );
    }

}
