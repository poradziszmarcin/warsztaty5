<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Form\PhoneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PhoneController
 * @package AppBundle\Controller
 * @Route("/Phone")
 */
class PhoneController extends Controller
{

    /**
     * @Route("/add/{id}")
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $Phone = new Phone();
        $form = $this->createForm(PhoneType::class, $Phone);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->find($id);
            $contact->addPhone($Phone);
            $Phone->setContact($contact);
            $em = $this->getDoctrine()->getManager();
            $em->persist($Phone);
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
        $Phone = $this->getDoctrine()->getRepository("AppBundle:Phone")->find($id);


        if ($Phone ==false)
        {
            throw $this->createNotFoundException("nie ma takiego adresu");
        }

        $form = $this->createForm(PhoneType::class,$Phone);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("app_contact_show",["id"=>$cid]);
        }

        return ["form"=>$form->createView()];
    }


    /**
     *
     * @Route("/remove/{id}{cid}")
     */
    public function removeAction($id,$cid)
    {
        $Phone = $this->getDoctrine()->getRepository("AppBundle:Phone")->find($id);

        if ($Phone == false)
        {
            throw $this->createNotFoundException("nie ma takiego adresu");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($Phone);
        $em->flush();

        return $this->redirectToRoute("app_contact_show",["id"=>$cid]);
    }
}
