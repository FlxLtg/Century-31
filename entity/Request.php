<?php

namespace Entity;

use Entity\User;

class Request
{

  private $em;

  private $post;

  private $get;

  private $user;
  
  private $path;

  public function __construct($get = null, $post = null, $em = null, User $user = null, $path = null) {

    $this->get=$get;
    $this->post=$post;
    $this->em=$em;
    $this->user=$user;
    $this->path=$path;

  }

  public function setEm($em)
  {
      $this->em = $em;
  }

  public function getEm()
  {
    return $this->em;
  }

  public function setPost($post)
  {
    $this->post = $post;
  }

  public function getPost(){
    return $this->post;
  }

  public function setGet($get)
  {
    $this->get = $get;
  }

  public function getGet()
  {
    return $this->get;
  }

  public function getUser()
  {
    return $this->user;
  }

  public function setUser(User $user=null)
  {
    $this->user=$user;
  }
  
  public function setPath($path)
  {
    $this->path=$path;
  }
  
  public function getPath()
  {
    return $this->path;
  }

}