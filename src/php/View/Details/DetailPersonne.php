<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 15.09.2025 → Modif : 
 * Description : page affichants les details de la personnes qui a récuperer le vélos
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../Model/config.php');
require_once('../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// récupèrer toute les informations sur la personnes 
$Personne = $db->GetOnePerson($_GET["ID"]);
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
        <div class="details-container">
            <h2>Détails du vélo</h2>    
            <?php

                $champs =
                [
                    'perFirstName' => 'Prénom',
                    'perLastName' => 'Nom',
                    'perEmail' => 'Email',
                    'perTel' => 'téléphone',
                    'perAdress' => 'Adresse',
                    'perCity' => 'Localité',
                    'perNPA' => 'NPA',
                    'perRole' => 'Fonction'
                ];

                foreach ($Personne[0] as $key => $value) 
                {
                    echo '<div class="detail-row">';
                    echo '<span class="detail-label">' . htmlspecialchars($champs[$key]) . ' :</span><span>';
                    echo htmlspecialchars($value);
                    echo '</span></div>';
                }             
            ?>
            <div class="btn-container">
            <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button> 
            </div>
        </div>
    </body>
</html>
