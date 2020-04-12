<?php


// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Entity\Request;
use Entity\User;
use Entity\Image;
use Entity\Annonce;
use Controllers\IndexController;
use Controllers\UserController;
use Controllers\AnnonceController;
use Controllers\AdminController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/entity"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters

session_start();

$conn = array(
          'dbname' => 'felix_site_vitrine_bdd',
          'user' => 'felix',
          'password' => '2018',
          'driver' => 'pdo_mysql',
         // 'host' => 'localhost',
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
$class = "Controllers\\" . (isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'IndexController');
$target = isset($_GET['t']) ? $_GET['t'] : "index";
$getParams = isset($_GET) ? $_GET : null;
$postParams = isset($_POST) ? $_POST : null;
$request = new Request();


if (isset($_SESSION['id'])) {

  $user = $entityManager->getRepository(User::class)->find($_SESSION['id']);
  $request->setUser($user);
//   $params['user']=$user;
}

$request->setEm($entityManager);
$request->setPost($postParams);
$request->setGet($getParams);
$request->setPath("http://195.154.118.169/felix/sitevitrine/");

$params = [
    "request" => $request,
]; 
 
if (isset($_SESSION['id'])){ // si le user est connecté
  
  if (class_exists($class, true)) {
    $class = new $class();
    if (in_array($target, get_class_methods($class))) { // si $target est une méthode existante dans $class
        call_user_func_array([$class, $target], $params);
    }
    
  else {
        call_user_func([$class, "index"], $params);  // si $target n'existe pas dans $class alors on renvoie la methode index de $class
    }
  } 
}

// si le user est déconnecté

elseif ($class == "Controllers\AdminController" && in_array($target, get_class_methods($class))){ // si c = admin et qu'on a un t = methode existante de c 
    $class = new AnnonceController; // c = annonce
    call_user_func_array([$class, $target], $params); // c = annonce et t = la methode existante
}
elseif ($class == "Controllers\UserController" && $target === "profil"){ // si c = user et t = profil 
    $class = new AnnonceController; // c = annonce
    call_user_func_array([$class, "index"], $params); // c = annonce et t = index
}
else if ($class == "Controllers\UserController" && in_array($target, get_class_methods($class))){ // si c = user et qu'on a un t = methode existante de c
  $class = new UserController; // c = user
  call_user_func_array([$class, $target], $params); // c = user et t = la methode existante
}
else if ($class == "Controllers\AnnonceController" && $target === "new"){ // si c = user et qu'on a un t = methode existante de c
  $class = new AnnonceController; // c = annonce
  call_user_func_array([$class, "index"], $params); // c = annonce et t = la methode existante
}
else if ($class == "Controllers\AnnonceController" && in_array($target, get_class_methods($class))){ // si c = user et qu'on a un t = methode existante de c
  $class = new AnnonceController; // c = annonce
  call_user_func_array([$class, $target], $params); // c = annonce et t = la methode existante
}
else { // dans tout les autres cas ou c != annonce et t n'existe pas alors
  $class = new AnnonceController; // c = annonce 
  call_user_func_array([$class, "index"], $params); // c = index et t = index
}




 
?>