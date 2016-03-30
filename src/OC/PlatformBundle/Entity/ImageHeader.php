<?php
// src/OC/PlatformBundle/Entity/Image

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Entity\ImageHeaderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ImageHeader
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="url", type="string", length=255)
   */
  private $url;

  /**
   * @ORM\Column(name="alt", type="string", length=255)
   */
  private $alt;

  private $file;

  private $tempFilename;

  /**
   * @ORM\Column(name="title", type="string", length=255)
   */
  private $title;

  /**
   * @ORM\Column(name="startX", type="integer")
   */
  private $startX;

  /**
   * @ORM\Column(name="startY", type="integer")
   */
  private $startY;

  /**
   * @ORM\Column(name="sizeX", type="integer")
   */
  private $sizeX;

  /**
   * @ORM\Column(name="sizeY", type="integer")
   */
  private $sizeY;

  /**
   * Get startX
   *
   * @return integer 
   */
  public function getStartX()
  {
      return $this->startX;
  }

  /**
   * Get startY
   *
   * @return integer 
   */
  public function getStartY()
  {
      return $this->startY;
  }

  /**
   * Get sizeX
   *
   * @return integer 
   */
  public function getSizeX()
  {
      return $this->sizeX;
  }

  /**
   * Get sizeY
   *
   * @return integer 
   */
  public function getSizeY()
  {
      return $this->sizeY;
  }

  /**
   * Set startX
   *
   * @param integer $startX
   * @return integer 
   */
  public function setStartX($startX)
  {
      $this->startX = $startX;
      return $this;
  }

  /**
   * Set startY
   *
   * @param integer $startY
   * @return integer 
   */
  public function setStartY($startY)
  {
      $this->startY = $startY;
      return $this;
  }

  /**
   * Set sizeX
   *
   * @param integer $sizeX
   * @return integer 
   */
  public function setSizeX($sizeX)
  {
      $this->sizeX = $sizeX;
      return $this;
  }

  /**
   * Set sizeY
   *
   * @param integer $sizeY
   * @return integer 
   */
  public function setSizeY($sizeY)
  {
      $this->sizeY = $sizeY;
      return $this;
  }


  /**
   * @param string $title
   * @return ImageHeader
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }


  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }


  public function getFile()
  {
    return $this->file;
  }

  public function setFile(UploadedFile $file = null)
  {
    $this->file = $file;

    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->url) {
      // On sauvegarde l'extension du fichier pour le supprimer plus tard
      $this->tempFilename = $this->url;

      // On réinitialise les valeurs des attributs url et alt
      $this->url = null;
      $this->alt = null;
      $this->startX = null;
      $this->startY = null;
      $this->sizeX = null;
      $this->sizeY = null;

    }
  }

   /**
   * @ORM\PrePersist()
   * @ORM\PreUpdate()
   */
  public function preUpload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif)
    if (null === $this->file) {
      return;
    }

    // Le nom du fichier est son id, on doit juste stocker également son extension
    // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
    $this->url = $this->file->guessExtension();

    // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
    $this->alt = $this->file->getClientOriginalName();
  }

  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */
  public function upload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif)
    if (null === $this->file) {
      return;
    }

    // Si on avait un ancien fichier, on le supprime
    if (null !== $this->tempFilename) {
      $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
      if (file_exists($oldFile)) {
        unlink($oldFile);
      }
    }

    // On déplace le fichier envoyé dans le répertoire de notre choix
    $this->file->move(
      $this->getUploadRootDir(), // Le répertoire de destination
      $this->id.'.'.$this->url   // Le nom du fichier à créer, ici « id.extension »
    );
  }

  public function getWebPath()
  {
    return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
  }

  /**
   * @ORM\PreRemove()
   */
  public function preRemoveUpload()
  {
    // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
    $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
  }

  /**
   * @ORM\PostRemove()
   */
  public function removeUpload()
  {
    // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
    if (sfile_exists($this->tempFilename)) {
      // On supprime le fichier
      unlink($this->tempFilename);
    }
  }

  public function getUploadDir()
  {
    // On retourne le chemin relatif vers l'image pour un navigateur
    return 'uploads/img';
  }

  protected function getUploadRootDir()
  {
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
  }

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * Set url
   *
   * @param string $url
   * @return Image
   */
  public function setUrl($url)
  {
      $this->url = $url;

      return $this;
  }

  /**
   * Get url
   *
   * @return string 
   */
  public function getUrl()
  {
      return $this->url;
  }

  /**
   * Set alt
   *
   * @param string $alt
   * @return Image
   */
  public function setAlt($alt)
  {
      $this->alt = $alt;

      return $this;
  }

  /**
   * Get alt
   *
   * @return string 
   */
  public function getAlt()
  {
      return $this->alt;
  }
}
