<?php
// src/OC/PlatformBundle/Entity/Advert.php

namespace OC\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="OC\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

    /**
   * @ORM\Column(name="date", type="datetime")
   * @Assert\DateTime()
   */
  private $date;

  /**
   * @ORM\Column(name="title", type="string", length=255)
   * @Assert\Length(min=10)
   */
  private $title;

  /**
   * @ORM\Column(name="author", type="string", length=255)
   * @Assert\Length(min=2)
   */
  private $author;

  /**
   * @ORM\Column(name="content", type="text")
   * @Assert\NotBlank()
   */
  private $content;

  /**
   * @ORM\OneToOne(targetEntity="OC\PlatformBundle\Entity\ImageHeader", cascade={"persist", "remove"})
   */
  private $imageheader;

  /**
   * @ORM\ManyToMany(targetEntity="OC\PlatformBundle\Entity\ImageContent", cascade={"persist"})
   */
  private $imagecontents;

  /**
   * @ORM\Column(name="published", type="boolean")
   */
  private $published = true;

  /**
   * @ORM\ManyToMany(targetEntity="OC\PlatformBundle\Entity\Category", cascade={"persist"})
   */
  private $categories;

  /**
   * @ORM\Column(name="updated_at", type="datetime", nullable=true)
   */
  private $updatedAt;

  /**
   * @Gedmo\Slug(fields={"title"})
   * @ORM\Column(length=128, unique=true)
   */
  private $slug;

  public function __construct()
  {
    $this->date         = new \Datetime();
   // $this->categories   = new ArrayCollection();
    $this->imagecontents   = new ArrayCollection();
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param \DateTime $date
   * @return Advert
   */
  public function setDate($date)
  {
    $this->date = $date;
    return $this;
  }

  /**
   * @return \DateTime
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * @param string $title
   * @return Advert
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

  /**
   * @param string $author
   * @return Advert
   */
  public function setAuthor($author)
  {
    $this->author = $author;
    return $this;
  }

  /**
   * @return string
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * @param string $content
   * @return Advert
   */
  public function setContent($content)
  {
    $this->content = $content;
    return $this;
  }

  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * @param boolean $published
   * @return Advert
   */
  public function setPublished($published)
  {
    $this->published = $published;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getPublished()
  {
    return $this->published;
  }

  public function setImageHeader(ImageHeader $imageheader = null)
  {
    $this->imageheader = $imageheader;
    return $this;
  }

  public function getImageHeader()
  {
    return $this->imageheader;
  }

  public function addImageContent(ImageContent $imagecontent)
  {
    $this->imagecontents[] = $imagecontent;
    return $this;
  }

  public function removeImageContent(ImageContent $imagecontent)
  {
    $this->imagecontents->removeElement($imagecontent);
  }

  public function getImagecontents()
  {
    return $this->imagecontents;
  }


  public function addCategory(Category $category)
  {
    $this->categories[] = $category;
    return $this;
  }

  public function removeCategory(Category $category)
  {
    $this->categories->removeElement($category);
  }

  public function getCategories()
  {
    return $this->categories;
  }

  /**
   * @ORM\PreUpdate
   */
  public function updateDate()
  {
    $this->setUpdatedAt(new \Datetime());
  }

  public function setUpdatedAt(\Datetime $updatedAt)
  {
    $this->updatedAt = $updatedAt;
    return $this;
  }

  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

}
