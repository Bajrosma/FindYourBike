<?php
//starting the session
session_start();

/**
* Auteur :         Bajro Osmanovic
* Date :           06.05.2025
* Description :    fichier php qui permet de supprimer une personne
*/

//ajoute le fichier qui gère les requette SQL
require_once('../Model/config.php');
require_once('../Model/database.php');

// recupère tout les utilisateurs pour la vérification
Database::getInstance()->DeleteOneBike($_GET["ID"]); 