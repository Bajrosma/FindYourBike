<?php
//démarer la session
session_start();
/**
* Auteur :         Bajro Osmanovic
* Date :           06.05.2025 → Modif : 28.05.2025
* Description :    fichier php qui permet de supprimer un vélo
*/
//ajoute le fichier qui gère les requette SQL
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// fait appel à la fonction qui supprime un vélo
Database::getInstance()->DeleteOneBike($_GET["ID"]);
// redirection sur la liste des vélos
header("Location: ../../View/Listes/ListeBikePage.php");
