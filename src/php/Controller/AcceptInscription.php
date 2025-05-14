<?php 
    //starting the session
    session_start();

    /**
    * Auteur :         Bajro Osmanovic
    * Date :           14.05.2025
    * Description :    fichier php qui permet de valider une inscription de commune
    */

    //ajoute le fichier qui gère les requette SQL
    require_once('../Model/config.php');
    require_once('../Model/database.php');

    // Création d'une instance de la classe Database pour l'accès à la base de données
    $db = Database::getInstance();
    // accepte la commune en changeant la valeur d'inscription sur la BD
    $db->AcceptInscription($_GET["ID"]);

    header(history.back());
    exit();