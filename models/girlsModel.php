<?php
include_once 'models/result.php';

class girlsModel
{
//    protected variables;
    protected $id, $id_from, $date_last_message, $pseudo, $profession, $yeux, $cheveux, $mensurations, $taille, $silhouette, $style, $origines, $hobbies, $alcool, $tabac, $alim, $manger, $signes, $description, $shopping_list, $age, $ville, $pays, $thumbnail, $photo, $musique, $livres, $cine, $tv, $otherPhotos, $otherPhotosName;
    
//    getters ******************************************************************

    public function getId(){
        return $this->id;
    }
    
    public function getIdFrom(){
        return $this->id_from;
    }
    
    public function getDateLastMessage(){
        return $this->date_last_message;
    }

    public function getPseudo(){
        return $this->pseudo;
    }

    public function getProfession(){
        return $this->profession;
    }

    public function getCheveux(){
        return $this->cheveux;
    }

    public function getMensurations(){
        return $this->mensurations;
    }

    public function getOrigines(){
        return $this->origines;
    }

    public function getAlcool(){
        return $this->alcool;
    }

    public function getTabac(){
        return $this->tabac;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getShoppingList(){
        return $this->shopping_list;
    }

    public function getAge(){
        return $this->age;
    }

    public function getVille(){
        return $this->ville;
    }
    
    public function getPays(){
        return $this->pays;
    }

    public function getThumbnail(){
        return $this->thumbnail;
    }

    public function getPhoto(){
        return $this->photo;
    }

//    setters ******************************************************************
    
    public function setId($id){
            $this->id = $id;
    }
    
    public function setIdFrom($id){
            $this->id_from = $id;
    }
    
    public function setDateLastMessage($date_last_message){
            $this->date_last_message = $date_last_message;
    }
    
    public function setPseudo($pseudo){
            $this->pseudo = $pseudo;
    }    
    
    public function setYeux($yeux){
            $this->yeux = $yeux;
    }
    
    public function setCheveux($cheveux){
            $this->cheveux = $cheveux;
    }
    
    public function setMensurations($mensurations){
            $this->mensurations = $mensurations;
    }
    
    public function setTaille($taille){
            $this->taille = $taille;
    }
    
    public function setSilhouette($silhouette){
            $this->silhouette = $silhouette;
    }
    
    public function setStyle($style){
            $this->style = $style;
    }
    
    public function setOrigines($origines){
            $this->origines = $origines;
    }
    
    public function setHobbies($hobbies){
            $this->hobbies = $hobbies;
    }
    
    public function setProfession($profession){
            $this->profession = $profession;
    }
    
    public function setAlcool($alcool){
            $this->alcool = $alcool;
    }
    
    public function setTabac($tabac){
            $this->tabac = $tabac;
    }
    
    public function setAlim($alim){
            $this->alim = $alim;
    }
    
    public function setManger($manger){
            $this->manger = $manger;
    }
    
    public function setSignes($signes){
            $this->signes = $signes;
    }
    
    
    public function setShoppingList($shopping_list){
            $this->shopping_list = $shopping_list;
    }
    
    public function setAge($age){
            $this->age = $age;
    }
    
    public function setVille($ville){
            $this->ville = $ville;
    }
    
    public function setPays($pays){
            $this->pays = $pays;
    }
    
    public function setThumbnail($thumbnail){
            $this->thumbnail = $thumbnail;
    }
    
    public function setPhoto($photo){
            $this->photo = $photo;
    }
    
    public function setMusique($musique){
            $this->musique = $musique;
    }
    
    public function setLivres($livres){
            $this->livres = $livres;
    }
    
    public function setCine($cine){
            $this->cine = $cine;
    }
    
    public function setTv($tv){
            $this->tv = $tv;
    }
    
    public function setDescription($description){
            $this->description = $description;
    }
    
    public function setOtherPhotos($otherPhotos){
            $this->otherPhotos = $otherPhotos;
    }
    
    public function setOtherPhotosName($otherPhotosName){
            $this->otherPhotosName = $otherPhotosName;
    }
    
    
//    Méthodes / Accès base   
    
    public function checkGirlInDatabase(){
       $resultat = $this->fetchRowCountGirlById();
       if($resultat == 0 || $resultat == ''){
           $testArray[] = new Result(false, 'Contact inexistant en base.');
       }else{
           $testArray[] = new Result(true);           
       }       
       return $testArray;
    }
    
    public function fetchRowCountGirlById(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM girls WHERE id_from = '$this->id_from'");
        return $req->rowCount();
    }
    
    public function fetchGirlById(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM girls WHERE id_from = '$this->id_from'");
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    public function addGirl(){
        $photo_bdd = "assets/images/thumbnails/" . $this->photo; 
        
        // COPIE DE LA PHOTO DANS LE DOSSIER THUMBNAILS
        $photo_folder = BASE_URL . "/assets/images/thumbnails/".$this->photo;
        copy($this->thumbnail, $photo_folder);
        
        $db = Db::getInstance();
        $db->query("INSERT INTO girls (id, id_from, date_last_message, pseudo, description, shopping_list, ville, pays, age, thumbnail, photo) VALUES ('$this->id', '$this->id_from', '$this->date_last_message','$this->pseudo', '$this->description', '$this->shopping_list', '$this->ville', '$this->pays','$this->age', '$this->thumbnail', '$photo_bdd')");                    
    }
    
    public function addDetails(){ 
        var_dump($this->yeux);
        $db = Db::getInstance();
        $db->query("INSERT INTO girls_details (id_from, yeux, cheveux, mensurations, taille, silhouette, style, origines, hobbies, profession, alcool, tabac, alim, manger, signes) VALUES ('$this->id_from', '$this->yeux','$this->cheveux', '$this->mensurations', '$this->taille', '$this->silhouette', '$this->style', '$this->origines', '$this->hobbies', '$this->profession', '$this->alcool', '$this->tabac', '$this->alim', '$this->manger', '$this->signes')");                  
    }
    
    public function addGouts(){      
        $db = Db::getInstance();
        $db->query("INSERT INTO girls_gouts (id_from, musique, livres, cine, tv) VALUES ('$this->id_from', '$this->musique','$this->livres', '$this->cine', '$this->tv')");                  
    }
    
    public function addOtherPhotos(){
        $photo_bdd = array();
        $tab = $this->otherPhotos;
        $tabName = $this->otherPhotosName;
        // SI IL Y A DES PHOTOS
        if($tab[0] != '' && $tab != NULL){
            
            $db = Db::getInstance();
            
            // Chemin rentré en base pour les photos
            for($i=0; $i<count($tabName) ;$i++){
                $photo_bdd[] = "assets/images/photos/". $this->id_from ."/". $tabName[$i];          
            }
            
            // INSERTION DE LA PREMIERE PHOTO ET DE L'ID
            $db->query("INSERT INTO girls_photos (id_from, photo_0, photoname_0) VALUES ('$this->id_from', '$tab[0]', '$photo_bdd[0]')");
            
            // COPIE DE LA PHOTO DANS LE DOSSIER PHOTOS AVEC SON SOUS DOSSIER BASE SUR id_from
            $photo_folder = BASE_URL . "/assets/images/photos/". $this->id_from ."/". $this->photo;
            copy($tab[0], $photo_folder);
            
            // UPDATE AVEC LES AUTRES PHOTOS
            for($i=1; $i<count($tab); $i++){                               
                $db->query("UPDATE girls_photos SET photo_$i='$tab[$i]',photoname_$i='$photo_bdd[$i]' WHERE id_from='$this->id_from'");                
            } 
        }
    }
    
    public function fetchGirls(){
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM girls ORDER BY date_last_message DESC");
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateDateLastMessage(){
        $db = Db::getInstance();
        $db->query("UPDATE girls SET date_last_message='$this->date_last_message' WHERE id_from='$this->id_from'");
    }
}