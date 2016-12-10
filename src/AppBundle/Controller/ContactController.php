<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactController
 * @package AppBundle\Controller
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     *
     * @Route("/show/{id}")
     * @Template()
     *
     */
    public function showAction ($id)
    {
        $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->find($id);
        return["contact"=>$contact];
    }

    /**
     * @param Request $request
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            $id = $contact->getId();
            return $this->redirectToRoute("app_contact_show",["id"=>$id]);
        }

        return ["form" =>$form->createView()];
    }

    /**
     * @Route("/edit/{id}")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->find($id);

        if ($contact == false)
        {
            throw $this->createNotFoundException("nie ma takiego kontaktu");
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("app_contact_show",["id"=>$contact->getId()]);
        }
        return ["form"=>$form->createView()];
    }

    /**
     * @param $id
     * @Route("/remove/{id}")
     */
    public function removeAction($id)
    {
        $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->find($id);

        if ($contact == false)
        {
            throw $this->createNotFoundException("nie znaleziono kontaktu");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();
        return $this->redirectToRoute("app_main_main");
    }


}
