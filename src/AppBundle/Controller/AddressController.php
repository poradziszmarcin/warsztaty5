<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddressController
 * @package AppBundle\Controller
 * @Route("/address")
 */
class AddressController extends Controller
{

    /**
     * @Route("/add/{id}")
     * @Template()
     */
    public function addAction(Request $request, $id)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $contact = $this->getDoctrine()->getRepository("AppBundle:Contact")->find($id);
            $contact->addAddress($address);
            $address->setContact($contact);
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute("app_contact_show",["id"=>$contact->getId()]);

        }

        return ["form"=>$form->createView()];

    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/edit/{id}{cid}")
     * @Template()
     */
    public function editAction(Request $request,$id,$cid)
    {
        $address = $this->getDoctrine()->getRepository("AppBundle:Address")->find($id);


        if ($address ==false)
        {
            throw $this->createNotFoundException("nie ma takiego adresu");
        }

        $form = $this->createForm(AddressType::class,$address);
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
        $address = $this->getDoctrine()->getRepository("AppBundle:Address")->find($id);

        if ($address == false)
        {
            throw $this->createNotFoundException("nie ma takiego adresu");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute("app_contact_show",["id"=>$cid]);
    }
}
