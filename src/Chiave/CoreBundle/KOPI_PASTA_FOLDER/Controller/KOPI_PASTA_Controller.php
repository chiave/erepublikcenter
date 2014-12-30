<?php

namespace Chiave\NAZWABUNDLA\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chiave\NAZWABUNDLA\Document\NAZWAGŁÓWNEJENCJI;
use Chiave\NAZWABUNDLA\Form\NAZWAGŁÓWNEJENCJIType;

// NAZWAKONTROLERA -> podaj nazwę kontrolera bez slowa Controller
// NAZWAGŁÓWNEJENCJI -> podaj nazwę głównej encji z dużej litery
// NAZWAGŁÓWNEJencji -> podaj nazwę głównej encji z małej litery
// NAZWABUNDLA -> podaj nazwę bundla w którym jesteśmy

/**
 * NAZWAKONTROLERA controller.
 *
 * @Route("/admin/NAZWAGŁÓWNEJencji")
 */
class KOPI_PASTA_Controller extends BaseController
{
    /**
     * @Route("s", name="NAZWAGŁÓWNEJencji_index")
     * @Method("GET")
     * @Template()
     */
//     * @Template("ChiaveNAZWABUNDLA:NAZWAKONTROLERA:NAZWAGŁÓWNEJencjiIndex.html.twig")
    public function indexAction()
    {
        $qb = $this->getQb('ChiaveNAZWABUNDLA:NAZWAGŁÓWNEJENCJI');
        $pagination = $this->get('knp_paginator')->paginate(
                $qb, $this->get('request')->query->get('page', 1), 20
        );

        /*
          $qb
          //STRINGI
          ->andWhere("a.name LIKE :name")
          ->setParameter("name", '%' . $playerName . '%');

          //INNE
          ->andWhere("t.game = :game")
          ->setParameter("game", $game);
         */

//        $query = $em->createQuery("SELECT u FROM MyProject\User u");
//        $query->setFetchMode("MyProject\User", "address", "EAGER");
//        $query->execute();


        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Route("/create", name="NAZWAGŁÓWNEJencji_create")
     * @Method("POST")
     * @Template()
     */
//              @Template("ChiaveNAZWABUNDLA:NAZWAKONTROLERA:NAZWAGŁÓWNEJencjiUpdate.html.twig")
    public function createAction(Request $request)
    {
        $em = $this->getManager();
        $NAZWAGŁÓWNEJencji = new NAZWAGŁÓWNEJENCJI();

        $form = $this->createNAZWAGŁÓWNEJENCJIForm($NAZWAGŁÓWNEJencji, 'NAZWAGŁÓWNEJencji_create');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($NAZWAGŁÓWNEJencji);
            $em->flush();
            $msg = $this->get('translator')
                    ->trans('Akcja wykonana pomyślnie');
            $this->addFlashMsg($msg, 'success');

            return $this->redirect($this->generateUrl('NAZWAGŁÓWNEJencji_update', array('id' => $NAZWAGŁÓWNEJencji->getId())));
        }

        return array(
            'NAZWAGŁÓWNEJencji' => $NAZWAGŁÓWNEJencji,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/new", name="NAZWAGŁÓWNEJencji_new")
     * @Method("GET")
     * @Template("ChiaveNAZWABUNDLA:NAZWAKONTROLERA:update.html.twig")
     */
//     * @Template("ChiaveNAZWABUNDLA:NAZWAKONTROLERA:NAZWAGŁÓWNEJencjiUpdate.html.twig")
    public function newAction()
    {
        $NAZWAGŁÓWNEJencji = new NAZWAGŁÓWNEJENCJI();

        $form = $this->createNAZWAGŁÓWNEJENCJIForm($NAZWAGŁÓWNEJencji, 'NAZWAGŁÓWNEJencji_create');

        return array(
            'NAZWAGŁÓWNEJencji' => $NAZWAGŁÓWNEJencji,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/update", name="NAZWAGŁÓWNEJencji_update")
     * @Method({"GET","POST"})
     * @Template()
     */
//     * @Template("ChiaveNAZWABUNDLA:NAZWAKONTROLERA:NAZWAGŁÓWNEJencjiUpdate.html.twig")

    public function updateAction(Request $request, $id)
    {
        $NAZWAGŁÓWNEJencji = $this->getRepo('ChiaveNAZWABUNDLA:NAZWAGŁÓWNEJENCJI')->find($id);
        $form = $this->createNAZWAGŁÓWNEJENCJIForm(
                $NAZWAGŁÓWNEJencji, 'NAZWAGŁÓWNEJencji_update'
        );

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getManager();

                $em->persist($NAZWAGŁÓWNEJencji);
                $em->flush();
                $msg = $this->get('translator')
                        ->trans('Akcja wykonana pomyślnie');
                $this->addFlashMsg($msg, 'success');
            }
        }

        return array(
            'NAZWAGŁÓWNEJencji' => $NAZWAGŁÓWNEJencji,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form for NAZWAGŁÓWNEJencji update.
     *
     * @param NAZWAGŁÓWNEJENCJI $NAZWAGŁÓWNEJencji
     * @param string              $route
     *
     * @return \Symfony\Component\Form\Form Form for visit
     */
    public function createNAZWAGŁÓWNEJENCJIForm(NAZWAGŁÓWNEJENCJI $NAZWAGŁÓWNEJencji, $route)
    {
        return $this->createForm(
                        new NAZWAGŁÓWNEJENCJIType(), $NAZWAGŁÓWNEJencji, array(
                    'action' => $this->generateUrl(
                            $route, array(
                        'id' => $NAZWAGŁÓWNEJencji->getId(),
                            )
                    ),
                    'method' => 'post',
                        )
        );
    }

    /**
     * Deletes a NAZWAGŁÓWNEJencji.
     *
     * @Route("/{id}/delete", name="NAZWAGŁÓWNEJencji_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $result = new \stdClass();
        $result->success = false;
        $NAZWAGŁÓWNEJencji = $this->getRepo('ChiaveNAZWABUNDLA:NAZWAGŁÓWNEJENCJI')->find($id);

        if ($NAZWAGŁÓWNEJencji) {
            $em = $this->getManager();

            $em->remove($NAZWAGŁÓWNEJencji);
            $em->flush();
            $result->success = true;
        }

        return new JsonResponse($result);
    }
}
