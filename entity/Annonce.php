<?php
// entity/Annonce.php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="annonce")
 */
class Annonce
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
    protected $titre;
      
      
  /** 
    * @ORM\Column(type="string") 
    */
    protected $description;
    
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $prix;
      
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $surface;
  
  
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $surface_total;
  
      
      
  /**
    * @var \DateTime
    *
    * @ORM\Column(type="date")
    */ 
    protected $date_disponible;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $type_propriete;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $type_appartement;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $type_contrat;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $ameublement;
  
  
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $etage;
  
  
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $nombre_pieces;
  
  
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $nombre_salle_bain;
  
  
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $nombre_salle_eau;
  
  
  /** 
    * @ORM\Column(type="integer") 
    */
    protected $nombre_chambre;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $piscine;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $jacuzzi;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $ascenseur;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $salle_sport;
  
  
  /** 
    * @ORM\Column(type="string") 
    */
    protected $place_parking;
  
  
  /**
    * @var \DateTime
    *
    * @ORM\Column(type="date")
    */ 
    protected $date_publication; 
  
  
    //ici c'est bien une liaison OneToMany !\\ 
   /**
     * One Annonce have Many Images.
     * @ORM\ManyToMany(targetEntity="Image")
     * @ORM\JoinTable(name="annonce_image",
     *      joinColumns={@ORM\JoinColumn(name="annonce_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $image;
  
    
    /**
     * Many Annonces have Many User.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="annonces")
     * @ORM\JoinTable(name="annonces_users")
     */
    private $users;

    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre.
     *
     * @param string $titre
     *
     * @return Annonce
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre.
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Annonce
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
  
    /**
     * Set prix.
     *
     * @param int $prix
     *
     * @return Annonce
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set surface.
     *
     * @param int $surface
     *
     * @return Annonce
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface.
     *
     * @return int
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set surfaceTotal.
     *
     * @param int $surfaceTotal
     *
     * @return Annonce
     */
    public function setSurfaceTotal($surfaceTotal)
    {
        $this->surface_total = $surfaceTotal;

        return $this;
    }

    /**
     * Get surfaceTotal.
     *
     * @return int
     */
    public function getSurfaceTotal()
    {
        return $this->surface_total;
    }

    /**
     * Set dateDisponible.
     *
     * @param \DateTime $dateDisponible
     *
     * @return Annonce
     */
    public function setDateDisponible($dateDisponible)
    {
        $this->date_disponible = $dateDisponible;

        return $this;
    }

    /**
     * Get dateDisponible.
     *
     * @return \DateTime
     */
    public function getDateDisponible()
    {
        return $this->date_disponible;
    }

    /**
     * Set typePropriete.
     *
     * @param string $typePropriete
     *
     * @return Annonce
     */
    public function setTypePropriete($typePropriete)
    {
        $this->type_propriete = $typePropriete;

        return $this;
    }

    /**
     * Get typePropriete.
     *
     * @return string
     */
    public function getTypePropriete()
    {
        return $this->type_propriete;
    }

    /**
     * Set typeAppartement.
     *
     * @param string $typeAppartement
     *
     * @return Annonce
     */
    public function setTypeAppartement($typeAppartement)
    {
        $this->type_appartement = $typeAppartement;

        return $this;
    }

    /**
     * Get typeAppartement.
     *
     * @return string
     */
    public function getTypeAppartement()
    {
        return $this->type_appartement;
    }

    /**
     * Set typeContrat.
     *
     * @param string $typeContrat
     *
     * @return Annonce
     */
    public function setTypeContrat($typeContrat)
    {
        $this->type_contrat = $typeContrat;

        return $this;
    }

    /**
     * Get typeContrat.
     *
     * @return string
     */
    public function getTypeContrat()
    {
        return $this->type_contrat;
    }

    /**
     * Set ameublement.
     *
     * @param string $ameublement
     *
     * @return Annonce
     */
    public function setAmeublement($ameublement)
    {
        $this->ameublement = $ameublement;

        return $this;
    }

    /**
     * Get ameublement.
     *
     * @return string
     */
    public function getAmeublement()
    {
        return $this->ameublement;
    }

    /**
     * Set etage.
     *
     * @param int $etage
     *
     * @return Annonce
     */
    public function setEtage($etage)
    {
        $this->etage = $etage;

        return $this;
    }

    /**
     * Get etage.
     *
     * @return int
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * Set nombrePieces.
     *
     * @param int $nombrePieces
     *
     * @return Annonce
     */
    public function setNombrePieces($nombrePieces)
    {
        $this->nombre_pieces = $nombrePieces;

        return $this;
    }

    /**
     * Get nombrePieces.
     *
     * @return int
     */
    public function getNombrePieces()
    {
        return $this->nombre_pieces;
    }

    /**
     * Set nombreSalleBain.
     *
     * @param int $nombreSalleBain
     *
     * @return Annonce
     */
    public function setNombreSalleBain($nombreSalleBain)
    {
        $this->nombre_salle_bain = $nombreSalleBain;

        return $this;
    }

    /**
     * Get nombreSalleBain.
     *
     * @return int
     */
    public function getNombreSalleBain()
    {
        return $this->nombre_salle_bain;
    }

    /**
     * Set nombreSalleEau.
     *
     * @param int $nombreSalleEau
     *
     * @return Annonce
     */
    public function setNombreSalleEau($nombreSalleEau)
    {
        $this->nombre_salle_eau = $nombreSalleEau;

        return $this;
    }

    /**
     * Get nombreSalleEau.
     *
     * @return int
     */
    public function getNombreSalleEau()
    {
        return $this->nombre_salle_eau;
    }

    /**
     * Set nombreChambre.
     *
     * @param int $nombreChambre
     *
     * @return Annonce
     */
    public function setNombreChambre($nombreChambre)
    {
        $this->nombre_chambre = $nombreChambre;

        return $this;
    }

    /**
     * Get nombreChambre.
     *
     * @return int
     */
    public function getNombreChambre()
    {
        return $this->nombre_chambre;
    }

    /**
     * Set piscine.
     *
     * @param string $piscine
     *
     * @return Annonce
     */
    public function setPiscine($piscine)
    {
        $this->piscine = $piscine;

        return $this;
    }

    /**
     * Get piscine.
     *
     * @return string
     */
    public function getPiscine()
    {
        return $this->piscine;
    }

    /**
     * Set jacuzzi.
     *
     * @param string $jacuzzi
     *
     * @return Annonce
     */
    public function setJacuzzi($jacuzzi)
    {
        $this->jacuzzi = $jacuzzi;

        return $this;
    }

    /**
     * Get jacuzzi.
     *
     * @return string
     */
    public function getJacuzzi()
    {
        return $this->jacuzzi;
    }

    /**
     * Set ascenseur.
     *
     * @param string $ascenseur
     *
     * @return Annonce
     */
    public function setAscenseur($ascenseur)
    {
        $this->ascenseur = $ascenseur;

        return $this;
    }

    /**
     * Get ascenseur.
     *
     * @return string
     */
    public function getAscenseur()
    {
        return $this->ascenseur;
    }

    /**
     * Set salleSport.
     *
     * @param string $salleSport
     *
     * @return Annonce
     */
    public function setSalleSport($salleSport)
    {
        $this->salle_sport = $salleSport;

        return $this;
    }

    /**
     * Get salleSport.
     *
     * @return string
     */
    public function getSalleSport()
    {
        return $this->salle_sport;
    }

    /**
     * Set placeParking.
     *
     * @param string $placeParking
     *
     * @return Annonce
     */
    public function setPlaceParking($placeParking)
    {
        $this->place_parking = $placeParking;

        return $this;
    }

    /**
     * Get placeParking.
     *
     * @return string
     */
    public function getPlaceParking()
    {
        return $this->place_parking;
    }
  

    /**
     * Set datePublication.
     *
     * @param \DateTime $datePublication
     *
     * @return Annonce
     */
    public function setDatePublication($datePublication)
    {
        $this->date_publication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication.
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->date_publication;
    }
  
    
    /**
     * Add image.
     *
     * @param \Entity\Image $image
     *
     * @return Annonce
     */
    public function addImage(\Entity\Image $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\Entity\Image $image)
    {
        return $this->image->removeElement($image);
    }

    /**
     * Get image.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImage()
    {
        return $this->image;
    }
  
    
    /**
     * Add user.
     *
     * @param \Entity\User $user
     *
     * @return Annonce
     */
    public function addUser(\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \Entity\User $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(\Entity\User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
  
}
