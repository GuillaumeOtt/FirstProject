<?php
// src/OC/PlatformBundle/Antispam/OCAntispam.php

namespace OC\PlatformBundle\Antispam;

class OCAntispam extends \Twig_Extension
{
  private $mailer;
  private $locale;
  private $minLength;

    // Dans le constructeur, on retire $locale des arguments
  public function __construct(\Swift_Mailer $mailer, $nbForSpam)
  {
    $this->mailer = $mailer;
    $this->nbForSpam = (int) $nbForSpam;
  }

  // Et on ajoute un setter
  public function setLocale($locale)
  {
    $this->locale = $locale;
  }

  /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   */
  public function isSpam($text)
  {
    return strlen($text) < $this->minLength;
  }

    // Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
  public function getFunctions()
  {
    return array(
      'checkIfSpam' => new \Twig_Function_Method($this, 'isSpam')
    );
  }

  // La méthode getName() identifie votre extension Twig, elle est obligatoire
  public function getName()
  {
    return 'OCAntispam';
  }
  
}