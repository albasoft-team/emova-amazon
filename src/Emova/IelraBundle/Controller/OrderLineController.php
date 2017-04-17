<?php

namespace Emova\IelraBundle\Controller;

use Emova\IelraBundle\Entity\OrderLine;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Orderline controller.
 *
 * @Route("orderline")
 */
class OrderLineController extends Controller
{
    /**
     * Lists all orderLine entities.
     *
     * @Route("/", name="orderline_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $orderLines = $em->getRepository('IelraBundle:OrderLine')->getOrderLineByStatut('arrive');
        $orderLinesLivre = $em->getRepository('IelraBundle:OrderLine')->getOrderLineByStatut('livree');
        $orderLinesPre = $em->getRepository('IelraBundle:OrderLine')->getOrderLineByStatut('preparee');
        $orderLinesEnv = $em->getRepository('IelraBundle:OrderLine')->getOrderLineByStatut('envoyee');
        $result = $em->getRepository('IelraBundle:OrderLine')->countNumberCommande();
        return $this->render('orderline/index.html.twig', array(
            'orderLines' => $orderLines,
            'orderLinesL' => $orderLinesLivre,
            'orderLinesP' => $orderLinesPre,
            'orderLinesE' => $orderLinesEnv,
            'nombre' => $result
        ));
    }

    /**
     * @Route("/all", name="orderline_all")
     * @Method("GET")
     */
    public function allCommandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $orderLines = $em->getRepository('IelraBundle:OrderLine')->getOrderLineBy('arrive');
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
//        $paginator  = $this->get('knp_paginator');
        $result = $em->getRepository('IelraBundle:OrderLine')->countNumberCommande();
//        $orderLines = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1)/*page number*/,
//            $request->query->getInt('limit', 3)/*limit per page*/
//        );
        return $this->render('orderline/all.html.twig', array(
            'orderLines' => $orderLines,
            'nombre' => $result
        ));
    }
    /**
     * @Route("/updateStatus", name="update_status")
     * @Method({"POST"})
     */
    public function updateStatus(Request $request)
    {
        $data = json_decode($request->getContent(), TRUE);
        $id = $data['id'];
        $em = $this->getDoctrine()->getManager();
        $orderline = $em->getRepository('IelraBundle:OrderLine')->find($id);
        $oldstatus = $orderline->getStatut();
        if (!$orderline) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        if ($oldstatus == 'arrive') {
            $orderline->setStatut('preparee');
        }
        if ($oldstatus == 'preparee') {
            $orderline->setStatut('envoyee');
        }
        if ($oldstatus == 'envoyee') {
            $orderline->setStatut('livree');
        }

//        if ($oldstatus == 'livree')
//        {
//            $orderline->setStatut('arrive');
//        }
        $em->flush();
        $orderLines = $em->getRepository('IelraBundle:OrderLine')->findAll();
        $serializer = $this->get('serializer');
        $arrayResult = $serializer->normalize($orderLines);
        return new JsonResponse($arrayResult);
    }

    /**
     * @Route("/allCmd/{statut}", name="orderline_allCmd")
     * @Method("GET")
     */
    public function allCommandesAction($statut)
    {
        $em = $this->getDoctrine()->getManager();
        $orderLines = $em->getRepository('IelraBundle:OrderLine')->getOrderLineBy($statut);
//        var_dump($orderLines);
        $serializer = $this->get('serializer');
        $arrayResult = $serializer->normalize($orderLines);

        return new JsonResponse($arrayResult);
    }
    /**
     * @Route("/allCmdr", name="all")
     * @Method("GET")
     */
    public function getAll()
    {
        $em = $this->getDoctrine()->getManager();
        $orderLines = $em->getRepository('IelraBundle:OrderLine')->findAll();
//        var_dump($orderLines);
        $serializer = $this->get('serializer');
        $arrayResult = $serializer->normalize($orderLines);

        return new JsonResponse($arrayResult);
    }

     /**
     * @Route("/getNumberCmd", name="orderline_Cmd")
     * @Method("GET")
     */
    public function getNumberCmd()
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('IelraBundle:OrderLine')->countNumberCommande();
        $serializer = $this->get('serializer');
        $arrayResult = $serializer->normalize($result);

        return new JsonResponse($arrayResult);
    }


    /**
     * Creates a new orderLine entity.
     *
     * @Route("/new", name="orderline_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $orderLine = new Orderline();
        $form = $this->createForm('Emova\IelraBundle\Form\OrderLineType', $orderLine);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderLine);
            $em->flush($orderLine);

            return $this->redirectToRoute('orderline_show', array('id' => $orderLine->getId()));
        }

        return $this->render('orderline/new.html.twig', array(
            'orderLine' => $orderLine,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a orderLine entity.
     *
     * @Route("/{id}", name="orderline_show")
     * @Method("GET")
     */
    public function showAction(OrderLine $orderLine)
    {
        $deleteForm = $this->createDeleteForm($orderLine);

        return $this->render('orderline/show.html.twig', array(
            'orderLine' => $orderLine,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing orderLine entity.
     *
     * @Route("/{id}/edit", name="orderline_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, OrderLine $orderLine)
    {
        $deleteForm = $this->createDeleteForm($orderLine);
        $editForm = $this->createForm('Emova\IelraBundle\Form\OrderLineType', $orderLine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orderline_edit', array('id' => $orderLine->getId()));
        }

        return $this->render('orderline/edit.html.twig', array(
            'orderLine' => $orderLine,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a orderLine entity.
     *
     * @Route("/{id}", name="orderline_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, OrderLine $orderLine)
    {
        $form = $this->createDeleteForm($orderLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($orderLine);
            $em->flush();
        }

        return $this->redirectToRoute('orderline_index');
    }

    /**
     * Creates a form to delete a orderLine entity.
     *
     * @param OrderLine $orderLine The orderLine entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(OrderLine $orderLine)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('orderline_delete', array('id' => $orderLine->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
