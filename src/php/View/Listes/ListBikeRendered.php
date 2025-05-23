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
$data = $db->GetAllDataBikes();
$bikes = $db->GetAllBikesRendered();
// information nécessaire au liste décourlantes 
$sizes = $db->GetAllSizes();
$brands = $db->GetAllBrands();
$colors = $db->GetAllColors();
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
            <h2>Liste des vélos annoncé</h2>
            <div style="margin-bottom: 15px;">
                <label for="filterSerial">Numéro de série :</label>
                <input type="text" id="filterSerial" oninput="filterTable()">
                <label for="filterBrand">Marque :</label>
                <select id="filterBrand" onchange="filterTable()">
                    <option value="">Toutes</option>
                    <?php 
                        // parcours le tableau des marques 
                        foreach($brands as $brand )
                        {
                            echo '<option value="'. $brand["braName"] .'">'. $brand["braName"] .'</option>"';
                        }
                    ?>
                </select>

                <label for="filterSize">Taille :</label>
                <select id="filterSize" onchange="filterTable()">
                    <option value="">Toutes</option>
                    <?php 
                        // parcours le tableau des tailles         
                        foreach($sizes as $size )
                        {
                            echo '<option value="'. $size["sizSize"] .'">'. $size["sizSize"] .'</option>"';
                        }
                    ?>
                </select>

                <label for="filterColor">Couleur :</label>
                <select id="filterColor" onchange="filterTable()">
                    <option value="">Toutes</option>
                    <?php 
                        // parcours le tableau des couleurs
                        foreach($colors as $color )
                        {
                            echo '<option value="'. $color["colName"] .'">'. $color["colName"] .'</option>"';
                        }
                    ?>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table hide-image-column">
                    <thead>
                        <tr>
                            <th>Photos</th>
                            <th>Numéro de séries</th>
                            <th>Marque</th>
                            <th>Taille</th>
                            <th>couleur</th>
                            <th>Adresse où il a été retrouvé</th>
                            <th>Date de rendu</th>
                            <th>Commune de référence</th>
                            <th>Propriètaire</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- données -->
                        <?php
                            foreach($bikes as $bike)
                            {
                                if(!$bike["bikResitutionDate"] == NULL)
                                {
                                    // Carrousel des images (réaliser à l'aide de ChatGPT)
                                    echo '<tr><td>';
                                    // Crée un ID unique pour le carrousel en fonction de l'ID du vélo
                                    $carouselId = "carouselBike" . $bike["ID_bike"];
                                    // Filtre les images pour ce vélo uniquement
                                    $bikeImages = array_filter($data, fn($img) => $img["FK_bike"] == $bike["ID_bike"]);
                                    // Si le vélo a des images
                                    if (count($bikeImages) > 0) 
                                    {
                                        // Début du carrousel Bootstrap (manuel, pas automatique)
                                        echo '<div id="' . $carouselId . '" class="carousel slide" data-bs-ride="false">';
                                        // Conteneur des images du carrousel
                                        echo '<div class="carousel-inner" style="max-width: 200px;">';
                                        // Marque la première image comme active
                                        $active = 'active';
                                        // Affiche chaque image
                                        foreach ($bikeImages as $img) 
                                        {
                                            echo '<div class="carousel-item ' . $active . '">';
                                            echo '<img src="../../../../userContent/img/ImageBike/' . htmlspecialchars($img["bidPathFile"]) . '" class="d-block w-100" alt="Image vélo" style="object-fit: contain; max-height: 250px;">';
                                            echo '</div>';
                                            $active = ''; // Les suivantes ne doivent pas être "active"
                                        }
                                        echo '</div>'; // Fin des images
                                        // Bouton précédent
                                        echo '<button class="carousel-control-prev" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="prev">';
                                        echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                                        echo '<span class="visually-hidden">Précédent</span>';
                                        echo '</button>';
                                        // Bouton suivant
                                        echo '<button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">';
                                        echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                                        echo '<span class="visually-hidden">Suivant</span>';
                                        echo '</button>';
                                        echo '</div>'; // Fin du carrousel
                                    } 
                                    else 
                                    {
                                        // Aucune image disponible
                                        echo 'Aucune image';
                                    }

                                    // Numéro de serie du cadre 
                                    echo '<td>' . $bike["bikFrameNumber"] . '</td>';
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
                                    // Commune oû le vélo est stocker 
                                    echo '<td><a href="../DetailPersonne.php?ID=' . $bike["ID_personne"] . '">' . $bike["perLastName"] . " " . $bike["perFirstName"] . '</td>';
                                    // Affichage des options en fonctions de la sessions
                                    echo '<td><a class="LinksOptions" href=""><img class="Logo" src="../../../../userContent/img/Logo/modificationIcon.jpg" alt="Modification"></a><br>
                                    <a class="LinksOptions OptionDetail" href="../DetailFoundedBike.php?ID=' . $bike["ID_bike"] . '"><img class="Logo" src="../../../../userContent/img/Logo/detailsIcon.jpg" alt="Suppression"></a>
                                    <a class="LinksOptionsDel" href="" onclick="return deleteCheck();"><img class="Logo" src="../../../../userContent/img/Logo/TrashIcon.png" alt="Suppression"></a>';
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
<script>
    // Fonction qui filtre les lignes du tableau en fonction des filtres sélectionnés
    function filterTable() 
    {
        // Récupère les valeurs des filtres (converties en minuscules pour éviter les problèmes de casse)
        const serialFilter = document.getElementById("filterSerial").value.toLowerCase(); // numéro de série
        const brandFilter = document.getElementById("filterBrand").value.toLowerCase();   // marque
        const sizeFilter = document.getElementById("filterSize").value.toLowerCase();     // taille
        const colorFilter = document.getElementById("filterColor").value.toLowerCase();   // couleur

        // Sélectionne toutes les lignes du tableau (à l'intérieur du tbody)
        const rows = document.querySelectorAll("table tbody tr");

        // Parcours chaque ligne du tableau
        rows.forEach(row => 
        {
            // Récupère les données des cellules correspondantes aux filtres
            const serial = row.cells[0].textContent.toLowerCase(); // cellule 1 : numéro de série
            const brand = row.cells[1].textContent.toLowerCase();  // cellule 2 : marque
            const size = row.cells[2].textContent.toLowerCase();   // cellule 3 : taille
            const color = row.cells[3].textContent.toLowerCase();  // cellule 4 : couleur

            // Vérifie si la ligne correspond à tous les filtres
            const show =
                serial.includes(serialFilter) && // le numéro de série contient la chaîne recherchée
                (brandFilter === "" || brand === brandFilter) && // si un filtre marque est défini, il doit correspondre
                (sizeFilter === "" || size === sizeFilter) &&     // idem pour la taille
                (colorFilter === "" || color === colorFilter);    // idem pour la couleur

            // Affiche la ligne si elle correspond, sinon la masque
            row.style.display = show ? "" : "none";
        });
    }
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