<?php

namespace Gajdaw\BDDTutorial\GeographyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gajdaw\BDDTutorial\GeographyBundle\Entity\Desert;
use Gajdaw\BDDTutorial\GeographyBundle\Form\DesertType;

/**
 * Desert controller.
 *
 * @Route("/desert")
 */
class DesertController extends Controller
{

    /**
     * Lists all Desert entities.
     *
     * @Route("/{page}", name="desert")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page = 1)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT d FROM GajdawBDDTutorialGeographyBundle:Desert d";
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
     * Creates a new Desert entity.
     *
     * @Route("/", name="desert_create")
     * @Method("POST")
     * @Template("GajdawBDDTutorialGeographyBundle:Desert:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Desert();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('desert_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Desert entity.
     *
     * @param Desert $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Desert $entity)
    {
        $form = $this->createForm(new DesertType(), $entity, array(
            'action' => $this->generateUrl('desert_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Desert entity.
     *
     * @Route("/new", name="desert_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Desert();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Desert entity.
     *
     * @Route("/{id}", name="desert_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Desert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Desert entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Desert entity.
     *
     * @Route("/{id}/edit", name="desert_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Desert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Desert entity.');
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
    * Creates a form to edit a Desert entity.
    *
    * @param Desert $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Desert $entity)
    {
        $form = $this->createForm(new DesertType(), $entity, array(
            'action' => $this->generateUrl('desert_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Desert entity.
     *
     * @Route("/{id}", name="desert_update")
     * @Method("PUT")
     * @Template("GajdawBDDTutorialGeographyBundle:Desert:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Desert')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Desert entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('desert_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Desert entity.
     *
     * @Route("/{id}", name="desert_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GajdawBDDTutorialGeographyBundle:Desert')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Desert entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('desert'));
    }

    /**
     * Creates a form to delete a Desert entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('desert_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
