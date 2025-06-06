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
$communes = $db->GetAllCommunes();
// reinitialise le message après une action 
$_SESSION["MessageAdd"] = "";
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
            <button onclick="history.back()" class="btn-back">← Retour</button>
            <h2>Liste des communes membres</h2>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Nom de la commune</th>
                        <th>Adresse complète</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <?php 
                            // Affichage des options en fonctions de la sessions
                            if($_SESSION["rights"] == 2)
                            {
                        ?>
                        <th>Options</th>
                        <?php 
                            }
                        ?>
                    </tr>
                    <!-- données -->
                    <?php
                        foreach($communes as $commune)
                        {
                            if($commune['comInscription'] == 1)
                            {
                                // Nom de la commune
                                echo '<tr><td>' . $commune["comName"] . '</td>';
                                // commande pour afficher le l'adresse de l'communes
                                echo "<td>" . $commune["comAdress"] . ", " . $commune["comCity"] . " " . $commune["comNPA"] . "</td>";
                                // affiche l'email des communes 
                                echo "<td>" . $commune["comEmail"] . "</td>";
                                // affiche le numéro de telephone des communes
                                echo "<td>" . $commune["comTel"] . "</td>";
                                // Affichage des options en fonctions de la sessions
                                // Affichage des options en fonctions de la sessions
                                if($_SESSION["rights"] == 2)
                                {
                                    echo '<td><a class="LinksOptions" href="../Modify/ModifyCommunePage.php?ID=' . $commune["ID_commune"] .'"><img href="../../userContent/img/Logo/modificationIcon.jpg"></a><br>
                                    <a class="LinksOptionsDel" href="../../Controller/DeletePages/DeleteCommune.php?ID=' . $commune["ID_commune"] . '" onclick="return deleteCheck();"><img class="Logo" src="../../../../userContent/img/Logo/TrashIcon.png" alt="Supprimer"></a>';
                                }    
                            }
                        }
                    ?>
                </table>
            </div>
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