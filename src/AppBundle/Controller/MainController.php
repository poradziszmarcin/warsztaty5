<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    /**
     * @Route("/")
     * @Template()
     */
    public function mainAction()
    {
        $contacts = $this->getDoctrine()->getRepository("AppBundle:Contact")->findAll();
        return ["contacts"=>$contacts];
    }
}
