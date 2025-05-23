<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 23.05.2025
 * Description : page affichants toutes les communes qui n'ont pas encore été admise dans les membres 
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// récupèrer toute les communes 
$Responsables = $db->GetResponsable();
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
            <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button>
            <h2>Liste des inscription en cours</h2>
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Nom de la commune</th>
                        <th>Adresse complète</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Nom du responsable</th>
                        <th>Options</th>
                    </tr>
                    <!-- données -->
                    <?php
                        foreach ($communes as $commune) {
                            if ($commune['comInscription'] == 0) 
                            {
                                // Nom de la commune
                                echo '<tr><td>' . $commune["comName"] . '</td>';                    
                                // Adresse complète
                                echo "<td>" . $commune["comAdress"] . ", " .  $commune["comCity"] . " " .  $commune["comNPA"]. "</td>";                    
                                // Email
                                echo "<td>" . $commune["comEmail"] . "</td>";
                        
                                // Téléphone
                                echo "<td>" . $commune["comTel"] . "</td>";
                                foreach($Responsables as $key)
                                if($commune["ID_commune"] == $key["FK_Commune"])
                                {
                                    echo "<td><em>". $key["perLastName"] . " " . $key["perFirstName"] ."</em></td>";
                                }
                                // options pour accepter ou refuser l'inscription
                                echo '<td>
                                        <a class="LinksOptions" href="../../Controller/AcceptInscription.php?ID=' . $commune["ID_commune"] . '"><img class="Logo" src="../../../../userContent/img/Logo/AcceptIcon.png" alt="Accepter"></a><br>
                                        <a class="LinksOptionsDel" href="../../Controller/RefuseInscription.php?ID=' . $commune["ID_commune"] . '" onclick="return deleteCheck();"><img class="Logo" src="../../../../userContent/img/Logo/RefuseIcon.jpg" alt="Refuser"></a>
                                    </td></tr>';
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