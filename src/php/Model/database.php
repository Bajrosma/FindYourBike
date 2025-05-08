<?php
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
         * TODO: à compléter
         */
        private function querySimpleExecute($query)
        {
            // Utilisation de query pour effectuer une requête
            return $this->connector->query($query);
        }
 
        /**
         * TODO: à compléter
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
         * fonction qui récupère tout les Genres deja existant
         * @return -- renvoie un tableau avec tout les genres
         */
        private function formatData($req)
        {
            // Traitement, transformer le résultat en tableau associatif
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }


        /**
         * fonction qui récupère tous les bâtiments deja existant
         * @return -- renvoie un tableau avec tous les bâtiments
         */
        public function GetAllbuildings()
        {
            $query = "SELECT ID_building, buiAdress, buiCity, buiNPA FROM t_building ";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère tous les contacts deja existant
         * @return -- renvoie un tableau avec tous les contacts
         */
        public function GetAllContacts()
        {
            $query = "SELECT ID_Contact,conName,conEmail,conTel,conFonction, entName FROM t_contacts JOIN t_entreprises ON FK_entreprise=ID_entreprise ";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

         /**
         * fonction qui récupère tous les entreprises deja existant
         * @return -- renvoie un tableau avec toutes les entreprises
         */
        public function GetAllEntreprises()
        {
            $query = "SELECT ID_entreprise,entName,entAdress,entCity,entNPA,entTel,entEmail FROM t_entreprises";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère tous les travaux deja existant
         * @return -- renvoie un tableau avec toutes les entreprises
         */
        public function GetAllworks()
        {
            $query = "SELECT ID_Work, worTitle, buiAdress, buiCity, buiNPA, entName, entemail, entTel FROM t_works JOIN t_entreprises ON FK_entreprise=ID_entreprise JOIN t_building ON FK_building=ID_building";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère toutes les données deja existant
         * @return -- renvoie un tableau avSec toutes les entreprises
         */
        public function GetAllData()
        {
            $query = "SELECT ID_data,datFilePath,FK_building FROM t_data";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

                 /**
         * fonction qui récupère tous les entreprises deja existant
         * @return -- renvoie un tableau avec toutes les entreprises
         */
        public function GetListesEntreprises()
        {
            $query = "SELECT ID_entreprise, entName FROM t_entreprises";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

                /**
         * fonction qui récupère tous les entreprises deja existant
         * @return -- renvoie un tableau avec toutes les entreprises
         */
        public function GetListeBuildings()
        {
            $query = "SELECT ID_Building,buiAdress,buiCity,buiNPA FROM `t_building`";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère tous les bâtiments deja existant
         * @return -- renvoie un tableau avec tout bâtiments
         */
        public function GetOneBuilding($id)
        {
            $query = "SELECT `ID_building`, `buiAdress`, `buiCity`, `buiNPA`, buiECA, buiParcelle FROM `t_building` WHERE ID_building = :id";

            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds['id']=['value'=> $id,'type'=>PDO::PARAM_INT];
   
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);

            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère tous les bâtiments deja existant
         * @return -- renvoie un tableau avec tout bâtiments
         */
        public function GetOneEntreprise($id)
        {
            $query = "SELECT `ID_entreprise`,`entName`,`entAdress`,`entCity`,`entNPA`,`entEmail`,`entTel` FROM `t_entreprises` WHERE ID_entreprise = :id";

            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds['id']=['value'=> $id,'type'=>PDO::PARAM_INT];
   
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);

            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère les détails des travaux sélectionnés
         * @return -- renvoie un tableau avec tout bâtiments
         */
        public function GetOneWork($id)
        {
            // requête sql avec :id pour faire la vérification des injections SQL
            $query = "SELECT ID_Work, worTitle, worDescription, worStartDate, worEndDate, worPrices, ID_building, buiAdress, buiCity, buiNPA, entName, entemail, entTel FROM t_works JOIN t_entreprises ON FK_entreprise=ID_entreprise JOIN t_building ON FK_building=ID_building WHERE ID_Work = :id";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['id' => ['value' => $id, 'type' => PDO::PARAM_INT]];
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);
            //mise en place du tableau 
            $prepareTabTemp = $this->formatData($prepareTemp);
            // retourne le tableau avec les données
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère les détails du travaux selectionné
         * @return -- renvoie un tableau avec tout bâtiments
         */
        public function GetOneContact($id)
        {
            $query = "SELECT `conName`,`conEmail`,`conTel`,`conFonction`,`FK_entreprise` FROM `t_contacts` WHERE ID_Contact = :id";

            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['id' => ['value' => $id, 'type' => PDO::PARAM_INT]];
   
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);

            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /** 
         * fonction qui récupère les détails d'un bâtiment
         * @return -- renvoie un tableau avec tout bâtiments 
         * maybe to add : ID_entreprise, entName, entemail, entTel JOIN t_entreprises ON FK_entreprise=ID_entreprise
         */
        public function GetWorksFormBuilding($id)
        {
            $query = "SELECT ID_Work, worTitle FROM t_works WHERE FK_building = :id";

            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['id' => ['value' => $id, 'type' => PDO::PARAM_INT]];
   
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);

            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        public function GetContactFromEntreprise($id)
        {
            $query = "SELECT `ID_Contact`,`conName`,`conEmail`,`conTel`,`conFonction`,`FK_entreprise` FROM `t_contacts` WHERE `FK_entreprise` = :id";

            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['id' => ['value' => $id, 'type' => PDO::PARAM_INT]];
   
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);

            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * fonction qui récupère les datas d'un immeuble
         * @return -- renvoie un tableau avec tout bâtiments
         */
        public function getDataFromBuilding($id)
        {
            $query = "SELECT datFilePath FROM t_data WHERE FK_building = :id";

            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = ['id' => ['value' => $id, 'type' => PDO::PARAM_INT]];
   
            // effectue la requête d'ajout en passant par une vérification 
            $prepareTemp = $this->queryPrepareExecute($query, $binds);

            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }
        

        /**
         * fonction qui récupère tout les utilisateurs
         * @return -- renvoie un tableau avec tout les utilisateur trouver
         */
        public function getUsers()
        {
            // requête sql 
            $query="SELECT `ID_user`, `useLogin`, `usePassword`, `useAdministrator` FROM `t_user`";

            // effectue la requête et recupere toute les valeurs
            $temp= $this->querySimpleExecute($query);

            // transforme le toute en tableau assoc
            $tabUsersTemp= $this->formatData($temp);

            // retourne un tableau
            return $tabUsersTemp;
        }

            /*
     * fonction permettant de mettre a jour un bâtiment
     */
    public function UpdateWork($title, $description, $price, $start, $end, $building, $entreprise, $id)
    {
        // Requête SQL d'insertion (à adapter selon le nom de ta table)
        $query = "UPDATE t_works SET worTitle = :Title, worDescription = :Descriptions, worPrices = :Prices, worStartDate = :StartDate, worEndDate = :EndDate, FK_building = :Building, FK_entreprise = :Entreprise WHERE t_works.ID_Work = :ID";
        
        // Binds avec les valeurs et leurs types pour la requête préparée
        $binds = [
            'Title' => ['value' => $title, 'type' => PDO::PARAM_STR],
            'Descriptions' => ['value' => $description, 'type' => PDO::PARAM_STR],
            'Prices' => ['value' => $price, 'type' => PDO::PARAM_STR],
            'StartDate' => ['value' => $start, 'type' => PDO::PARAM_STR],
            'EndDate' => ['value' => $end, 'type' => PDO::PARAM_STR],
            'Building' => ['value' => $building, 'type' => PDO::PARAM_INT],
            'Entreprise' => ['value' => $entreprise, 'type' => PDO::PARAM_INT],
            "ID" => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];

         // Exécution sécurisée de la requête préparée
         $this->queryPrepareExecute($query, $binds);
    }    

    /*
     * fonction permettant de mettre a jour un bâtiment
     */
    public function UpdateBuilding($adress, $city, $npa, $eca, $parcelle, $id)
    {
        // requête sql incomplête (manque les valeurs à rentrer)
        $query = "UPDATE t_building SET buiAdress = :Adress, buiCity = :City, buiNPA = :NPA, buiECA = :ECA, buiParcelle = :Parcelle WHERE t_building.ID_Building = :id";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
        $binds = [
            'Adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'City' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'NPA' => ['value' => $npa, 'type' => PDO::PARAM_INT],
            'ECA' => ['value' => $eca, 'type' => PDO::PARAM_INT],
            'Parcelle' => ['value' => $parcelle, 'type' => PDO::PARAM_INT],
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];
        // effectue la requête d'ajout en passant par une vérification 
        $this->queryPrepareExecute($query, $binds);
    }

    /*
     * fonction permettant de mettre a jour une Entreprise
     */
    public function UpdateEntreprise($name, $adress, $city, $npa, $email, $tel, $id)
    {
        // requête sql incomplête (manque les valeurs à rentrer)
        $query = "UPDATE t_entreprises SET entname = :Names, entAdress = :Adress, entCity = :City, entNPA = :NPA, entTel = :Tel, entEmail = :Email WHERE t_entreprises.ID_Entreprise = :id";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
        $binds = [
            'Names' => ['value' => $name, 'type' => PDO::PARAM_STR],
            'Adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'City' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'NPA' => ['value' => $npa, 'type' => PDO::PARAM_INT],
            'Email' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'Tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];
       
        // effectue la requête d'ajout en passant par une vérification 
        $this->queryPrepareExecute($query, $binds);
    }

    /*
     * fonction permettant de mettre a jour un Contact
     */
    public function UpdateContact($name, $email, $tel, $fonction, $entreprise, $id)
    {
        // requête sql incomplête (manque les valeurs à rentrer)
        $query = "UPDATE t_contacts SET conName = :Names, conTel = :Tel, conEmail = :Email, conFonction = :Fonction, FK_entreprise = :Entreprise WHERE t_contacts.ID_Contact = :id";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête

        $binds = [
            'Names' => ['value' => $name, 'type' => PDO::PARAM_STR],
            'Email' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'Tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
            'Fonction' => ['value' => $fonction, 'type' => PDO::PARAM_STR],
            'Entreprise' => ['value' => $entreprise, 'type' => PDO::PARAM_INT],
            'id' => ['value' => $id, 'type' => PDO::PARAM_INT]
        ];
        // effectue la requête d'ajout en passant par une vérification 
        $this->queryPrepareExecute($query, $binds);
    }
    /* 
     * fonction qui permet la suppresion d'un bâtiment
     */
    public function deleteOneBuilding($id)
    {
        // requête sql incomplete en attente du binds
        $query = "DELETE FROM t_building WHERE t_building.ID_building = :prepareId";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
        $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
        // effectue la requête d'ajout en passant par une vérification  
        $this->queryPrepareExecute($query, $binds);
    }
    /* 
     * fonction qui permet la suppresion d'un Contact
     */
    public function deleteOneContact($id)
    {
        // requête sql incomplete en attente du binds
        $query = "DELETE FROM t_contacts WHERE t_contacts.ID_Contact = :prepareId";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
        $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
        // effectue la requête d'ajout en passant par une vérification  
        $this->queryPrepareExecute($query, $binds);
    }
    /* 
     * fonction qui permet la suppresion d'un Contact
     */
    public function deleteOneWork($id)
    {
        // requête sql incomplete en attente du binds
        $query = "DELETE FROM t_works WHERE t_works.ID_Work = :prepareId";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
        $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
        // effectue la requête d'ajout en passant par une vérification  
        $this->queryPrepareExecute($query, $binds);
    }
    /* 
     * fonction qui permet la suppresion d'un Contact
     */
    public function deleteOneEntreprise($id)
    {
        // requête sql incomplete en attente du binds
        $query = "DELETE FROM `t_entreprises` WHERE `t_entreprises`.`ID_entreprise` = :prepareId";
        // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
        $binds = ['prepareId' => ['value' => $id, 'type' => PDO::PARAM_INT]];
        // effectue la requête d'ajout en passant par une vérification  
        $this->queryPrepareExecute($query, $binds);
    }

    /* 
     * Fonction qui permet d'ajouter un bâtiment dans la base de données
     */
    public function AddBuilding($adress, $city, $npa, $eca, $parcelle)
    {
        // Requête SQL d'insertion (à adapter selon le nom de ta table)
        $query = "INSERT INTO t_building (buiAdress, buiCity, buiNPA, buiECA, buiParcelle) VALUES (:Adress, :City, :NPA, :ECA, :Parcelle)";
        
        // Binds avec les valeurs et leurs types pour la requête préparée
        $binds = [
            'Adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'City' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'NPA' => ['value' => $npa, 'type' => PDO::PARAM_INT],
            'ECA' => ['value' => $eca, 'type' => PDO::PARAM_INT],
            'Parcelle' => ['value' => $parcelle, 'type' => PDO::PARAM_INT]
        ];

        // Exécution sécurisée de la requête préparée
        $this->queryPrepareExecute($query, $binds);
    }

    public function AddEntreprise ($name, $adress, $city, $npa, $email, $tel)
    {
        // Requête SQL d'insertion (à adapter selon le nom de ta table)
        $query = "INSERT INTO t_entreprises (entName, entAdress, entCity, entNPA, entTel, entEmail) VALUES (:Names, :Adress, :City, :NPA, :email, :tel)";
        
        // Binds avec les valeurs et leurs types pour la requête préparée
        $binds = [
            'Names' => ['value' => $name, 'type' => PDO::PARAM_STR],
            'Adress' => ['value' => $adress, 'type' => PDO::PARAM_STR],
            'City' => ['value' => $city, 'type' => PDO::PARAM_STR],
            'NPA' => ['value' => $npa, 'type' => PDO::PARAM_INT],
            'email' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'tel' => ['value' => $tel, 'type' => PDO::PARAM_STR]
        ];

        // Exécution sécurisée de la requête préparée
        $this->queryPrepareExecute($query, $binds);
    }

    public function AddContact ($name, $email, $tel, $fonction, $entreprise)
    {
        // Requête SQL d'insertion (à adapter selon le nom de ta table)
        $query = "INSERT INTO t_contacts (conName, conTel, conEmail, conFonction, FK_entreprise ) VALUES (:Names, :email, :tel, :fonction, :entreprise)";
        
        // Binds avec les valeurs et leurs types pour la requête préparée
        $binds = [
            'Names' => ['value' => $name, 'type' => PDO::PARAM_STR],
            'email' => ['value' => $email, 'type' => PDO::PARAM_STR],
            'tel' => ['value' => $tel, 'type' => PDO::PARAM_STR],
            'fonction' => ['value' => $tel, 'type' => PDO::PARAM_STR],
            'entreprise' => ['value' => $entreprise, 'type' => PDO::PARAM_INT]
        ];

         // Exécution sécurisée de la requête préparée
         $this->queryPrepareExecute($query, $binds);
    }    


    public function AddWork ($title, $description, $price, $start, $end, $building, $entreprise)
    {
        // Requête SQL d'insertion (à adapter selon le nom de ta table)
        $query = "INSERT INTO t_works (worTitle, worDescription, worPrices, worStartDate, worEndDate, FK_building, FK_entreprise) VALUES 
                  (:Title, :Descriptions, :Prices, :StartDate, :EndDate, :Building, :Entreprise)";
        
        // Binds avec les valeurs et leurs types pour la requête préparée
        $binds = [
            'Title' => ['value' => $title, 'type' => PDO::PARAM_STR],
            'Descriptions' => ['value' => $description, 'type' => PDO::PARAM_STR],
            'Prices' => ['value' => $price, 'type' => PDO::PARAM_STR],
            'StartDate' => ['value' => $start, 'type' => PDO::PARAM_STR],
            'EndDate' => ['value' => $end, 'type' => PDO::PARAM_STR],
            'Building' => ['value' => $building, 'type' => PDO::PARAM_INT],
            'Entreprise' => ['value' => $entreprise, 'type' => PDO::PARAM_INT]
        ];

         // Exécution sécurisée de la requête préparée
         $this->queryPrepareExecute($query, $binds);
    }    
    
    public function GetContactsFromEntreprise ($entreprise)
    {
        // Requête SQL d'insertion (à adapter selon le nom de ta table)
        $query = "SELECT conName,conEmail,conTel,conFonction, entname FROM t_contacts JOIN t_entreprises ON FK_entreprise=ID_entreprise WHERE entname = :entreprise";
        
        // Binds avec les valeurs et leurs types pour la requête préparée
        $binds = 
        [
            'entreprise' => ['value' => $entreprise, 'type' => PDO::PARAM_STR]
        ];
         // Exécution sécurisée de la requête préparée
        $prepareTemp = $this->queryPrepareExecute($query, $binds);
        // transforme le format des données en tableau  
        $prepareTabTemp = $this->formatData($prepareTemp);
        // retourne un tableau
        return $prepareTabTemp;
    }
}
?>