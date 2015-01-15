<?php

namespace Gajdaw\BDDTutorial\GeographyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gajdaw\BDDTutorial\GeographyBundle\Entity\Beach;
use Gajdaw\BDDTutorial\GeographyBundle\Form\BeachType;

/**
 * Beach controller.
 *
 * @Route("/beach")
 */
class BeachController extends Controller
{

    /**
     * Lists all Beach entities.
     *
     * @Route("/pager/{page}", name="beach", requirements={"page": "\d+"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page = 1)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM GajdawBDDTutorialGeographyBundle:Beach p";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            3
        );

        // parameters to template
        return array(
            'pagination' => $pagination
        );
    }
    /**
     * Creates a new Beach entity.
     *
     * @Route("/", name="beach_create")
     * @Method("POST")
     * @Template("GajdawBDDTutorialGeographyBundle:Beach:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Beach();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('beach_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Beach entity.
     *
     * @param Beach $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Beach $entity)
    {
        $form = $this->createForm(new BeachType(), $entity, array(
            'action' => $this->generateUrl('beach_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Beach entity.
     *
     * @Route("/new", name="beach_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Beach();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Beach entity.
     *
     * @Route("/{id}", name="beach_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Beach')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Beach entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Beach entity.
     *
     * @Route("/{id}/edit", name="beach_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Beach')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Beach entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Beach entity.
    *
    * @param Beach $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Beach $entity)
    {
        $form = $this->createForm(new BeachType(), $entity, array(
            'action' => $this->generateUrl('beach_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Beach entity.
     *
     * @Route("/{id}", name="beach_update")
     * @Method("PUT")
     * @Template("GajdawBDDTutorialGeographyBundle:Beach:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Beach')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Beach entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('beach_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Beach entity.
     *
     * @Route("/{id}", name="beach_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Beach')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Beach entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('beach'));
    }

    /**
     * Creates a form to delete a Beach entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('beach_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
