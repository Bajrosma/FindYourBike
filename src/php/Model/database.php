<?php
/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 21.05.2025
 * Description : page contenant toute la logique derrière concernant les interactions direct avec la Base de données
 */
    class Database
    {  
 
        // Variable de classe
        private $connector;
 
        // Variable pour l'instance
        private static $instance = null;
 
 
        /**
         * Fonction pour la connexion et la construction en se connectant avec PDO avec la base de donnée
         **/
        private function __construct()
        {
            // Essayer
            try
            {
                // Crée une nouvelle connexion PDO
                $this->connector = new PDO(DB, USER, $this->getPassword());
            }
            // Si a échoué crée l'execptionm pdo
            catch (PDOException $e)
            {
                // Afficher l'erreur
                die('Erreur : ' . $e->getMessage());
            }
        }
 
        /**
         * Fonction pour l'instance
         **/
        public static function getInstance()
        {
            // Si instance est "vide"
            if(is_null(self::$instance))  
            {
                // Crée l'instance
                self::$instance = new Database();
            }
            // Retourne obligatoirement l'instance
            return self::$instance;
        }
 
        /**
        * Fonction pour avoir le mot de passe du compte pour la base de donnée depuis le fichier JSON
        **/  
        private function getPassword()
        {
            // Lire le fichier JSON avec file_get_contents
            $readJSONFile = file_get_contents(__DIR__.'/../../json/secret.json');
 
            // Décoder le fichier JSON
            $array = json_decode($readJSONFile, TRUE);
 
            // Retourner en tableau le mot de passe
            return $array["password"];
        }
 
        /**
         * fonction qui permet d'executer des requêtes simples
         */
        private function querySimpleExecute($query)
        {
            // Utilisation de query pour effectuer une requête
            return $this->connector->query($query);
        }
 
        /**
         * fonction qui permet d'executer des requêtes qui doivent être vérifier
         */
        private function queryPrepareExecute($query, $binds)
        {
            try
            {
                // Utilisation de prepare, bindValue et execute
                $req = $this->connector->prepare($query);
 
                foreach($binds as $tableKey=>$recipe)
                {
                    //associe les valeurs dans un tableau associatife
                    $req->bindValue(":$tableKey", $recipe['value'], $recipe['type']);
                }
               
                $req->execute();
                return $req;
            }
            catch (PDOException $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
        }
 
        /**
         * fonction qui transforme les données reçu en tableau
         * @return -- renvoie le tableau demandé
         */
        private function formatData($req)
        {
            // Traitement, transformer le résultat en tableau associatif
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * fonction qui récupère les informations d'une personnes et leur mot de passes hashé
         * @return -- renvoie un tableau avec toutes les utilisateurs et leur mot de passe
         */
        public function Getusers()
        {
            // requête 
            $query = "SELECT useName, usePassword, usePrivilage FROM t_user ";
            // execution de la requête
            $prepareTemp = $this->querySimpleExecute($query);
            // transformation des résultat en tableaux
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau 
            return $prepareTabTemp;
        }
        /**
         * fonction permettant la création d'un compte
         */
        public function CreateAccount($User, $password, $privilage)
        {
            // requête 
            $query = "INSERT INTO t_user (useName, usePassword, usePrivilage) VALUES (:username, :passwords, :privilage)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'username' => ['value' => $User, 'type' => PDO::PARAM_STR],
            'passwords' => ['value' => $password, 'type' => PDO::PARAM_STR],
            'privilage' => ['value' => $privilage, 'type' => PDO::PARAM_INT]
            ];    
            // Exécution sécurisée de la requête préparée
            $this->queryPrepareExecute($query, $binds);
        }
        /**
         * recherche les données pour les statistiques
         * @return -- renvoie un tableau avec toutes les données nécessaire au statistique
         */
        public function GetDataForStatistiqueTrimestre($year, $trimestre)
        {
            // requête 
            $query = "SELECT bikDate, bikResitutionDate, bikPlace, bikFrameNumber, braName, sizSize, colName, comName FROM t_bikes JOIN t_size on FK_size=ID_size JOIN t_brand ON FK_brand=ID_brand JOIN t_color ON FK_color=ID_color JOIN t_communes ON FK_commune=ID_commune WHERE QUARTER(bikDate) = :Trimestre AND YEAR(bikDate) = :Years ";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'Trimestre' => ['value' => $trimestre, 'type' => PDO::PARAM_STR],
                'Years' => ['value' => $year, 'type' => PDO::PARAM_STR]
            ];  
            // Exécution sécurisée de la requête préparée
            $prepareTemp = $this->queryPrepareExecute($query, $binds);
            // en fait un tableau lisible
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }
        /**
         * recherche les données pour les statistiques
         * @return -- renvoie un tableau avec toutes les données nécessaire au statistique
         */
        public function GetDataForStatistiqueYear($year)
        {
            // requête 
            $query = "SELECT bikDate, bikResitutionDate, bikPlace, bikFrameNumber, braName, sizSize, colName, comName FROM t_bikes JOIN t_size on FK_size=ID_size JOIN t_brand ON FK_brand=ID_brand JOIN t_color ON FK_color=ID_color JOIN t_communes ON FK_commune=ID_commune WHERE YEAR(bikDate) = :Years ";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'Years' => ['value' => $year, 'type' => PDO::PARAM_STR]
            ];  
            // Exécution sécurisée de la requête préparée
            $prepareTemp = $this->queryPrepareExecute($query, $binds);
            // en fait un tableau lisible
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche une commune à l'aide de son nom
         * @return -- renvoie l'ID de la commune rechercher
         */
        public function GetCommune($name)
        {
            $query = "SELECT ID_commune FROM t_communes WHERE comName=:nomCommune";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'nomCommune' => ['value' => $name, 'type' => PDO::PARAM_STR]
            ];    
            // Exécution sécurisée de la requête préparée
            $prepareTemp =$this->queryPrepareExecute($query, $binds);
            // en fait un tableau lisible
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }
        /**
         * Fonction qui recherche une personne à l'aide de son prénom
         * @return -- renvoie l'ID de la personne rechercher
         */
        public function GetLastPeople($firstname, $lastname)
        {
            $query = "SELECT ID_personne FROM t_personnes WHERE perFirstName=:FirstName AND perLastName = :LastName";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'FirstName' => ['value' => $firstname, 'type' => PDO::PARAM_STR],
            'LastName' => ['value' => $lastname, 'type' => PDO::PARAM_STR]
            ]; 
            // Exécution sécurisée de la requête préparée
            $prepareTemp =$this->queryPrepareExecute($query, $binds);
            // en fait un tableau lisible
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }
        /**
         * Fonction qui recherche les commune 
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllCommunes()
        {
            // requête pour récuperer les communes
            $query = "SELECT ID_commune, comName, comAdress, comNPA, comCity, comEmail, comTel, comInscription FROM t_communes ";
            // execute la commune
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }
        /**
         * Fonction qui recherche la commune demandé 
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetOneCommune($id)
        {
            // requête pour récuperer les communes
            $query = "SELECT comName, comAdress, comNPA, comCity, comEmail, comTel FROM t_communes WHERE ID_commune = :ID ";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'ID' => ['value' => $id, 'type' => PDO::PARAM_STR]
                ]; 
                // Exécution sécurisée de la requête préparée
                $prepareTemp =$this->queryPrepareExecute($query, $binds);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }
        /**
         * Fonction qui recherche les communes pour l'affichage d'une liste deroulante 
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllCommunesDropDown()
        {
            // requête pour récuperer les communes
            $query = "SELECT 1	ID_commune, comName, comInscription FROM t_communes ";
            // execute la commune
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les personnes
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllUsers()
        { 
            // requête pour récuperer les communes
            $query = "SELECT ID_personne, perFirstName,perLastName,perEmail,perTel,perAdress,perCity,perNPA,perRole, ID_commune, comInscription FROM t_personnes JOIN t_communes ON FK_commune=ID_commune ";
            // execute la commande
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les personnes
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllBikes()
        { 
            // requête pour récuperer les communes
            $query = "SELECT ID_bike, bikDate, bikResitutionDate, bikPlace, bikFrameNumber, braName, sizSize, colName, comName FROM t_bikes JOIN t_size on FK_size=ID_size JOIN t_brand ON FK_brand=ID_brand JOIN t_color ON FK_color=ID_color JOIN t_communes ON FK_commune=ID_commune";
            // execute la commande
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les personnes
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllDataBikes()
        { 
            // requête pour récuperer les communes
            $query = "SELECT bidPathFile, FK_bike FROM t_bikedata";
            // execute la commande
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les vélor rendus
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllBikesRendered()
        { 
            // requête pour récuperer les communes
            $query = "SELECT ID_bike, bikDate, bikResitutionDate, bikPlace, bikFrameNumber, braName, sizSize, colName, comName, perFirstName, perLastName, ID_personne FROM t_bikes JOIN t_size on FK_size=ID_size JOIN t_brand ON FK_brand=ID_brand JOIN t_color ON FK_color=ID_color JOIN t_communes ON FK_commune=ID_commune JOIN t_personnes ON FK_personne=ID_personne";
            // execute la commande
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche le responsable de l'inscription  
         * @return -- renvoie les informations trouvé
         */
        public function GetResponsable()
        { 
            // requête pour récuperer les communes
            $query = "SELECT perFirstName, perLastName ,perRole, FK_Commune FROM t_personnes p INNER JOIN (SELECT MIN(ID_personne) AS min_id FROM t_personnes GROUP BY FK_commune) AS first_per_commune ON p.ID_personne = first_per_commune.min_id";
            // execute la commune
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les couleurs
         * @return -- renvoie les couleurs trouvé
         */
        public function GetAllColors()
        { 
            // requête pour récuperer les communes
            $query = "SELECT ID_color, colName FROM t_color";
            // execute la commune
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les marques
         * @return -- renvoie les marques trouvé
         */
        public function GetAllBrands()
        { 
            // requête pour récuperer les communes
            $query = "SELECT ID_brand, braName FROM t_brand";
            // execute la commune
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui recherche les couleurs
         * @return -- renvoie les couleurs trouvé
         */
        public function GetAllSizes()
        { 
            // requête pour récuperer les communes
            $query = "SELECT ID_size, sizSize FROM t_size";
            // execute la commune
            $prepareTemp = $this->querySimpleExecute($query);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /**
         * Fonction qui permet de sauver une inscription
         */
        public function InscriptionAdd($name,$adress,$city,$npa,$email,$tel,$lastname,$firstname,$fonction)
        {
            // ajout de la commune souhaitons s'inscrire
            $this->AddCommune($name,$adress,$city,$npa,$email,$tel);
            // rechercher l'ID de la nouvelle commune pour inscrire la personnes
            $communeID = $this->GetCommune($name);
            // ajout de la personne responsable
            $this->AddPersonne($adress,$city,$npa,$email,$tel,$lastname,$firstname,$fonction,$communeID[0]["ID_commune"]);
        }

        /**
         * Fonction qui permet de sauver des informations de la commune
         */
        public function AddCommune($name,$adress,$city,$npa,$email,$tel)
        {
            // première requête 
            $query = "INSERT INTO t_communes (comName, comAdress, comNPA, comCity, comEmail, comTel) VALUES 
            (:communeName, :communeAdress, :communeNPA, :CommuneLocalite, :communeEmail, :communeTel)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'communeName' => ['value' => $name, 'type' => PDO::PARAM_STR],
            'communeAdress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'communeNPA' => ['value' => $npa, 'type' => PDO::PARAM_STR],
            'CommuneLocalite' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'communeEmail' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'communeTel' => ['value' => $tel, 'type' => PDO::PARAM_STR]
            ];    
            // Exécution sécurisée de la première requête préparée
            $this->queryPrepareExecute($query, $binds);
        }

        /**
         * Fonction qui permet de sauver des informations de la personne
         */
        public function AddPersonne($adress,$city,$npa,$email,$tel,$lastname,$firstname,$fonction,$communeID)
        {
            // deuxième requête 
            $query = "INSERT INTO t_personnes (perFirstName,perLastName,perEmail,perTel,perAdress,perCity,perNPA,perRole,FK_commune) VALUES 
            (:firstName, :lastName, :email, :tel, :adress, :city, :npa, :roles, :commune)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'firstName' => ['value' => $firstname, 'type' => PDO::PARAM_STR],
            'lastName' => ['value' => $lastname, 'type' => PDO::PARAM_STR],
            'email' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
            'city' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'npa' => ['value' => $npa, 'type' => PDO::PARAM_STR],
            'roles' => ['value' => $fonction, 'type' => PDO::PARAM_STR],
            'commune' => ['value' => $communeID, 'type' => PDO::PARAM_STR]
            ];    
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }
        /**
         * Fonction qui permet d'effectuer les différentes étapes d'ajout d'un vélo
         */
        public function NewBike($date, $place, $frameNumber, $color, $brand, $size, $commune, $FilesName)
        {
            // ajoute le vélo
            $this->AddBike($date, $place, $frameNumber, $color, $brand, $size, $commune);
            // récupère le dernier vélo ajouter
            $idBike = $this->getLastInsertId($frameNumber);
            // Ajouter les images liées au vélo 
            foreach($FilesName as $key => $value)
            {
                $this->SaveDataBike($value, $idBike);
            }
        }
        /**
         * Fonction qui permet de sauver les informations d'un nouveau vélo
         */
        public function AddBike($date, $place, $frameNumber, $color, $brand, $size, $commune)
        {
            // Première requête d'insertion du vélo
            $query = "INSERT INTO t_bikes (bikDate, bikPlace, bikFrameNumber, FK_brand, FK_size, FK_color, FK_commune, FK_personne) 
                      VALUES (:FoundDate, :Place, :FrameNumber, :Brand, :Size, :Color, :Commune, NULL)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'FoundDate' => ['value' => $date, 'type' => PDO::PARAM_STR],
                'Place' => ['value' => $place, 'type' => PDO::PARAM_STR],
                'FrameNumber' => ['value' => $frameNumber, 'type' => PDO::PARAM_STR],
                'Brand' => ['value' => $brand, 'type' => PDO::PARAM_INT],
                'Size' => ['value' => $size, 'type' => PDO::PARAM_INT],
                'Color' => ['value' => $color, 'type' => PDO::PARAM_INT],
                'Commune' => ['value' => $commune, 'type' => PDO::PARAM_INT]
            ];   
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }
        /**
         * Fonction qui permet de sauver les images lier à un nouveau vélo
         */
        public function getLastInsertId($frameNumber)
        {
            $query = "SELECT ID_Bike FROM t_bikes WHERE bikFrameNumber=:FrameNumber";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'FrameNumber' => ['value' => $frameNumber, 'type' => PDO::PARAM_STR]
            ];    
            // Exécution sécurisée de la requête préparée
            $prepareTemp =$this->queryPrepareExecute($query, $binds);
            // en fait un tableau lisible
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp[0]['ID_Bike'];
        }
        /**
         * Fonction qui permet de sauver les images lier à un nouveau vélo
         */
        public function SaveDataBike($pathFileName, $idBike)
        {
            $query = "INSERT INTO t_bikedata ( bidPathFile, FK_bike) VALUES (:FileNames,:IdBike)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'FileNames' => ['value' => $pathFileName, 'type' => PDO::PARAM_STR],
                'IdBike' => ['value' => $idBike, 'type' => PDO::PARAM_STR]
                ];    
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }
        /**
         * Fonction qui valide l'inscription de la commune 
         */
        public function AcceptInscription($id)
        {
            // requête pour récuperer les communes
            $query = "UPDATE t_communes SET comInscription = 1 WHERE t_communes.ID_commune = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'ID' => ['value' => $id, 'type' => PDO::PARAM_INT]
            ];
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }

        /**
         * Fonction qui met à jour une commune
         */
        public function UpdateCommune($name, $adress, $city, $npa, $email, $tel, $id)
        {
            // requête pour récuperer les communes
            $query = "UPDATE t_communes SET comName = :Name, comAdress = :Adress, comNPA = :NPA, comCity = :City, comEmail = :Email, comTel = :Tel WHERE ID_commune = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'Name' => ['value' => $name, 'type' => PDO::PARAM_STR],
                'Adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
                'City' => ['value' => $city, 'type' => PDO::PARAM_STR],
                'NPA' => ['value' => $npa, 'type' => PDO::PARAM_STR],
                'Email' => ['value' => $email, 'type' => PDO::PARAM_STR],
                'Tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
                'ID' => ['value' => $id, 'type' => PDO::PARAM_INT]
            ];
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }

        /**
         * Fonction qui met à jour une commune
         */
        public function UpdatePerson($firstname, $lastname, $adress, $city, $npa, $email, $tel, $role, $id)
        {
            // requête pour récuperer les communes
            $query = "UPDATE t_personnes SET perFirstName = :FirstName, perLastName = :LastName, perEmail = :Email, perTel = :Tel, perAdress = :Adress, perCity = :City, perNPA = :NPA, perRole = :Roles WHERE ID_personne = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'FirstName' => ['value' => $firstname, 'type' => PDO::PARAM_STR],
                'LastName' => ['value' => $lastname, 'type' => PDO::PARAM_STR],
                'Adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
                'City' => ['value' => $city, 'type' => PDO::PARAM_STR],
                'NPA' => ['value' => $npa, 'type' => PDO::PARAM_STR],
                'Email' => ['value' => $email, 'type' => PDO::PARAM_STR],
                'Tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
                'Roles' => ['value' => $role, 'type' => PDO::PARAM_STR],
                'ID' => ['value' => $id, 'type' => PDO::PARAM_INT]

            ];
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }

        /**
         * Fonction qui valide l'inscription de la commune 
         */
        public function RestitutionUpdate($firstname, $lastname, $adress, $city, $npa, $email, 
                                          $tel, $dateofrestitution, $id, $FilesName)
        {
            // crée le nouvelle personne responsable du véloo
            $this->creatNewPeople($firstname, $lastname, $adress, $city, $npa, $email, $tel);
            // met à jour le vélo pour savoir qui est le propriètaire et ou il hanite
            $personne = $this->GetLastPeople($firstname, $lastname);
            // met à jour le vélo pour savoir qui est le propriètaire et ou il hanite
            $this->updateBikeDate($dateofrestitution, $personne, $id);
            // sauvegarde les documents de preuves
            foreach($FilesName as $key => $value)
            {
                $this->SaveProof($value, $id);
            }
        }
        /**
         * Fonction qui permet de sauver les images lier à un nouveau vélo
         */
        public function SaveProof($pathFileName, $idBike)
        {
            $query = "INSERT INTO t_dataproof ( proPathFile, FK_bike) VALUES (:FileNames,:IdBike)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'FileNames' => ['value' => $pathFileName, 'type' => PDO::PARAM_STR],
                'IdBike' => ['value' => $idBike, 'type' => PDO::PARAM_STR]
                ];    
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }
        /**
         * Fonction qui permet de sauver une inscription
         */
        public function creatNewPeople($firstname, $lastname, $adress, $city, $npa, $email, $tel)
        {
            // requête 
            $query = "INSERT INTO t_personnes(perFirstName, perLastName, perEmail, perTel, perAdress, perCity, perNPA, perRole) 
            VALUES (:firstname,:lastname,:email,:tel,:adress,:city,:npa,'Citoyen.ne')";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'firstname' => ['value' => $firstname, 'type' => PDO::PARAM_STR],
            'lastname' => ['value' => $lastname, 'type' => PDO::PARAM_STR],
            'email' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
            'adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'city' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'npa' => ['value' => $npa, 'type' => PDO::PARAM_STR]
            ];    
            // Exécution sécurisée de la première requête préparée
            $this->queryPrepareExecute($query, $binds);
        }

                /**
         * Fonction qui valide l'inscription de la commune 
         */
        public function updateBikeDate($dateofrestitution, $personne, $id)
        {
            // requête pour récuperer les communes
            $query = "UPDATE t_bikes SET bikResitutionDate = :dateofrestitution, FK_personne = :personne WHERE t_bikes.ID_bike = :id";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'id' => ['value' => $id, 'type' => PDO::PARAM_INT],
                'personne' => ['value' => $personne, 'type' => PDO::PARAM_INT],
                'dateofrestitution' => ['value' => $dateofrestitution, 'type' => PDO::PARAM_STR]
            ];
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }

        /**
         * Fonction qui supprime l'inscription de la commune 
         */
        public function RefuseInscription($id)
        {
            // requête pour récuperer les communes
            $query = "DELETE FROM t_communes WHERE t_communes.ID_commune = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'ID' => ['value' => $id, 'type' => PDO::PARAM_INT]
            ];
            // Exécution sécurisée de la requête
            $this->queryPrepareExecute($query, $binds);
        }

        /**
         * récupere les information d'un vélo pour le rendu
         */
        public function GetOneBike($id)
        {
            // requête pour récuperer les communes
            $query = "SELECT bikDate, bikPlace, bikFrameNumber, braName, sizSize, colName, comName FROM t_bikes JOIN t_size on FK_size=ID_size JOIN t_brand ON FK_brand=ID_brand JOIN t_color ON FK_color=ID_color JOIN t_communes ON FK_commune=ID_commune WHERE ID_bike = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'ID' => ['value' => $id, 'type' => PDO::PARAM_INT]
            ];
            // Exécution sécurisée de la requête
            $prepareTemp = $this->queryPrepareExecute($query, $binds);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }
        /**
         * récupere les information d'une personne
         */
        public function GetOnePerson($id)
        {
            // requête pour récuperer les communes
            $query = "SELECT perFirstName, perLastName, perEmail, perTel, perAdress, perCity, perNPA, perRole FROM t_personnes WHERE ID_personne = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
                'ID' => ['value' => $id, 'type' => PDO::PARAM_INT]
            ];
            // Exécution sécurisée de la requête
            $prepareTemp = $this->queryPrepareExecute($query, $binds);
            // transforme les données en tableau
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau
            return $prepareTabTemp;
        }

        /* 
        * fonction qui permet la suppresion d'un bâtiment
        */
        public function DeleteOnePerson($id)
        {
            // requête sql incomplete en attente du binds
            $query = "DELETE FROM t_personnes WHERE t_personnes.ID_personne = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
            // effectue la requête d'ajout en passant par une vérification  
            $this->queryPrepareExecute($query, $binds);
        }
        /* 
        * fonction qui permet la suppresion d'un bâtiment
        */
        public function DeleteOneCommune($id)
        {
            // requête sql incomplete en attente du binds
            $query = "DELETE FROM t_communes WHERE t_communes.ID_building = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
            // effectue la requête d'ajout en passant par une vérification  
            $this->queryPrepareExecute($query, $binds);
        }
        /* 
        * fonction qui permet la suppresion d'un bâtiment
        */
        public function DeleteOneBike($id)
        {
            // requête sql incomplete en attente du binds
            $query = "DELETE FROM t_bikes WHERE t_bikes.ID_bike = :ID";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
            // effectue la requête d'ajout en passant par une vérification  
            $this->queryPrepareExecute($query, $binds);
        }


}
?>