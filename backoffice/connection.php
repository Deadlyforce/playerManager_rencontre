<?php
// cette classe est un "singleton" : elle ne peut pas être instanciée
// elle retourne toujours la même instance de connexion grâce à la méthode ::getInstance()
// pour y accéder de n'importe où il suffit maintenant d'appeler Db::getInstance()
class Db {    
    private static $instance = NULL;    
    private function __construct(){}    
    private function __clone(){}

    // on crée un objet PDO (pour la connexion à la db) et on le stocke dans la variable de classe $instance
    // si elle est NULL puis on le retourne, si cette méthode a déjà été appelée une fois, alors $instance
    // aura déjà une valeur (l'objet de connexion) et dans ce cas on retourne simplement cette valeur
    public static function getInstance(){
        if (!isset(self::$instance)){
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            self::$instance = new PDO('mysql:host=localhost;dbname=playermanager', 'root', '', $pdo_options);
      }
      return self::$instance;
    }
}
