<?php
// CONNEXION A LA BASE
define('BASE_URL', $_SERVER['DOCUMENT_ROOT'].'playerManager');
require_once('backoffice/connection.php');
session_start();

// SI CONTROLLER ET ACTION EXISTENT, STOCKAGE DANS VARIABLES. SINON PAGE PAR DEFAUT
//if(isset($_GET['controller']) && isset($_GET['action'])){
if(filter_has_var(INPUT_GET,'controller') && filter_has_var(INPUT_GET,'action')){
//    $controller = $_GET['controller'];
//    $action = $_GET['action'];
    $controller = filter_input(INPUT_GET,'controller');
    $action = filter_input(INPUT_GET,'action');
}else{
    // CHARGEMENT PAGE PAR DEFAUT
    $controller = 'girlsControl';
    $action = 'allGirls';
}

// Si les controleur et action existent, je charge une variable avec le chemin du fichier php correspondant à ce controleur. 

if(!empty($controller) && !empty($action)){
    $file_controller = 'controllers/'. $controller . '.php';	
        
    // Si ce fichier php correspondant au controleur existe, je réalise l'inclusion du fichier (j'appelle le controleur et sa vue correspondante...la page en somme)
    // Si la classe correspondante existe dedans, je l'instancie et crée un objet correspondant
    // Enfin, si dans cette classe une méthode correspondant à l'action existe, je l'appelle
    if(file_exists($file_controller)){
        include($file_controller);

        if(class_exists($controller)){
            $control = new $controller;
            if(method_exists($control, $action)){
                $control->$action();
            }
        }
    }
}




