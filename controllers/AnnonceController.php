<?php

namespace Controllers;

use Entity\Annonce;
use Entity\Request;
use Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;


require 'email.php';
require 'bootstrap.php';


class AnnonceController extends Controller
{
 
 public function index($request)
 {
   $user = $request->getUser();   
   $offset = 1;
   $limit = 12;
     
   $em = $request->GetEm();
   $qb = $em->createQueryBuilder();  
   $qb->select('a')
      ->from('Entity\Annonce', 'a')
      ->orderBy('a.id', 'DESC')
      ->setFirstResult($offset)
      ->setMaxResults($limit);
   
   $query = $qb->getQuery();
   $lastAnnonces = $query->getResult();
   $paginator = new Paginator($query, $fetchJoinCollection = true);
   
   
  $em = $request->GetEm(); // CETTE REQUETE PERMET D'OBTENIR DYNAMIQUEMENT LE NOMBRE DE PAGE POUR LA NAVBAR DEDIE
  $qb = $em->createQueryBuilder();  
  $qb->select('a')
     ->from('Entity\Annonce', 'a')
     ->where('a.type_contrat = ?1')
     ->setParameter(1, 'location');
   
  $query = $qb->getQuery();
  $annoncesLocation = $query->getResult();
  $nombreLocation = count($annoncesLocation); 
  $paginator = new Paginator($query, $fetchJoinCollection = true);
  
  $em = $request->GetEm(); // CETTE REQUETE PERMET D'OBTENIR DYNAMIQUEMENT LE NOMBRE DE PAGE POUR LA NAVBAR DEDIE
  $qb = $em->createQueryBuilder();  
  $qb->select('a')
     ->from('Entity\Annonce', 'a')
     ->where('a.type_contrat = ?1')
     ->setParameter(1, 'vente');
   
  $query = $qb->getQuery();
  $annoncesVente = $query->getResult();
  $nombreVente = count($annoncesVente);
  $paginator = new Paginator($query, $fetchJoinCollection = true);
 
   
   echo $this->twig->render('index.html', [
     "annonces" => $lastAnnonces,
     "nombreLocation" => $nombreLocation,
     "nombreVente" => $nombreVente,
     "user" => $user,
   ]);
 }
  
 public function list($request)
 {
  $user = $request->getUser();   
   
  echo $this->twig->render('listAnnonce.html', 
      [
        "user" => $user,
      ]
      );
 }
   
 public function show($request) 
 {
   $annonce = $request->getEm()->getRepository('Entity\Annonce')->find($_GET['annonceID']);
   $images = $annonce->getImage();
   $imageFirst = $annonce->getImage([0]);
   $user = $request->getUser();
   echo $this->twig->render('annonce.html',
                           [
                             "annonce" => $annonce,
                             "images" => $images,
                             'user' => $user,
                             'imageFirst' => $imageFirst,
                           ]);
 }
  
 public function new($request) 
 {
   $user = $request->getEm()->getRepository('Entity\User')->find($_SESSION['id']);
   
   echo $this->twig->render('newAnnonce.html',
                           [
                             'user' => $user,
                           ]
                           );
 }
  
 public function create($request)
 {
        
   if (isset($_POST['submit'])) {
        $dtDisponible = new \DateTime($_POST['date_dispo_annonce']); //on instancie un nouvel objet date pour pouvoir le traiter comme tel et non comme un string
        $dtPublication = new \DateTime();
         
        $annonce= new Annonce;
        $annonce->setTitre($_POST['titre_annonce']);
        $annonce->setDescription($_POST['description_annonce']);
        $annonce->setSurface($_POST['surface_annonce']);
        $annonce->setSurfaceTotal($_POST['surface_total_annonce']);
        $annonce->setDateDisponible($_POST['date_dispo_annonce']); //// a supprimer car doublon
        $annonce->setTypePropriete($_POST['type_propriete']);
        $annonce->setTypeContrat($_POST['type_contrat']);
        $annonce->setTypeAppartement(isset($_POST['type_annonce_appartement']));
        $annonce->setAmeublement(isset($_POST['ameublement_annonce']));
        $annonce->setEtage(isset($_POST['etage_annonce']));
        $annonce->setNombrePieces($_POST['nombre_pieces_annonce']);
        $annonce->setNombreSalleBain($_POST['nombre_salle_bain_annonce']);
        $annonce->setNombreSalleEau($_POST['nombre_salle_eau_annonce']);
        $annonce->setNombreChambre($_POST['nombre_chambre_annonce']);
        $annonce->setPiscine(isset($_POST['piscine_annonce']));
        $annonce->setJacuzzi(isset($_POST['jacuzzi_annonce']));
        $annonce->setAscenseur(isset($_POST['ascenseur_annonce']));
        $annonce->setSalleSport(isset($_POST['salle_de_sport_annonce']));
        $annonce->setPlaceParking(isset($_POST['place_de_parking_annonce']));
        $annonce->setPrix($_POST['prix_annonce']);
        $annonce->setDateDisponible($dtDisponible);
        $annonce->setDatePublication($dtPublication);
          
           echo "<pre>";var_dump($_FILES);
//            $fileDestination = "imagesAnnonce/test_pter.toto";
//            move_uploaded_file($_FILES['image']['tmp_name'][1],$fileDestination); die('fin de test');
           foreach ($_FILES['image']['name'] as $key => $fileName) {
             
           $file = $_FILES['image'];   
           $fileName = $_FILES['image']['name'][$key]; // equivaut a $file['name']
           $fileTmpName = $_FILES['image']['tmp_name'][$key];
           $fileSize = $_FILES['image']['size'][$key];
           $fileType = $_FILES['image']['type'][$key];
           $fileError = $_FILES['image']['error'][$key];
           list($fileNameWithoutExtension, $fileExtension) = explode(".", $fileName); // permet de séparer fileName en deux et de dissocier le nom de l'extension.
           $fileActualExtension = strtolower($fileExtension);
           $fileExtensionAllowed = ['jpg', 'jpeg', 'png'];
             
          $maxDim = 1400;
          list($width, $height, $type, $attr) = getimagesize($fileTmpName);
          if ($width > $maxDim || $height > $maxDim) {
              $target_filename = $fileTmpName;
              $ratio = $width/$height;
              if($ratio > 1) {
                  $new_width = $maxDim;
                  $new_height = $maxDim/$ratio;
              } else {
                  $new_width = $maxDim*$ratio;
                  $new_height = $maxDim;
              }
              $src = imagecreatefromstring(file_get_contents($fileTmpName));
              $dst = imagecreatetruecolor($new_width, $new_height);
              imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
              imagedestroy($src);
              imagepng($dst, $target_filename); // adjust format as needed
              imagedestroy($dst);
          }
             
            if (is_uploaded_file($fileTmpName)) {
              
             if (in_array($fileActualExtension, $fileExtensionAllowed)) {
               
                if ($fileError === 0){
                  
                    if ($fileSize < 50000){
                      
                      $fileNameNew = uniqid('', true).".".$fileExtension;
                      $fileDestination = "imagesAnnonce/".$fileNameNew;
                      move_uploaded_file($fileTmpName, $fileDestination); // Envoie l'image du fichier temporaire vers le fichier définitif
                      
                      $newImageInBdd = new Image;
                      $newImageInBdd->SetNom($fileNameNew); // ATTRIBUE A NOTRE OBJET IMAGE LE NOM DE L'IMAGE ENREGISTRER DANS NOTRE DOSSIER imageAnnoncea
                      $request->getEm()->persist($newImageInBdd);
                      $request->getEm()->flush();
              
                      $annonce->addImage($newImageInBdd);    // AJOUTER CHAQUE IMAGE A L'ANNONCE
                      $request->getEm()->persist($annonce);
                      $request->getEm()->flush();
                      
                    } 
                  else {
                        echo "votre image est trop grande";
                  }
                }   
             else {
                    echo "une erreur est survenue";
             }
           } 
             else {
                    echo "ce type de document n'est pas telechargeable";
             }
           } 
             else {
                    echo "le fichier n'est pas upload par requete http post, attaque possible par telechargement de fichier";
             }
          }
         }   
        echo $this->twig->render('home.html');
 }
    
public function edit($request)
{
   $annonce = $request->getEm()->getRepository('Entity\Annonce')->find($_GET['annonceID']);
   $images = $request->getEm()->getRepository('Entity\Image')->findAll();
   $user = $request->getUser();
   
   echo $this->twig->render('editAnnonce.html',
                      [
                        'annonce' => $annonce,
                        'images' => $images,
                        'user' => $user,
                      ]);  
}
  
 public function editSecond($request)
 {
    $annonce = $request->getEm()->getRepository('Entity\Annonce')->find($_GET['annonceID']);
    $images = $request->getEm()->getRepository('Entity\Image')->findAll();

    
    $dtDisponible = new \DateTime($_POST['date']);
  
    $annonce->setTitre($_POST['titre_annonce']);
    $annonce->setDescription($_POST['description_annonce']);
    $annonce->setSurface($POST['surface_annonce']);
    $annonce->setDateDisponible($dtDisponible);
    
    $request->getEm()->persist($annonce);
    $request->getEm()->flush();
}
 
 public function delete($request)
 {
   
 }
  
public function contactAnnonce($request)
{
   $annonce = $request->getEm()->getRepository('Entity\Annonce')->find($_GET['annonceID']);
   $user = $request->getUser();
    
   echo $this->twig->render('contactAnnonce.html',
                      [
                        'annonce' => $annonce,
                        'user' => $user,
                      ]);
}
   
 public function contact($request)
 {
   $user = $request->getUser();
   
   echo $this->twig->render('contact.html',
                            [
                              'user' => $user,
                            ]);    
 }
  
public function contactedAnnonce($request)
{
   $user = $request->getUser();  
   $annonce = $request->getEm()->getRepository('Entity\Annonce')->find($_GET['id']);
   $from = 'felixprojetdev@gmail.com';
   $to = 'felixprojetdev@gmail.com';
   $subject = "Titre de l'annonce : ".''.$_POST['titre_annonce'].', '."id de l'annonce : ".''.$annonce->getId();
   $body = "Mail du client : ".''.$_POST['email_contact'].''. "- Nom et Prenom du client : ".''.$_POST['prenom_contact'].' '.$_POST['nom_contact'].' '."Sujet du mail : ".''.$_POST['sujet_contact'].''. "Corps du mail : ".''.$_POST['message_contact'];
   $e = null;

   sendMail($from, $to, $subject, $body, $e);
  
   echo $this->twig->render('listAnnonce.html', [
     'user' => $user,
   ]);
}
  
public function contacted($request)
{
   $user = $request->getUser();  
   $from = 'felixprojetdev@gmail.com';
   $to = 'felixprojetdev@gmail.com';
   $subject = "Sujet : ".''.$_POST["sujet_contact"];
   $body = "Mail du client : ".''.$_POST['email_contact'].''. "- Nom et Prenom du client : ".''.$_POST['prenom_contact'].' '.$_POST['nom_contact'].' '."Sujet du mail : ".''.$_POST['sujet_contact'].''. "Corps du mail : ".''.$_POST['message_contact'];
   $e = null;

   sendMail($from, $to, $subject, $body, $e);
  
   echo $this->twig->render('index.html', [
     'user' => $user,
   ]);
}  
  
public function like($request)
{
   $user = $request->getEm()->getRepository('Entity\User')->find($_SESSION['id']);
   $annonce =  $request->getEm()->getRepository('Entity\Annonce')->find($_GET['annonceID']);
   $usersLikeAnnonce = $annonce->getUsers();
   
   if($user){
     if ($annonce->getUsers()->contains($user)){ // si parmit les users qui ont like l'annonce se trouve le user actuellement connecté et qui techniquement a clické sur le coeur.
       $user->removeAnnonce($annonce);
       $code = 200; // UNLIKE L'ANNONCE
       $message = "l'annonce a était unlike !";
       $request->getEm()->flush();
     }
     elseif (!$annonce->getUsers()->contains($user)){
       $user->addAnnonce($annonce);
       $code = 200; // LIKE L'ANNONCE
       $message = "l'annonce a était like !";
       $request->getEm()->flush();
     }
   }
   else {
     $code = 500;
     $message = "utilisateur non connecté";
   }
   http_response_code($code);
   $response = array(
         'message' => $message,
     );
  echo json_encode($response);   
}

public function recherche($request)
{
  $user = $request->getUser();
  $content_raw = file_get_contents("php://input"); 
  $data = json_decode($content_raw, true);
  $newData = array();
  
  foreach($data as $key => $value){
    if ($value === false){
      $value = "";
    }
    elseif ($value == "true"){
      $value = "1";
    }
    elseif ($value == "") {
      $value = null;
    }
  $newData[$key] = $value;
  };
  
  $annoncesParPage = 12;
  
  if(isset($_GET['page']) and $_GET['page'] != 0) // on recupere par $_GET['page'] car dans l'appel ajax on recupere la valeur dans local.storage pour la placé dans l'url
  {
     $pageActuelle = intval($_GET['page']);
  }
  else // dans le cas ou le localStorage('page') n'est pas définit ou égal a 0
  {
     $pageActuelle = 1;  
  }

  $limit = $annoncesParPage;
  $offset = ($pageActuelle-1) * $annoncesParPage; 
  $em = $request->GetEm(); // CETTE REQUETE PERMET D'OBTENIR UNIQUEMENT ET DYNAMIQUEMENT LE NOMBRE DE PAGE POUR LA NAVBAR DEDIE
  $qb = $em->createQueryBuilder();  
  $qb->select('a')
     ->from('Entity\Annonce', 'a')
     ->orderBy('a.id', 'DESC');
    if ($newData['contrat'] != "null") {
     $qb->andWhere('a.type_contrat = ?1') 
        ->setParameter(1, $newData['contrat']);
    }
    if ($newData['propriete'] != "null") {
     $qb->andWhere('a.type_propriete = ?2')
        ->setParameter(2, $newData['propriete']);
    }
    if ($newData['typeAppartement'] != "null") {
     $qb->andWhere('a.type_appartement = ?3')
        ->setParameter(3, $newData['typeAppartement']);
    }
    if ($newData['surface1'] != null) {
     $qb->andWhere('a.surface >= ?4') 
        ->setParameter(4, $newData['surface1']);
    }  
    if ($newData['surface2'] != null) {
     $qb->andWhere('a.surface <= ?5')
        ->setParameter(5, $newData['surface2']);
    }  
    if ($newData['surfaceTotal1'] != null) {
     $qb->andWhere('a.surface_total >= ?6') 
        ->setParameter(6, $newData['surfaceTotal1']);
    }  
    if ($newData['surfaceTotal2'] != null) {
     $qb->andWhere('a.surface_total <= ?7')
        ->setParameter(7, $newData['surfaceTotal2']);
    }
    if ($newData['prix1'] != null) {
     $qb->andWhere('a.prix >= ?8') 
        ->setParameter(8, $newData['prix1']);
    }  
    if ($newData['prix2'] != null) {
     $qb->andWhere('a.prix <= ?9')
        ->setParameter(9, $newData['prix2']);
    }
    if ($newData['piscineAnnonce'] != "") {
     $qb->andWhere('a.piscine = ?10')
        ->setParameter(10, $newData['piscineAnnonce']);
    }
    if ($newData['jacuzziAnnonce'] != "") {
     $qb->andWhere('a.jacuzzi = ?11')
        ->setParameter(11, $newData['jacuzziAnnonce']);
    }
    if ($newData['ascenseurAnnonce'] != "") {
     $qb->andWhere('a.ascenseur = ?12')
        ->setParameter(12, $newData['ascenseurAnnonce']);
    }
    if ($newData['salleDeSportAnnonce'] != "") {
     $qb->andWhere('a.salle_sport = ?13')
        ->setParameter(13, $newData['salleDeSportAnnonce']);
    }
    if ($newData['placeDeParkingAnnonce'] != "") {
     $qb->andWhere('a.place_parking = ?14')
        ->setParameter(14, $newData['placeDeParkingAnnonce']);
    }
  
  $query = $qb->getQuery();
  $annonces = $query->getResult(); // toutes les annonces avec ces filtres de recherche
  
  $paginator = new Paginator($query, $fetchJoinCollection = true);
  
  $annoncesNombre = count($annonces);
  $pagesTotal = ceil(($annoncesNombre / $annoncesParPage)); 

  ///////////// UNE FOIS QU'ON A LE NOMBRE MAX DE PAGES POUR LA NAVBAR ON REQUETE AVEC FILTRE ET NBR D'ANNONCES MAX PAR PAGE POUR N'AFFICHER QUE 12 ANNONCES A LA FOIS

  $em = $request->GetEm(); // CETTE REQUETE RENVOIE LES 12 ANNONCES A AFFICHE EN FONCTION DE LA PAGE CHOISIE
  $qb = $em->createQueryBuilder();  
  $qb->select('a')
     ->from('Entity\Annonce', 'a')
     ->orderBy('a.id', 'DESC')
     ->setFirstResult($offset)
     ->setMaxResults($limit);
    if ($newData['contrat'] != "null") {
     $qb->andWhere('a.type_contrat = ?1') 
        ->setParameter(1, $newData['contrat']);
    }
    if ($newData['propriete'] != "null") {
     $qb->andWhere('a.type_propriete = ?2')
        ->setParameter(2, $newData['propriete']);
    }
    if ($newData['typeAppartement'] != "null") {
     $qb->andWhere('a.type_appartement = ?3')
        ->setParameter(3, $newData['typeAppartement']);
    }
    if ($newData['surface1'] != null) {
     $qb->andWhere('a.surface >= ?4') 
        ->setParameter(4, $newData['surface1']);
    }  
    if ($newData['surface2'] != null) {
     $qb->andWhere('a.surface <= ?5')
        ->setParameter(5, $newData['surface2']);
    }  
    if ($newData['surfaceTotal1'] != null) {
     $qb->andWhere('a.surface_total >= ?6') 
        ->setParameter(6, $newData['surfaceTotal1']);
    }  
    if ($newData['surfaceTotal2'] != null) {
     $qb->andWhere('a.surface_total <= ?7')
        ->setParameter(7, $newData['surfaceTotal2']);
    }
    if ($newData['prix1'] != null) {
     $qb->andWhere('a.prix >= ?8') 
        ->setParameter(8, $newData['prix1']);
    }  
    if ($newData['prix2'] != null) {
     $qb->andWhere('a.prix <= ?9')
        ->setParameter(9, $newData['prix2']);
    }  
    if ($newData['piscineAnnonce'] != "") {
     $qb->andWhere('a.piscine = ?10')
        ->setParameter(10, $newData['piscineAnnonce']);
    }
    if ($newData['jacuzziAnnonce'] != "") {
     $qb->andWhere('a.jacuzzi = ?11')
        ->setParameter(11, $newData['jacuzziAnnonce']);
    }
    if ($newData['ascenseurAnnonce'] != "") {
     $qb->andWhere('a.ascenseur = ?12')
        ->setParameter(12, $newData['ascenseurAnnonce']);
    }
    if ($newData['salleDeSportAnnonce'] != "") {
     $qb->andWhere('a.salle_sport = ?13')
        ->setParameter(13, $newData['salleDeSportAnnonce']);
    }
    if ($newData['placeDeParkingAnnonce'] != "") {
     $qb->andWhere('a.place_parking = ?14')
        ->setParameter(14, $newData['placeDeParkingAnnonce']);
    }
//   var_dump($offset);
//   var_dump($limit);
  $query = $qb->getQuery();
// <<<<<<< HEAD
  $annonces = $query->getResult(); ///////////// CETTE VARIABLE QUI DOIT ETRE TESTER EN TANT QUE NOMBRE MAX D'ANNONCES, PAS LE FINDALL !!!
//   var_dump(count($annonces));
// =======
//   $annonces = $query->getResult();
  
  $paginator = new Paginator($query, $fetchJoinCollection = true);

// >>>>>>> dev
  if ($annonces) {
    http_response_code(200);
    echo $this->twig->render('refreshAnnonce.html',
                              [
                                'annonces' => $annonces,
                                'user' => $user,
                                'pages' => $pagesTotal,
                              ]
                            );    
  }
}
  
  public function infosPersoNav($request) {
    $user = $request->getUser();
    echo $this->twig->render('infosPerso.html', [
      'user' => $user
    ]);
  }
  
}  