<?php 
    //starting the session
    session_start();

    /**
    * Auteur :         Bajro Osmanovic
    * Date :           06.05.2025
    * Description :    fichier php qui verifie que l'utilisateur connecter existe et rentre le bon mot de passe
    */

    //ajoute le fichier qui gère les requette SQL
    require_once('../Model/config.php');
    require_once('../Model/database.php');

    // recupère tout les utilisateurs pour la vérification
    $users=Database::getInstance()->Getusers(); 

    //Stock les informations de connexions
    $username=$_POST["username"];
    $password=$_POST["password"];

    $validConnection = false;

    switch($_POST['action'])
    {
        case'login':
            // verifie que les données rentrer ne sont pas vide 
            if(empty($_POST["username"])||empty($_POST["password"]))
            {
                // message d'erreur en cas de case vide
                $_SESSION["MessageErrorLogin"] = "veuillez rentrer votre login et mot de passe";

                //retour en arrière
                header("Location:../../../index.php");
            }
            // si les cases ne sont pas vides il continue le processus de vérification
            else
            {
                // parcours tout les utilisateurs
                foreach($users as $user)
                {
                    // contrôle si c'est le même user
                    if($user["useName"] == $username)
                    {
                        // contrôle si c'est le même mot de passe
                        if(password_verify($password,$user["usePassword"]))
                        {
                            if($user["usePrivilage"] == 2)
                            {
                                $_SESSION["rights"] = $user["usePrivilage"];
                            }
                            else if ($user["usePrivilage"] == $user["usePrivilage"])
                            {
                                $_SESSION["rights"] = $user["usePrivilage"];
                            }
                            else 
                            {
                                $_SESSION["rights"] = 0;
                            }
                            $validConnection = true;
                            $_SESSION["MessageErrorLogin"] = "";
                            break;
                        }
                    // si les conditions ne sont pas remplis, retourne en arrière
                    else
                    {
                    // message d'erreur
                        $_SESSION["MessageErrorLogin"] = "faute de mot de passe";
                        header("Location:../../../../index.php");
                    }
                    }
                    // si les conditions ne sont pas remplis, retourne en arrière
                    else
                    {
                        $_SESSION["MessageErrorLogin"] = "utilisateur n'as pas été trouvé";
                        header("Location:../../../../index.php");
                    } 
                }       
            }
            break;
    }
    if($validConnection)
    {
        if($_SESSION["rights"] == 2)
        {
            // vas sur la page d'acceuil
            header("Location:../View/Accueil/AccueilAdmin.php");
        }
        else 
        {
            // vas sur la page d'acceuil
            header("Location:../View/Accueil/AccueilCommune.php");
        }
    }