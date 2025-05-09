<?php
/**
 * Auteur : Bajro Osmanovic
 * Date : 09.95.2025 → Modif : 
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
            $query = "SELECT useName, usePassword FROM t_user ";
   
            $prepareTemp = $this->querySimpleExecute($query);
            $prepareTabTemp = $this->formatData($prepareTemp);
   
            return $prepareTabTemp;
        }

        /**
         * test
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
}
?>