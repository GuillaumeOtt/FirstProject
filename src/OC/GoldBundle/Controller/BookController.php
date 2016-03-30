<?php

namespace OC\GoldBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OCGoldBundle:Default:index.html.twig', array('name' => $name));
    }
}
