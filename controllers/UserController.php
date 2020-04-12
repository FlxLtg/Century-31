<?php

namespace Controllers;

use Entity\User;
use Entity\Request;

require 'bootstrap.php';

class UserController extends Controller

{
    public function inscription($request) // renvoie le formulaire pour s'inscrire
    {
        echo $this->twig->render('inscription.html');
    }
  
    public function checkEmail($email) // verifie si l'email est de la bonne forme
    {
       $result = 0;
       if (preg_match ( " /^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/ " , $email))
       {
         $result = 1;
       }
       return $result;
    }
   
    public function checkMdp($decoded_data) // verifie si les deux mots de passe sont identiques
    {
       $result = 0;
       if ($decoded_data['motDePasse'] == $decoded_data['motDePasseVerif'])
       {
         $result = 1;
       }
       return $result;
    }
  
    public function inscriptionValid($request) 
    { // creer un nouvel utilisateur si toutes les conditions sont remplis
      
      $content_raw = file_get_contents("php://input"); // ON RECUPERE LES DONNEES DES INPUTS 
      $decoded_data = json_decode($content_raw, true);
      $email = $decoded_data['email'];
      $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        403 => "Invention",
        404 => "Not found",
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
      $code=403;
      $message="ERREUR";
    
    
       if ((!$decoded_data['identifiant']) OR (!$decoded_data['motDePasse']) OR (!$decoded_data['motDePasseVerif']) OR (!$decoded_data['email'])) // le "!" vient verifier si les données sont fausses (si pas de données alors vaut false)
        { 
          $message="Merci de remplir tous les champs";
          $code=500;
        }  
       
       elseif ($request->getEm()->getRepository('Entity\User')->findOneBy(['email'=>$decoded_data['email']])) // si l'email est déja utilisé (donc qu'il le trouve dans la bdd) ->erreur
       { 
         $message="un compte existe deja pour cette adresse mail";
         $code=500;
       }
      
       elseif ($request->getEm()->getRepository('Entity\User')->findOneBy(['identifiant'=>$decoded_data['identifiant']])) // si l'identifiant existe deja -> erreur
       { 
         $message="cet identifiant est déja utilisé";
         $code=500;
       }
         
      elseif ($this->checkMdp($decoded_data) == 1 && $this->checkEmail($decoded_data["email"]) == 1) // creer l'utilisateur si les conditions sont bien remplis
      {
        
        $dt = new \DateTime(date("Y/m/d"));
        
        $user = new User;
        $user->setIdentifiant($decoded_data['identifiant']);
        $user->setMdp($decoded_data['motDePasse']);
        $user->setEmail($decoded_data['email']);
        $user->setDateInscription($dt);
        $user->setRole('utilisateur');
        
        $request->getEm()->persist($user);
        $request->getEm()->flush();
        
        $message="Inscription reussie, vous aller être redirigé vers la page de connexion";
        $code=200;
        
//         $from = 
//         $to = 
//         $subject = 
//         $body = 
//         sendMail($from, $to, $subject, $body, $e);
          
      }
          
      elseif ($this->checkMdp($decoded_data) == 0) // erreur de mot de passe
      {
           $message="Les mots de passe ne sont pas identiques";
           $code=403;
      }
      
      elseif ($this->checkEmail($email) == 0) // erreur d'email
      {
           $message="l'adresse mail n'est pas au bon format - Exemple de format valide : test@test.com";
           $code=403;
      }
    
      else {
        http_response_code($code);
        echo (array(
        'status' => $status[$code],
        'message' => $message,
        ));
      };
      

      http_response_code($code);
      $response = array(
      'status' => $status[$code],
      'message' => $message,
      );
      echo json_encode($response);
  
    
    }
     
    public function connexion($request) // renvoie le formulaire de connexion
    { 
       echo $this->twig->render('connexion.html');
    } 
  
    public function connexionValid($request) 
    {
      
      $content_raw = file_get_contents("php://input"); 
      $decoded_data = json_decode($content_raw, true);
      $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        403 => "Invention",
        404 => "Not found",
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
      $code=403;
      $message="Il semblerait que vous ne soyez pas encore inscrit..";
      $user = $request->getEm()->getRepository('Entity\User')->findOneBy(['identifiant'=>$decoded_data['identifiant']]);

      // ON TEST SI LES CHAMPS NE SONT PAS VIDE //
      
      if ((!$decoded_data['identifiant']) OR (!$decoded_data['motDePasse'])) // le "!" vient verifier si les données sont fausses (si pas de données alors vaut false)
        { 
          $message="Merci de remplir tous les champs";
          $code=500;
        }
        
      // A PARTIR D'ICI ON TEST SI L'IDENTIFIANT EST DANS LA BDD //  
      
      if ($user) {
        
        // TEST SI LE MDP CORRESPOND A L'IDENTIFIANT //
        if ($user->getMdp() == $decoded_data['motDePasse']) {
            $_SESSION['id'] = $user->getId();
            $message="Tout est bon";
            $code=200;
        }
        
        // SI LE MOT DE PASSE NE CORRESPOND PAS A L'IDENTIFIANT //
        else {
             $message="Identifant ou mot de passe incorrect";
             $code=403;
        }
        
        // ON RETOURNE LA REPONSE //
        http_response_code($code);
        echo json_encode(array( // return the encoded json
            'status' => $status[$code], // success or not?
            'message' => $message
        ));
      } 
      
        // SI L'IDENTIFIANT N'EST PAS DANS LA BDD //
      else {
        http_response_code($code);
        echo json_encode(array(
            'status' => $status[$code], // success or not?
            'message' => $message,
        ));
      } 
      
     }
  
    public function disconnect($request)
    {
      $user = $request->getEm()->getRepository('Entity\User')->find($_SESSION['id']);
      $request->getEm()->persist($user);
      $request->getEm()->flush();
      $_SESSION = array();
      session_destroy();
      header('location: http://195.154.118.169/felix/sitevitrine/?c=annonce&t=index');
    }
  
    public function profil($request)
    {
      $user = $request->getEm()->getRepository('Entity\User')->find($_SESSION['id']);
      $annonces = $user->getAnnonces();

      echo $this->twig->render('profil.html',
                                [
                                  "user" => $user,
                                  "annonces" =>$annonces,
                                ]);
      
      if ($user = null) {
        header('location: /c=annonce&t=home');
      }
    }
  
    public function resetIdentifiant($request)
    {
     $content_raw = file_get_contents("php://input"); 
     $identifiantJSON = json_decode($content_raw, true);
     $user = $request->getUser();
     $identifiant = $user->getIdentifiant();
  //     var_dump($identifiantJSON);

      if ($identifiant === $identifiantJSON) {
        $message="votre identifiant est déja celui-ci";
        $code=500;
      }

      else {
        $user->setIdentifiant($identifiantJSON);
        $request->getEm()->persist($user);
        $request->getEm()->flush();
        $message="Changement d'identifiant effectué";
        $code=200;
      }

      http_response_code($code);
        echo json_encode(array(
            'message' => $message,
        )); 
    }

    public function resetEmail($request)
    {
   $content_raw = file_get_contents("php://input"); 
   $emailJSON = json_decode($content_raw, true);
   $user = $request->getUser();
   $email = $user->getEmail();
    
   if ($email === $emailJSON) { 
         $message="cette adresse est déja celle associé à ce compte";
         $code=500;
       }
    
   elseif ($this->checkEmail($emailJSON) == 1 and $emailJSON != $email) {
     $user->setEmail($emailJSON);
     $request->getEm()->persist($user);
     $request->getEm()->flush();
     $message ="l'adresse mail a bien était modifiée";
     $code=200;
   }
   
   else {
     $message="erreur";
     $code=200;
   }
   
   http_response_code($code);
      echo json_encode(array(
          'message' => $message,
      ));    
  }
    
    public function resetPassword($request)
    {
     $content_raw = file_get_contents("php://input"); 
     $decoded_data = json_decode($content_raw, true);
     $user = $request->getUser();
     $lastPassword = $user->getMdp();
     $message="erreur";
     $code=500;
      if ($decoded_data['password'] != $decoded_data['passwordNew1'] and $decoded_data['passwordNew1'] === $decoded_data['passwordNew2']) {
        $user->setMdp($decoded_data['passwordNew1']);
        $request->getEm()->persist($user);
        $request->getEm()->flush();
        $message="le mot de passe a bien été modifié";
        $code=200;
      }
      elseif ($decoded_data['passwordNew1'] != $decoded_data['passwordNew2']) {
        $message= "les deux mots de passes ne sont pas identiques";
        $code=500;
      }
      elseif ($decoded_data['password'] != $lastPassword) {
        $message= "votre mot de passe actuel est incorrect";
        $code=500;
      }
      elseif ($decoded_data['password'] === $decoded_data['passwordNew1'] and $decoded_data['passwordNew1'] === $decoded_data['passwordNew2']) {
        $message="merci de saisir un nouveau mot de passe différent de celui actuel";
        $code=500;
      }
      http_response_code($code);
      echo json_encode(array(
          'message' => $message,
      ));
    }
  
  
}  
 