<?php
//démarer la session
session_start();
/**
* Auteur :         Bajro Osmanovic
* Date :           06.05.2025 → Modif : 28.05.2025
* Description :    fichier php qui permet de supprimer une personne
*/
//ajoute le fichier qui gère les requette SQL
require_once('../Model/config.php');
require_once('../Model/database.php');
// supprime la personne selectionner
Database::getInstance()->DeleteOnePerson($_GET["ID"]); 

header("Location: ../../View/Listes/ListePersonnesPage.php");
