<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 15.09.2025 → Modif : 
 * Description : page affichants les details du vélo
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../Model/config.php');
require_once('../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// récupèrer toute les informations sur le vélos
$bike = $db->GetOneBike($_GET["ID"]);
$bikeImages = $db->GetDataFromOneBike($_GET["ID"]);
// récupérer les informations du propriétaire si il est rendu
if(!$bike[0]['bikResitutionDate'] == NULL)
{
    $owner = $db->GetOnwerFromBike($_GET["ID"]);
}
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
        <div class="table-container">
            <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button> 
            <h2>Détails du vélo</h2>   
             
            <?php
                 // Crée un ID unique pour le carrousel en fonction de l'ID du vélo
                $carouselId = "carouselBike" . $_GET["ID"];
                // Si le vélo a des images
                if (count($bikeImages) > 0) {
                    echo '<div id="' . $carouselId . '" class="carousel slide mx-auto mb-4" data-bs-ride="false" style="max-width: 800px;">';
                    echo '<div class="carousel-inner">';
                    $active = 'active';
                    foreach ($bikeImages as $img) {
                        echo '<div class="carousel-item ' . $active . '">';
                        echo '<img src="../../../userContent/img/ImageBike/' . htmlspecialchars($img["bidPathFile"]) . '" class="d-block w-100" alt="Image vélo" style="object-fit: contain; max-height: 600px;">';
                        echo '</div>';
                        $active = '';
                    }
                    echo '</div>';
                    echo '<button class="carousel-control-prev" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="prev">';
                    echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    echo '<span class="visually-hidden">Précédent</span>';
                    echo '</button>';
                    echo '<button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">';
                    echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    echo '<span class="visually-hidden">Suivant</span>';
                    echo '</button>';
                    echo '</div>';
                } else {
                    echo '<p class="text-center">Aucune image disponible pour ce vélo.</p>';
                }
                // affichage des information
                $champs =
                [
                    'bikPlace' => 'Lieu de découverte',
                    'bikDate' => 'Trouvé le',
                    'bikResitutionDate' => 'Rendu le',
                    'bikFrameNumber' => 'Numéro de série chassis',
                    'braName' => 'Marque',
                    'sizSize' => 'Taille',
                    'colName' => 'Couleur',
                    'comName' => 'Nom de la commune où il est stocké'
                ];
                foreach ($bike[0] as $key => $value) {
                    // On ignore bikResitutionDate s'il est NULL
                    if ($key === 'bikResitutionDate' && is_null($value)) {
                        continue;
                    }
                
                    // Vérifie que la clé existe bien dans le tableau des champs
                    if (isset($champs[$key])) {
                        echo '<div class="detail-row">';
                        echo '<span class="detail-label">' . htmlspecialchars($champs[$key]) . ' :</span><span>';
                        echo htmlspecialchars($value);
                        echo '</span></div>';
                    }
                }    
                // indique qui est le propriétaire
                if(!$bike[0]['bikResitutionDate'] == NULL)
                {
                echo '  <div class="detail-row">
                            <span class="detail-label"> nom du propriétaire :</span>
                            <span>' . $owner[0]['perFirstName'] . ' ' . $owner[0]['perLastName'] .'</span>
                        </div>';
                } 
            ?>

        </div>
    </body>
</html>
