<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Email;
use AppBundle\Form\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EmailController
 * @package AppBundle\Controller
 * @Route("/Email")
 */
class EmailController extends Controller
{

    /**
     * @Route("/add/{id}")
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $Email = new Email();
        $form = $this->createForm(EmailType::class, $Email);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->find($id);
            $contact->addEmail($Email);
            $Email->setContact($contact);
            $em = $this->getDoctrine()->getManager();
            $em->persist($Email);
            $em->flush();

            return $this->redirectToRoute("app_contact_show",["id"=>$contact->getId()]);

        }

        return ["form"=>$form->createView()];

    }

    /**
     *
     * @
     * @Route("/edit/{id}{cid}")
     * @Template()
     */
    public function editAction(Request $request,$id,$cid)
    {
        $Email = $this->getDoctrine()->getRepository("AppBundle:Email")->find($id);


        if ($Email ==false)
        {
            throw $this->createNotFoundException("nie ma takiego adresu");
        }

        $form = $this->createForm(EmailType::class,$Email);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("app_contact_show",["id"=>$cid]);
        }

        return ["form"=>$form->createView()];
    }


    /**
     * @param $id
     * @Route("/remove/{id}{cid}")
     */
    public function removeAction($id,$cid)
    {
        $Email = $this->getDoctrine()->getRepository("AppBundle:Email")->find($id);

        if ($Email == false)
        {
            throw $this->createNotFoundException("nie ma takiego adresu");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($Email);
        $em->flush();

        return $this->redirectToRoute("app_contact_show",["id"=>$cid]);
    }
}
