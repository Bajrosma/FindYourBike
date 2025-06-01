<?php
    //commence le système de session
    session_start();
    /**
     * Auteur : Bajro Osmanovic
     * Date : 21.05.2025 → Modif : 
     * Description : page affichants les statistiques 
     */
    // Inclusion des fichiers de configuration et de gestion de la base de données
    require_once('../Model/config.php');
    require_once('../Model/database.php');
    // Création d'une instance de la classe Database pour l'accès à la base de données
    $db = Database::getInstance();
    // reinitialise le message après une action 
    $_SESSION["MessageAdd"] = "";
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <!--liens avec le css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../ressources/css/codepen.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Inclure Bootstrap JS et jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FindYourBike</title>
       
        <!--Change l'icone de la page (celle a côté du titre)-->
        <link rel="icon" href="userContent/img/Logo2.png" type="image/x-icon">
    </head>
    <body>
        <div class="container">
            <a href="Accueil/AccueilCommune.php" class="btn-back">← Retour</a>
            <h1>Choix de la durée des statistiques</h1>
            <div class="grid">
                <a class="btn" href="Formulaires/FormulaireStatistiqueChoice.php?Choice=1">Trimestre</a>
                <a class="btn" href="Formulaires/FormulaireStatistiqueChoice.php?Choice=0">Annuel</a>
            </div>
        </div>
    <div id="graph"></div>
    </bod<>
