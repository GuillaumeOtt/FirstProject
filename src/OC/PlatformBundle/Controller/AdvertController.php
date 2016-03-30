<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;
use OC\PlatformBundle\Form\AdvertDeleteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use OC\PlatformBundle\Beta\BetaListener;

class AdvertController extends Controller
{

  public function translationAction($name)
  {
    return $this->render('OCPlatformBundle:Advert:translation.html.twig', array(
      'name' => $name
    ));
  }

  public function indexAction($page)
  {

    if ($page < 1) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    $nbPerPage=2;

    // Pour récupérer la liste de toutes les annonces : on utilise findAll()
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->getAdverts($page,$nbPerPage);

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts)/$nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page
      ));
    }

    // On donne toutes les informations nécessaires à la vue
    return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page
    ));
  }

  public function viewAction($id)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Pour récupérer une annonce unique : on utilise find()
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    // On vérifie que l'annonce avec cet id existe bien
    if ($advert === null) {
      throw $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }

    // Puis modifiez la ligne du render comme ceci, pour prendre en compte les variables :
    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert));
  }
  

  public function addAction(Request $request)
  {

  ///**
  // * @Security("has_role('ROLE_AUTEUR')")
  // */
        // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
  if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux auteurs.');
  }
    // On crée un objet Advert
    $advert = new Advert();

    // On crée le FormBuilder grâce au service form factory
    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
    $form = $this->get('form.factory')->create(new AdvertType, $advert);

    //$form->handleRequest($request);
    // On vérifie que les valeurs entrées sont correctes
    // (Nous verrons la validation des objets en détail dans le prochain chapitre)
    if ($form->handleRequest($request)->isValid()) {
      // On l'enregistre notre objet $advert dans la base de données, par exemple
 
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // On redirige vers la page de visualisation de l'annonce nouvellement créée
      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
    }

    // À ce stade, le formulaire n'est pas valide car :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
    return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }


  public function add_graphicAction(Request $request)
  {

    ///**
    // * @Security("has_role('ROLE_AUTEUR')")
    // */
    // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
    if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
    // Sinon on déclenche une exception « Accès interdit »
       throw new AccessDeniedException('Accès limité aux auteurs.');
    }
    // On crée un objet Advert
    $advert = new Advert();

    // On crée le FormBuilder grâce au service form factory
    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
    $form = $this->get('form.factory')->create(new AdvertType, $advert);

    //$form->handleRequest($request);
    // On vérifie que les valeurs entrées sont correctes
    // (Nous verrons la validation des objets en détail dans le prochain chapitre)
    if ($form->handleRequest($request)->isValid()) {
      // On l'enregistre notre objet $advert dans la base de données, par exemple
 
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // On redirige vers la page de visualisation de l'annonce nouvellement créée
      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
    }

    // À ce stade, le formulaire n'est pas valide car :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
    return $this->render('OCPlatformBundle:Advert:add_graphic.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function editAction($id, Request $request)
  {
     $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(new AdvertEditType(), $advert);

    if ($form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
    }

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'form'   => $form->createView(),
      'advert' => $advert // Je passe également l'annonce à la vue si jamais elle veut l'afficher
    ));
  }

  public function deleteAction($id, Request $request)
  {
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // On récupère l'entité correspondant à l'id $id
    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    // Si l'annonce n'existe pas, on affiche une erreur 404
    if ($advert == null) {
      throw $this->createNotFoundException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(new AdvertDeleteType(), $advert);


    if ($request->isMethod('POST')) {
      // Si la requête est en POST, on deletera l'article

      $em->remove($advert);
      $em->flush();
      $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');
      return $this->redirect($this->generateUrl('oc_platform_home'));

    }


    return $this->render('OCPlatformBundle:Advert:delete.html.twig',
        array(
            'delte_form' => $form->createView(),
            'advert' => $advert, // Je passe également l'annonce à la vue si jamais elle veut l'afficher
        )
    );

  }


  public function menuAction($limit = 3)
  {
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limit,                  // On sélectionne $limit annonces
        0                        // À partir du premier
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }
}