<?php
// src/OC/PlatformBundle/Entity/Category.php

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Category
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="name", type="string", length=255)
   */
  private $name;

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param string $name
   * @return Category
   */
  public function setName($name)
  {
    $this->name = $name;
    return $this;
  }


  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}