<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 
 * Description : page affichants toutes les communes qui ont été admise dans les membres 
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// récupèrer toute les communes 
$bikes = $db->GetAllBikes();
?>

<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <!--liens avec le css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
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
        <div class="table-container">
            <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button>
            <h2>Liste des communes membres</h2>
            <table>
                <tr>
                    <th>bikFrameNumber</th>
                    <th>Marque</th>
                    <th>Taille</th>
                    <th>couleur</th>
                    <th>Adresse où il a été retrouvé</th>
                    <th>Date découverte</th>
                    <th>Commune de référence</th>
                    <th>Options</th>
                </tr>
                <!-- données -->
                <?php
                    foreach($bikes as $bike)
                    {
                        // Numéro de serie du cadre 
                        echo '<tr><td>' . $bike["bikFrameNumber"] . '</td>';
                        // Marque du vélo 
                        echo '<td>' . $bike["braName"] . '</td>';
                        // Taille du vélo 
                        echo '<td>' . $bike["sizSize"] . '</td>';
                        // Couleur du vélo 
                        echo '<td>' . $bike["colName"] . '</td>';
                        // lieu ou le vélo a été retrouvé
                        echo '<td>' . $bike["bikPlace"] . '</td>';
                        // date de la découverte du vélo 
                        echo '<td>' . $bike["bikDate"] . '</td>';
                        // Commune oû le vélo est stocker 
                        echo '<td>' . $bike["comName"] . '</td>';
                        // Affichage des options en fonctions de la sessions
                        echo '<td><a class="LinksOptions" href="">Modifier</a><br>
                        <a class="LinksOptions" href="">rendre</a><br>
                        <a class="LinksOptionsDel" href="" onclick="return deleteCheck();">Supprimer</a>';
                    }
                ?>
            </table>
        </div>
    </body>
</html>
<script>
    // Sélectionne tous les éléments ayant la classe "LinksOptions"
    document.querySelectorAll('.LinksOptionsDel').forEach(link => 
    {
        // Ajoute un écouteur d'événement "click" sur chaque lien sélectionné
        link.addEventListener('click', function(event) 
        {
            // Affiche une boîte de confirmation avec le message
            // Si l'utilisateur clique sur "Annuler", confirm() renvoie "false"
            if (!confirm("Voulez-vous vraiment supprimer cet élément ?")) 
            {
                // Annule l'action par défaut du lien (empêche la navigation)
                event.preventDefault();
            }
        });
    });
</script>