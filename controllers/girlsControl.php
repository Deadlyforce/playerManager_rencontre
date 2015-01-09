<?php
include 'models/girlsModel.php';

class girlsControl
{
    public function loginAdopte(){
        $msg = '';        
        
        $link = 'http://www.adopteunmec.com//auth/login';        
        
        $postfields = array(
            'username'    => 'adresseemailducompteperso',
            'password'    => 'password',
            'submit'      => 'OK',
            'remember'    => 'checked'
        );
        
        $path_cookie = 'connexion_adopte_temporaire.txt';                
        if(!file_exists(realpath($path_cookie))){
            touch($path_cookie);           
        }
        
        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0';
        
        // RECUPERATION DE LA PAGE LOGIN ET AUTHENTIFICATION *******************
        $curl = curl_init();
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($curl, CURLOPT_URL, $link);      
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);      
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_REFERER, 'http://www.adopteunmec.com');
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: www.adopteunmec.com'));

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);

            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIEJAR, realpath($path_cookie));        

            $return = curl_exec($curl);

    //        print_r($return);
    //        var_dump($curl);        

            if(preg_match('/votre mot de passe est erroné,/i', $return)){
                $msg = 'Connexion échouée.<br/>';
                exit();
            }else{
                $msg = 'Vous êtes connecté.<br/>';
            }
            
            header('location:index.php?controller=girlsControl&action=allGirls&status='.$msg);
            
//            $msg .= '<a class="btn btn-default" href="index.php?controller=girlsControl&action=messages" title="messages">Messages</a><br/>';   
//            $msg .= '<a class="btn btn-default" href="index.php?controller=girlsControl&action=allGirls" title="">Voir tous contacts</a><br/>';
       
//        include 'views/website/website.php';
    }
    
    
    public function messages(){
        $msg = '';        
        
        $path_cookie = 'connexion_adopte_temporaire.txt';                
        if(!file_exists(realpath($path_cookie))){
            touch($path_cookie);           
        }
        
        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0'; 
        
        $messages = '';        
        $contacts = '';        
        $matches = array();
        $page = 0;
        $counter = 1;
        $previousCounter = -1;
        
        // RECUPERATION DE LA PAGE MESSAGES ************************************  
        
        while($counter > $previousCounter){   
                        
            $url = 'http://www.adopteunmec.com/messages/index/messages/'.$page;
//            var_dump($url);
            $messages .= $this->call_curl($url, $userAgent, $path_cookie); 
            
            $pattern = '/"mails":\[(.*)\]}};/';     
            preg_match_all($pattern, $messages, $matches);            
            $counter = count($matches[1]);
            
            $previousCounter++;          
            $page++;
        }
        
//        echo '<pre>';
//        print_r($messages);  
//        echo '</pre>'; 
                       
           
        // TROUVE LA SECTION "mails" DANS LE VAR START = DU JAVASCRIPT DANS LA PAGE PUIS STOCKAGE DANS $data
        $pattern = '/"mails":\[(.*)\]}};/';     
        preg_match_all($pattern, $messages, $matches);            
        $mails = $matches[1];        
        
//        echo '<pre>';
//        print_r($mails);
//        echo '</pre>';
        
        $girls = array();
        foreach($mails as $messagePage){
//            echo '<pre>';
//            print_r($messagePage);
//            echo '</pre>';
            // SEPARATION DES DONNEES DANS UN TABLEAU - CHAQUE row ENGLOBE DIFFERENTES INFOS D'UN MEMBRE
            $resultat = explode('},{',$messagePage);
            $girls = array_merge($girls, $resultat);
        }
        
//        echo '<pre>';
//        print_r($girls);  
//        echo '</pre>';
        
        // SEPARATION DES DONNEES DANS UN TABLEAU - CHAQUE row ENGLOBE DIFFERENTES INFOS D'UN MEMBRE

        // FABRICATION DES REGEX UTILISES PLUS BAS POUR ISOLER LES INFOS
        $pattern_id = '/"id":"(.*)"/';
        $pattern_id_from = '/"id_from":"(.*)"/';
        $pattern_pseudo  = '/"pseudo":"(.*)"/';        
        $pattern_date  = '/"date":"(.*)"/';        
        $pattern_city  = '/"city":"(.*)"/';
        $pattern_age  = '/"age":(.*)/';
        $pattern_thumbnail  = '/"path":(.*)/';
        $pattern_photo = '/com\/(.*)/';
        
        $pattern_defaultPhoto = '/jpg?(.*)/';
        
        
        // POUR CHAQUE FILLE, EXTRACTION DE PLUSIEURS INFOS : id, id_from, pseudo, city, age, adresse de la photo - STOCKAGE DANS $info
        foreach($girls as $row => $data){                
            $row_data = explode(',',$data);                     
           
            // Parcours chaque entrée et fait un match au cas où les données changeraient de place chez adopte
            foreach($row_data as $key => $value){
                
                // OBTENTION DE id
                $result = preg_match($pattern_id, $value, $matches);                
                if($result === 1){
                    $contacts[$row]['id'] = $matches[1];
                }
                
                // OBTENTION DE id_from
                $result = preg_match($pattern_id_from, $value, $matches);                
                if($result === 1){
                    $contacts[$row]['id_from'] = $matches[1];
                }
                
                // OBTENTION DU PSEUDO
                $result = preg_match($pattern_pseudo, $value, $matches);                
                if($result === 1){
                    $matches[1] = filter_var($matches[1],FILTER_SANITIZE_STRING);
                    // Inversion de l'encodage JSON
                    $matches[1] = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $matches[1]);
                    $contacts[$row]['pseudo'] = $matches[1];
                }
                
                // OBTENTION DE LA DATE DE DERNIER MESSAGE
                $result = preg_match($pattern_date, $value, $matches);                
                if($result === 1){
                    $matches[1] = filter_var($matches[1],FILTER_SANITIZE_STRING);
                    // Inversion de l'encodage JSON
                    $matches[1] = str_replace('\\','',$matches[1]); 
                    $matches[1] = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $matches[1]);
                                        
                    // A ce stade : date + baratin autour, il faut filtrer en fonction du texte
                    // 1) Récupérer le nombre
                    $int = substr($matches[1],7,2);
                    // Suppression des espaces restants
                    $int = str_replace(' ', '', $int);
                    
                    // Plusieurs cas à traiter
                    $now = new DateTime();
                    if(strpos($matches[1],'minute') !== false || strpos($matches[1],'minutes')) {                        
                        $date = $now->sub(new DateInterval('PT'.$int.'M'));
                        $date = $date->format('Y-m-d H:i:s');
                    }
                    if(strpos($matches[1],'heure') !== false || strpos($matches[1],'heures')) {                       
                        $date = $now->sub(new DateInterval('PT'.$int.'H'));
                        $date = $date->format('Y-m-d H:i:s');
                    }
                    if(strpos($matches[1],'jour') !== false || strpos($matches[1],'jours')) {                        
                        $date = $now->sub(new DateInterval('P'.$int.'D'));
                        $date = $date->format('Y-m-d H:i:s');
                    }
                    if(strpos($matches[1],'mois') !== false) {                        
                        $date = $now->sub(new DateInterval('P'.$int.'M'));
                        $date = $date->format('Y-m-d H:i:s');
                    }
                    if(strpos($matches[1],'ann') !== false) {                        
                        $date = $now->sub(new DateInterval('P'.$int.'Y'));
                        $date = $date->format('Y-m-d H:i:s');
                    }
                    
                    $contacts[$row]['date'] = $date;
                }
                
                $result = preg_match($pattern_city, $value, $matches);                
                if($result === 1){
                    $matches[1] = preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $matches[1]);
                    $contacts[$row]['city'] = $matches[1];
                }
                
                $result = preg_match($pattern_age, $value, $matches);                
                if($result === 1){
                    $contacts[$row]['age'] = $matches[1];
                }
                
                // Thumbnail et si "?" elimination des caractères en trop
                $result = preg_match($pattern_thumbnail, $value, $matches);                
                if($result === 1){
                    $matches[1] = str_replace('\\','',$matches[1]);
                    $resultat = trim($matches[1],'"');
                    
                    $pregmatch = preg_match($pattern_defaultPhoto, $resultat, $matches);
                    if($pregmatch === 1){
                        $contacts[$row]['thumbnail'] = str_replace($matches[1],'',$resultat);
                    }else{
                        $contacts[$row]['thumbnail'] = $resultat;
                    }                    
                }
            }
            
            // Recuperation du nom de la photo (assez complexe avec des "/" et parfois "?"
            $pregmatch = preg_match($pattern_photo, $contacts[$row]['thumbnail'], $matches);
            if($pregmatch === 1){  
                // Une fois le nom trouvé
                // Vérification si photo générique par défaut (membre supprimé ou pas de photo)
                $result = preg_match($pattern_defaultPhoto, $matches[1], $m);
                if($result === 1){
                    $resultat = str_replace($m[1],'',$matches[1]);
                    $contacts[$row]['photo'] = str_replace('/','',$resultat);
                }else{
                    $contacts[$row]['photo'] = str_replace('/','',$matches[1]);
                }
            }
//            var_dump($contacts[$row]);
//            var_dump($contacts[$row]['photo']);
            
            // RECUPERATION DE LA PAGE PROFIL (COMPLET)            
            $profile = $this->getProfile($contacts[$row]['id_from']);
            
//            for($i=0;$i<count($profile);$i++){
//                if(!is_array($profile[$i])){
//                    $profile[$i] = filter_var($profile[$i], FILTER_SANITIZE_STRING);
//                }else{
//                    for($j=0; $j<count($profile[$i]);$j++){
//                        if(!is_array($profile[$i][$j])){
//                            $profile[$i][$j] = filter_var($profile[$i][$j], FILTER_SANITIZE_STRING);
//                        }else{
//                            for($k=0;$k<count($profile[$i][$j]);$k++){
//                                $profile[$i][$j][$k] = filter_var($profile[$i][$j][$k], FILTER_SANITIZE_STRING);
//                            }
//                        }
//                    }
//                }
//            }
            
            echo '<pre>';
            print_r($profile);
            echo '</pre>';
            
            $contacts[$row]['pays'] = filter_var($profile[4], FILTER_SANITIZE_STRING);
            
            // RECUP DES DETAILS
            // Si la variable $details n'est pas définie, profil supprimé            
            if(!isset($profile[0]) || $profile[0] == NULL){
                $contacts[$row]['yeux'] = '';
                $contacts[$row]['profession'] = '';
                $contacts[$row]['cheveux'] = '';
                $contacts[$row]['alcool'] = '';
                $contacts[$row]['mensurations'] = '';
                $contacts[$row]['tabac'] = '';
                $contacts[$row]['style'] = '';
                $contacts[$row]['alim'] = '';
                $contacts[$row]['origines'] = '';
                $contacts[$row]['manger'] = '';
                $contacts[$row]['hobbies'] = '';
                $contacts[$row]['signes'] = '';
            }else{                            
                $contacts[$row]['yeux'] = filter_var($profile[0][0], FILTER_SANITIZE_STRING);                
                $contacts[$row]['cheveux'] = filter_var($profile[0][2], FILTER_SANITIZE_STRING);                
                $contacts[$row]['mensurations'] = filter_var($profile[0][4], FILTER_SANITIZE_STRING);                
                $contacts[$row]['style'] = filter_var($profile[0][6], FILTER_SANITIZE_STRING);                
                $contacts[$row]['origines'] = filter_var($profile[0][8], FILTER_SANITIZE_STRING);
                $contacts[$row]['hobbies'] = filter_var($profile[0][10], FILTER_SANITIZE_STRING);
                $contacts[$row]['profession'] = filter_var($profile[0][1], FILTER_SANITIZE_STRING);
                $contacts[$row]['alcool'] = filter_var($profile[0][3], FILTER_SANITIZE_STRING);
                $contacts[$row]['tabac'] = filter_var($profile[0][5], FILTER_SANITIZE_STRING);
                $contacts[$row]['alim'] = filter_var($profile[0][7], FILTER_SANITIZE_STRING);
                $contacts[$row]['manger'] = filter_var($profile[0][9], FILTER_SANITIZE_STRING);                    
                $contacts[$row]['signes'] = filter_var($profile[0][11], FILTER_SANITIZE_STRING);
            }
            
            // TRAITEMENT DES MENSURATIONS
            $tab = $this->isolateMensurations($contacts[$row]['mensurations']);
            $contacts[$row]['taille'] = $tab[0];
            $contacts[$row]['silhouette'] = $tab[1];
            
            // RECUPERATION DE LA DESCRIPTION
            $contacts[$row]['description'] = filter_var($profile[1], FILTER_SANITIZE_STRING);            
            
            // RECUPERATION DE LA SHOPPING LIST
            $contacts[$row]['shopping_list'] = filter_var($profile[2], FILTER_SANITIZE_STRING);      
            
            // RECUPERATION DES GOUTS 
            if(isset($profile[3][0])){
                for($i=0; $i<count($profile[3][0]);$i++){
                    $profile[3][0][$i] = filter_var($profile[3][0][$i], FILTER_SANITIZE_STRING);
                    $profile[3][0][$i] = str_replace('/',' ',$profile[3][0][$i]);
                    $profile[3][0][$i] = str_replace(',',' ',$profile[3][0][$i]);
                }
                $contacts[$row]['musique'] = implode(',',$profile[3][0]);
            }else{
                $contacts[$row]['musique'] = '';
            }
            
            if(isset($profile[3][1])){
                for($i=0; $i<count($profile[3][1]);$i++){
                    $profile[3][1][$i] = str_replace( ',', '', $profile[3][1][$i]);
                    $profile[3][1][$i] = filter_var($profile[3][1][$i], FILTER_SANITIZE_STRING);
                }
                $contacts[$row]['livres'] = implode(',',$profile[3][1]);
            }else{
                $contacts[$row]['livres'] = '';
            }
            
            if(isset($profile[3][2])){
                for($i=0; $i<count($profile[3][2]);$i++){
                    $profile[3][2][$i] = str_replace( ',', '', $profile[3][2][$i]);
                    $profile[3][2][$i] = filter_var($profile[3][2][$i], FILTER_SANITIZE_STRING);
                }
                $contacts[$row]['cine'] = implode(',',$profile[3][2]);
            }else{
                $contacts[$row]['cine'] = '';
            }
            
            if(isset($profile[3][3])){
                for($i=0; $i<count($profile[3][3]);$i++){
                    $profile[3][3][$i] = str_replace( ',', '', $profile[3][3][$i]);
                    $profile[3][3][$i] = filter_var($profile[3][3][$i], FILTER_SANITIZE_STRING);
                }
                $contacts[$row]['tv'] = implode(',',$profile[3][3]);
            }else{
                $contacts[$row]['tv'] = '';
            }
            
            // RECUP DES AUTRES PHOTOS
            if(isset($profile[5])){
//                var_dump($profile[5]);
                for($i=0; $i<count($profile[5]);$i++){             
                    
                    // CHARGEMENT DES VARIABLES
                    // Chemins
                    $contacts[$row]['other_photos'][] = $profile[5][$i];
                    
                    // FABRICATION DES NOMS DES AUTRES PHOTOS                    
                    $pregmatch = preg_match($pattern_photo, $contacts[$row]['other_photos'][$i], $matches);
                    if($pregmatch === 1){  
                        // Une fois le nom trouvé
                        // Vérification si photo générique par défaut (membre supprimé ou pas de photo)
                        $result = preg_match($pattern_defaultPhoto, $matches[1], $m);
                        if($result === 1){
                            $resultat = str_replace($m[1],'',$matches[1]);
                            $contacts[$row]['other_photos_name'][] = str_replace('/','',$resultat);
                        }else{
                            $contacts[$row]['other_photos_name'][] = str_replace('/','',$matches[1]);
                        }
                    }                                      
                }
            }            
                        
        } 
               
        echo '<pre>';
        print_r($contacts);
        echo '</pre>';
        
        // ENTREE EN BASE DE id, pseudo, ville, age, thumbnail... ***************
        
        $girls = new girlsModel();
        
        // PARCOURS DE TOUS LES CONTACTS RECUEILLIS *****************************
        foreach($contacts as $contact){
            
            // BASICS
            $girls->setId($contact['id']);
            $girls->setIdFrom($contact['id_from']);
            
            $girls->setDateLastMessage($contact['date']);
            $girls->setPseudo($contact['pseudo']);
            $girls->setVille($contact['city']);
            $girls->setPays($contact['pays']);
            $girls->setAge($contact['age']);
            $girls->setThumbnail($contact['thumbnail']);
            $girls->setPhoto($contact['photo']);
            
            // DETAILS            
            $girls->setYeux($contact['yeux']);            
            $girls->setCheveux($contact['cheveux']);            
            $girls->setMensurations($contact['mensurations']);
            $girls->setTaille($contact['taille']);
            $girls->setSilhouette($contact['silhouette']);
            $girls->setStyle($contact['style']);
            $girls->setOrigines($contact['origines']);
            $girls->setHobbies($contact['hobbies']);
            $girls->setProfession($contact['profession']);
            $girls->setAlcool($contact['alcool']);            
            $girls->setTabac($contact['tabac']);          
            $girls->setAlim($contact['alim']);          
            $girls->setManger($contact['manger']);          
            $girls->setSignes($contact['signes']);          
            
            
            // DESCRIPTION
            $girls->setDescription($contact['description']);
            
            // DESCRIPTION
            $girls->setShoppingList($contact['shopping_list']);
            
            // GOUTS
            $girls->setMusique($contact['musique']);
            $girls->setLivres($contact['livres']);
            $girls->setCine($contact['cine']);
            $girls->setTv($contact['tv']);
            
            // AUTRES PHOTOS
            $girls->setOtherPhotos($contact['other_photos']);
            $girls->setOtherPhotosName($contact['other_photos_name']);
           
            
            // VERIF SI FILLE DEJA EN BASE OU NON            
            $testArray = $girls->checkGirlInDatabase();
            
            for($i=0; $i<count($testArray); $i++){
                // Si le contact n'est pas en base, entrée en base sinon teste dates
                if(!$testArray[$i]->getPassed()){
                    // Entrée en base
                    $girls->addGirl();
                    $girls->addDetails();
                    $girls->addGouts();
                    $girls->addOtherPhotos();
                }else{
                    // SI FILLE DEJA EN BASE, VERIF SI CHANGEMENT DE DATE DERNIER MESSAGE
                    $girl = $girls->fetchGirlById();
                    if($girl['date_last_message'] != $contact['date']){
                        $girls->updateDateLastMessage();
                    }
                }
            }                                  
        }
        
        
//        header('location:index.php?controller=girlsControl&action=allGirls');             
        
        // UNE FOIS QUE TOUTES LES OPERATIONS DE CETTE SESSION SONT FAITES
//        unlink($path_cookie);
        
//        $msg .= '<a class="btn btn-default" href="index.php?controller=girlsControl&action=allGirls" title="">Tous contacts</a><br/>';
        
        include 'views/website/website.php';
    }
    
    
    public function isolateMensurations($string){
         
        if($string != '' && !empty($string)){
            
            // Recherche de la virgule
            $pos = strpos($string, ',');

            // SI PAS DE VIRGULE
            if($pos === FALSE){
                // RECHERCHE DE cm
                $result = strpos($string, 'cm');
                // SI cm EST TROUVÉ
                if($result !== FALSE){
                    $taille = intval(filter_var($string, FILTER_SANITIZE_NUMBER_INT));
                    $silhouette = '';
                }else{
                    $taille = 0;
                    $silhouette = $string;
                }
            }else{
                // SI VIRGULE DANS LE TEXTE
                $matches = array();
                $m = array();
                
                $pattern  = '/(.*) cm/';                
                $result = preg_match($pattern, $string, $matches);                
                if($result === 1){
                    $taille = intval($matches[1]);
                }
                
                // Silhouette, RECHERCHE DE kg
                $res = strpos($string, 'kg');
                
                // SI PAS kg
                if($res === FALSE){
                    $pattern_silhouette  = '/, (.*)/';                
                    $r = preg_match($pattern_silhouette, $string, $m);                
                    if($r === 1){
                        $silhouette = $m[1];
                    }
                }else{
                    $pattern_silhouette  = '/kg, (.*)/';                
                    $r = preg_match($pattern_silhouette, $string, $m);                
                    if($r === 1){
                        $silhouette = $m[1];
                    }else{
                        $silhouette = 'non renseigné';
                    }
                }
            }          
        }else{
            $taille = 0;
            $silhouette = '';
        }
        
        $tab[] = $taille; 
        $tab[] = $silhouette;
        
        return $tab;
    }
    
       
    public function call_curl($url, $userAgent, $path_cookie){
        $curl = curl_init();      
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($curl, CURLOPT_URL, $url);      
            curl_setopt($curl, CURLOPT_COOKIESESSION, true);      
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_REFERER, 'http://www.adopteunmec.com');
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
//            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_COOKIEFILE, realpath($path_cookie));

        $messages = curl_exec($curl);
        return $messages;
        
        curl_close($curl);
    }
    
    
    public function getProfile($id){
                
        $path_cookie = 'connexion_adopte_temporaire.txt';                
        if(!file_exists(realpath($path_cookie))){
            touch($path_cookie);           
        }
        
        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0';         
               
        // Retrait de toutes les informations utiles du profil *****************
        
        $url = 'http://www.adopteunmec.com/profile/'.$id;
        
        $profile = $this->call_curl($url, $userAgent, $path_cookie);        
//        print_r($profile);
        
        $dom = new DOMDocument();
        @$dom->loadHTML($profile);
        
        $xpath = new DOMXPath($dom);        
        
        // VERIFICATION QUE LA FICHE EXISTE ENCORE (recupère le texte 'Cet utilisateur n'existe pas')
        $errorPage = $xpath->evaluate('//div[contains(concat(" ",normalize-space(@class)," ")," error_page_message ")]/text()');
        
        if(is_object($errorPage)){
        
            // RECUPERATION DES PHOTOS
            
            // SI AU MOINS LA 2me PHOTO EXISTE
            $uniquePic = $xpath->evaluate('//div[@id="user-pic-list"]/div[1]');
            
            if(is_object($uniquePic->item(0))){
                $pic = array();
                $i=1;
                // condition valide
                $test = $xpath->evaluate('//div[@id="user-pic-list"]');
                
                while(($test->item(0)) != NULL){                    
                    $resultat = $xpath->evaluate('//div[@id="user-pic-list"]/div['.$i.']/img/@src');
                    $pic[] = $resultat->item(0)->nodeValue;
                    $test = $xpath->evaluate('//div[@id="user-pic-list"]/div['.($i+1).']');
                    $i++;
                }
            }else{
                // S'IL N'Y A QU'UNE PHOTO, $pic EST VIDE POUR CE PROFIL
                $pic[] = '';
            }
            
//            echo '<pre>';
//            print_r($pic);
//            echo '</pre>';                         
                
            // RECUPERATION DU PAYS *********************************************             
            
            $country = $xpath->evaluate('//div[@id="profile-infos"]/div[2]/span[3]/text()');
            if(is_object($country->item(0))){                
                $pays = trim($country->item(0)->nodeValue);
            }else{
                $pays = '';
            }
            
            // RECUPERATION DES DETAILS *********************************************             
            $details = array();
            
            $yeux = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[1]/td[1]/div/text()[2]');
            for($i=0;$i<$yeux->length;$i++){                 
                $details[0] = trim($yeux->item($i)->nodeValue);                
            }
            
            $profession = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[1]/td[2]/div/text()[2]');
            for($i=0;$i<$profession->length;$i++){                
                $details[1] = trim($profession->item($i)->nodeValue);                
            }
            
            $cheveux = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[2]/td[1]/div/text()[2]');
            for($i=0;$i<$cheveux->length;$i++){               
                $details[2] = trim($cheveux->item($i)->nodeValue);                
            }
            
            $alcool = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[2]/td[2]/div/text()[2]');
            for($i=0;$i<$alcool->length;$i++){
                $details[3] = trim($alcool->item($i)->nodeValue);                
            }
            
            $mensurations = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[3]/td[1]/div/text()[2]');
            for($i=0;$i<$mensurations->length;$i++){
                $details[4] = trim($mensurations->item($i)->nodeValue);                
            }
            
            $tabac = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[3]/td[2]/div/text()[2]');
            for($i=0;$i<$tabac->length;$i++){
                $details[5] = trim($tabac->item($i)->nodeValue);                
            }
            
            $style = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[4]/td[1]/div/text()[2]');
            for($i=0;$i<$style->length;$i++){
                $details[6] = trim($style->item($i)->nodeValue);                
            }
            
            $alim = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[4]/td[2]/div/text()[2]');
            for($i=0;$i<$alim->length;$i++){
                $details[7] = trim($alim->item($i)->nodeValue);                
            }
            
            $origines = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[5]/td[1]/div/text()[2]');
            for($i=0;$i<$origines->length;$i++){
                $details[8] = trim($origines->item($i)->nodeValue);                
            }
            
            $manger = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[5]/td[2]/div/text()[2]');
            for($i=0;$i<$manger->length;$i++){
                $details[9] = trim($manger->item($i)->nodeValue);                
            }
            
            $hobbies = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[6]/td[1]/div/text()[2]');
            for($i=0;$i<$hobbies->length;$i++){
                $details[10] = trim($hobbies->item($i)->nodeValue);                
            }
            
            $signes = $xpath->evaluate('//div[@id="view_details"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[6]/td[2]/div/text()[2]');
            for($i=0;$i<$signes->length;$i++){
                $details[11] = trim($signes->item($i)->nodeValue);                
            }
                        
            
            // RECUPERATION DE LA DESCRIPTION ***********************************
            
            $resultat = $xpath->evaluate('//div[@id="view_description_girl"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/p');
            if(is_object($resultat->item(0))){
                $description = trim($resultat->item(0)->nodeValue);       
            }else{
                $description = '';
            }
            
            // RECUPERATION DE LA SHOPPING LIST *********************************
            
            $resultat = $xpath->evaluate('//div[@id="view_shoppinglist_girl"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/p');
            if(is_object($resultat->item(0))){
                $shoplist = trim($resultat->item(0)->nodeValue);            
            }else{
                $shoplist = '';
            }
            
            // RECUPERATION DES GOUTS SI EXISTENT *******************************
            $gouts = '';  
                        
            $musique = $xpath->evaluate('//div[@id="view_gouts"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[2]/td[1]/ol/li/*');
            $livres = $xpath->evaluate('//div[@id="view_gouts"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[2]/td[2]/ol/li/*');
            $cine = $xpath->evaluate('//div[@id="view_gouts"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[4]/td[1]/ol/li/*');
            $tv = $xpath->evaluate('//div[@id="view_gouts"]/div[contains(concat(" ",normalize-space(@class)," ")," data ")]/table/tbody/tr[4]/td[2]/ol/li/*');
            
            for($i=0;$i<$musique->length;$i++){
                $gouts[0][] = ($musique->item($i)->nodeValue);                
            }
            
            for($i=0;$i<$livres->length;$i++){
                $gouts[1][] = ($livres->item($i)->nodeValue);                
            }
            
            for($i=0;$i<$cine->length;$i++){
                $gouts[2][] = ($cine->item($i)->nodeValue);                
            }
            
            for($i=0;$i<$tv->length;$i++){
                $gouts[3][] = ($tv->item($i)->nodeValue);                
            }
        }
//            var_dump($gouts);
            
//            echo '<pre>';
//            print_r($resultat);
//            echo '</pre>';
                   
            // ******************************************************************            
            
        
            $profil[] = $details;
            $profil[] = $description;
            $profil[] = $shoplist;
            $profil[] = $gouts;
            $profil[] = $pays;  
            $profil[] = $pic;  
            
            return $profil;
    
    //        echo '<pre>';        
    //        print_r($details);
    //        echo '</pre>';           
            
           
    }
    
    
    public function allGirls(){
        $msg = '';
        
        if(filter_has_var(INPUT_GET,'status')){
            $status = $_GET['status']; 
            $msg .= $status;
        }
        
        $msg.= '<a class="btn btn-default" href="index.php?controller=girlsControl&action=loginAdopte" title="login">Adopte login</a><br/><br/>';
        
        $girls = new girlsModel();
        $list = $girls->fetchGirls();        
       
        foreach($list as $girl){
            $date = new DateTime($girl['date_last_message']);
            $girl['date_last_message'] = $date->format('d-m-Y');
            
            $msg .= '<img src="'.$girl['photo'].'" /><br/>';
            $msg .= 'Dernier message: '.$girl['date_last_message'].'<br/>';
            $msg .= $girl['pseudo'].'<br/>';
            $msg .= 'Age: '.$girl['age'].'<br/>';
            $msg .= $girl['ville'].'<br/>';
        }   
        
                
        include 'views/girls/allGirls.php';
    }
}



