<?php
//démarer la session
session_start();
/**
* Auteur :         Bajro Osmanovic
* Date :           06.05.2025 → Modif : 28.05.2025
* Description :    fichier php qui permet de supprimer une commune
*/
//ajoute le fichier qui gère les requette SQL
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// fait appel à la fonction qui supprime la commune
Database::getInstance()->DeleteOneCommune($_GET["ID"]); 
// retourne sur la liste des communes
header("Location: ../../View/Listes/ListeCommunePage.php");
