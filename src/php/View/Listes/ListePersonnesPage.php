<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 
 * Description : Page affichant toutes les personnes enregistere à l'aide de l'application
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// récupèrer toute les communes 
$poeple = $db->GetAllUsers();
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
            <h2>Liste des personnes inscrite</h2>
            <table>
                <tr>
                    <th>Nom de la personne</th>
                    <th>Adresse complète</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>roles</th>
                    <th>Options</th>
                </tr>
                <!-- données -->
                <?php
                    foreach($poeple as $person)
                    {
                        if($person['comInscription'] == 1)
                        {
                        // Nom de la commune
                        echo '<tr><td>' . $person["perFirstName"] . " " . $person["perLastName"] .'</td>';
                        // commande pour afficher le l'adresse de l'communes
                        echo "<td>" . $person["perAdress"] . ", " . $person["perCity"] . " " . $person["perNPA"] . "</td>";
                        // affiche l'email des communes 
                        echo "<td>" . $person["perEmail"] . "</td>";
                        // affiche le numéro de telephone des communes
                        echo "<td>" . $person["perTel"] . "</td>";
                        //  défini son rôle à la commune ou pas
                        echo "<td>" . $person["perRole"] . "</td>";
                        // Affichage des options en fonctions de la sessions
                        echo '<td><a class="LinksOptions" href="">Modifier</a><br>
                        <a class="LinksOptionsDel" href="" onclick="return deleteCheck();" style="color: #3D2C23;">Supprimer</a>';
                        }
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