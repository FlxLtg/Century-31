<?php

namespace Controllers;

use Entity\User;
use Entity\Request;

require 'bootstrap.php';


class AdminController extends Controller

{
  
  public function index($request)
  {
//     if ($_SESSION['id'] === null) {
//       header('location: http://195.154.118.169/felix/sitevitrine/?c=annonce&t=home');
//     }
//     else {
    $user = $request->getEm()->getRepository('Entity\User')->find($_SESSION['id']);
    $users = $request->getEm()->getRepository('Entity\User')->findAll();
    
    echo $this->twig->render('adminPannel.html',
        [
          "users" => $users,
          "user" => $user,
        ]
        );
//     }  
  }
  
  public function changeRole($request) 
  {
    $content_raw = file_get_contents("php://input"); // ON RECUPERE LES DONNEES DES INPUTS 
    $decoded_data = json_decode($content_raw, true);
    $newRole = $decoded_data['newRole'];
    $user = $request->getEm()->getRepository('Entity\User')->find($_GET['userID']);
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        403 => "Invention",
        404 => "Not found",
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );
      $code=403;
      $message="erreur";
    
    if ($user != null & $newRole != $user->getRole())
    {
        $user->setRole($newRole);
        $request->getEm()->persist($user);
        $request->getEm()->flush();
        $message = "tout est bon";
        $code = 200; 
    }
    
    elseif ($user != null & $newRole == $user->getRole())
    {
      $message = "Le role de cet utilisateur est deja celui demandé";
      $code = 500;
    }
    

        http_response_code($code);
        echo json_encode(array(
            'status' => $status[$code],
            'message' => $message,
        ));  
  }
  
  public function deleteUser($request)
  {
   $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        403 => "Invention",
        404 => "Not found",
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        ); 
   $message = 'erreur';
   $code = 500;  
   $user = $request->getEm()->getRepository('Entity\User')->find($_GET['userId']);
   
  if ($user)
   {  
   $request->getEm()->remove($user);
   $request->getEm()->flush();
    
   $message = "tout est bon";
   $code = 200;
    
   http_response_code($code);
        echo json_encode(array(
            'status' => $status[$code],
            'message' => $message,
        )); 
   }
    
  else
  {
    http_response_code($code);
        echo json_encode(array(
            'status' => $status[$code],
            'message' => $message,
        ));
  }
}
    
 public function changeHoraires($request)
 {
   
 }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
}