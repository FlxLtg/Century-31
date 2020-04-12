<?php
// entity/User.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
  
  
  /** 
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    protected $id;
      
      
  /** 
    * @ORM\Column(type="string") 
    */
    protected $identifiant;
      
      
  /** 
    * @ORM\Column(type="string") 
    */
    protected $mdp;
      
      
  /** 
    * @ORM\Column(type="string") 
    */
    protected $email;
      
      
  /**
    * @var \DateTime
    *
    * @ORM\Column(type="date")
    */ 
    protected $date_inscription; 
   
  /** 
    * @ORM\Column(type="string") 
    */
    protected $role;
  
  
  // ...
    /**
     * Many Users have Many Annonces.
     * @ORM\ManyToMany(targetEntity="Annonce", inversedBy="users")
     * @ORM\JoinTable(name="annonces_users")
     */
    private $annonces;
  
   public function __construct() {
        $this->annonces = new \Doctrine\Common\Collections\ArrayCollection();
    }
  
  
  /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identifiant.
     *
     * @param string $identifiant
     *
     * @return User
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant.
     *
     * @return string
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set mdp.
     *
     * @param string $mdp
     *
     * @return User
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp.
     *
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateInscription.
     *
     * @param \DateTime $dateInscription
     *
     * @return User
     */
    public function setDateInscription($dateInscription)
    {
        $this->date_inscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription.
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
  
    
    /**
     * Add annonce.
     *
     * @param \Entity\Annonce $annonce
     *
     * @return User
     */
    public function addAnnonce(\Entity\Annonce $annonce)
    {
        $this->annonces[] = $annonce;

        return $this;
    }

    /**
     * Remove annonce.
     *
     * @param \Entity\Annonce $annonce
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAnnonce(\Entity\Annonce $annonce)
    {
        return $this->annonces->removeElement($annonce);
    }

    /**
     * Get annonces.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }
}
  
  
  