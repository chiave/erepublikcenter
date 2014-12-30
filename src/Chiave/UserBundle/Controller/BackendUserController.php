<?php

namespace Chiave\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Chiave\CoreBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Chiave\UserBundle\Document\User;
use Chiave\UserBundle\Form\UserType;

/**
 * Users controller.
 *
 * @Route("/admin/users")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BackendUserController extends BaseController
{
    /**
     * Lists all users.
     *
     * @Route("/", name="chiave_user_users")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getManager();

        $users = $em
                ->getRepository('ChiaveUserBundle:User')
                ->findAll()
        ;

        return array(
            'users' => $users,
        );
    }

    /**
     * @Route("/create", name="chiave_user_user_create")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveUserBundle:BackendUser:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $user = new User();

        $form = $this->createUserForm(
                $user, 'chiave_user_user_create'
        );

        $form->handleRequest($request);

        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $password = substr($tokenGenerator->generateToken(), 0, 12);
        $user->setPassword($password);

        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getManager();

            $citizen = $this
                    ->get('erepublik_citizen_scrobbler')
                    ->updateCitizen(
                    $user
                    )
            ;

            $user->setCitizen($citizen);
            $em->persist($user);

            $em->flush();

            $userManager = $this->container->get('fos_user.user_manager');
            $user->setPlainPassword('test');

            $userManager->updateUser($user, true);

            return $this->redirect(
                            $this->generateUrl('chiave_user_users')
            );
        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/new", name="chiave_user_user_new")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveUserBundle:BackendUser:update.html.twig")
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $form = $this->createUserForm(
                $user, 'chiave_user_user_create'
        );

        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing category.
     *
     * @Route("/{id}/edit", name="chiave_gallery_categories_edit")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendCategories:update.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getManager();

        $category = $em->getRepository('ChiaveGalleryBundle:Categories')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Categories.');
        }

        $form = $this->createCategoryForm(
                $category, 'chiave_gallery_categories_update'
        );

        return array(
            'category' => $category,
            'form' => $form->createView(),
        );
    }

    /**
     * Edits an existing category.
     *
     * @Route("/{id}/update", name="chiave_gallery_categories_update")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getManager();

        $category = $em->getRepository('ChiaveGalleryBundle:Categories')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Categories.');
        }

        $form = $this->createCategoryForm(
                $category, 'chiave_gallery_categories_update'
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_categories_edit', array('id' => $id)));
        }

        return array(
            'category' => $category,
            'form' => $form->createView(),
        );
    }

    /**
     * Deletes category.
     *
     * @Route("/{id}/delete", name="chiave_gallery_categories_delete")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $result = new \stdClass();
        $result->success = false;

        $em = $this->getManager();
        $category = $em->getRepository('ChiaveGalleryBundle:Categories')->find($id);

        if (!$category) {
            // throw $this->createNotFoundException('Unable to find Categories.');
            $result->error = 'Unable to find Categories.';
        } else {
            $em->remove($category);
            $em->flush();

            $result->success = true;
        }

        return new JsonResponse($result);
    }

    /**
     * Creates a form for user.
     *
     * @param User   $user
     * @param string $route
     *
     * @return \Symfony\Component\Form\Form Form for user
     */
    public function createUserForm(User $user, $route)
    {
        return $this->createForm(
                        new UserType(), $user, array(
                    'action' => $this->generateUrl(
                            $route, array(
                        'id' => $user->getId(),
                    )),
                    'method' => 'post',
                        )
        );
    }
}
