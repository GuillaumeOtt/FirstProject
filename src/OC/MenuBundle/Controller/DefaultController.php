<?php

namespace OC\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    
  public function web_blogsAction()
  {

    return $this->render('OCMenuBundle:Default:web_blogs.html.twig');
  
  }

  public function web_socialAction()
  {

    return $this->render('OCMenuBundle:Default:web_social.html.twig');
  
  }

  public function design_graphicAction()
  {
  
    return $this->render('OCMenuBundle:Default:design_graphic.html.twig');
  
  }

  public function design_illustrationAction()
  {
  
    return $this->render('OCMenuBundle:Default:design_illustration.html.twig');
  
  }

  public function design_photoAction()
  {
  
    return $this->render('OCMenuBundle:Default:design_photo.html.twig');
  
  }

  public function contactAction()
  {
  
    return $this->render('OCMenuBundle:Default:contact.html.twig');
  
  }

  public function aboutAction()
  {
  
    return $this->render('OCMenuBundle:Default:about_us.html.twig');
  
  }

}
