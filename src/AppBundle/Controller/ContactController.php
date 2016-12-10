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
}
