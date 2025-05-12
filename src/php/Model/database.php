<?php
/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 12.05.2025
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
         * test
         * @return -- renvoie un tableau avec tous les bâtiments
         */
        public function Getusers()
        {
            $query = "SELECT useName, usePassword, usePrivilage FROM t_user ";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * recherche une commune
         * @return -- renvoie un tableau avec tous les bâtiments
         */
        public function CreateAccount($User, $password)
        {
            $query = "INSERT INTO t_user (useName, usePassword) VALUES (:username, :passwords)";
            // tableau qui permet de vérifier si les valeurs sont ok et de les rentrées les valeurs dans la requête
            $binds = [
            'username' => ['value' => $User, 'type' => PDO::PARAM_STR],
            'passwords' => ['value' => $password, 'type' => PDO::PARAM_STR]
            ];    
            // Exécution sécurisée de la requête préparée
            $this->queryPrepareExecute($query, $binds);
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
         * Fonction qui recherche les commune 
         * @return -- renvoie les informations des communes trouvé
         */
        public function GetAllCommunes()
        {
            // requête pour récuperer les communes
            $query = "SELECT 1	ID_commune, comName, comAdress, comNPA, comCity, comEmail, comTel, comInscription FROM t_communes ";
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
            // Exécution sécurisée de la première requête préparée
            $this->queryPrepareExecute($query, $binds);
        }
}
?>