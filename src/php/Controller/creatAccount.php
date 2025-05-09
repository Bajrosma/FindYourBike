<?php
//starting the session
session_start();

/**
* Auteur :         Bajro Osmanovic
* Date :           06.05.2025
* Description :    fichier php qui permet de crée des comptes utilisateurs
*/

//ajoute le fichier qui gère les requette SQL
require_once('../Model/config.php');
require_once('../Model/database.php');

$motDePasse = 'admin1234';
$password = password_hash($motDePasse, PASSWORD_DEFAULT);
$user = 'Admin';

// recupère tout les utilisateurs pour la vérification
Database::getInstance()->CreateAccount($user, $password, 0); 