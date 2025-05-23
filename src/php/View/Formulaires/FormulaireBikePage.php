<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 12.95.2025 → Modif : 
 * Description : page du formulaire d'ajout d'un vélo
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// information nécessaire au liste décourlantes 
$sizes = $db->GetAllSizes();
$brands = $db->GetAllBrands();
$colors = $db->GetAllColors();
$communes = $db->GetAllCommunesDropDown();
?>

<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--liens avec le css personnelle et css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
        <!-- Inclure Bootstrap JS et jQuery -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
        <title>FindYourBike</title>
    </head>
<body>

  <div class="container">
    <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button>
    <h1>Ajout d'un vélo trouvé</h1>
    <form action="../../Controller/ChecksFormulaires/FormulaireBikeCheck.php" method="post" enctype="multipart/form-data">
    <?php 
        $Champs = [
            'bikPlace' => 'lieu de découverte (adresse complète)',
            'bikFrameNumber' => 'Numéro de série (cadre)'
        ];
        // parcours le tableau des champs de type text
        foreach ($Champs as $Champ => $label) 
        {
            // récolte les informations sur d'éventuelle message d'erreurs
            $errorMessage = isset($_SESSION["ErrorMessage" . ucfirst($Champ)]) ? $_SESSION["ErrorMessage" . ucfirst($field)] : '';
            // vérifie si une entrée a été sauvée dans la session, si oui le champ reprend le même texte et si non, il laisse vide
            $value = isset($_SESSION[$Champ]) ? htmlspecialchars($_SESSION[$Champ]) : '';
            echo "
            <div class='form-group row mb-3'>
                <label for='$Champ' class='col-sm-4 col-form-label'>$label</label>
                <div class='col-sm-8'>
                    <input type='text' class='form-control' name='$Champ' id='$Champ' value='$value'>
                    <p class='text-danger'>$errorMessage</p> <!-- Affichage du message d'erreur si présent -->
                </div>
            </div>";
        }
    ?>
    <!-- Date de découverte -->
    <div class='form-group row mb-3'>
        <label for="bikDate" class='col-sm-4 col-form-label'>Date de découverte</label>
        <div class='col-sm-8'>
            <input class='form-control' type="date" id="bikDate" name="bikDate">
        </div>
    </div>
    <!-- Liste des couleurs de vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_color" class='col-sm-4 col-form-label'>Couleur du vélo</label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_color" id="FK_color">
            <option value=""></option>
            <?php 
                // parcours le tableau des couleurs
                foreach($colors as $color )
                {
                    echo '<option value="'. $color["ID_color"] .'">'. $color["colName"] .'</option>"';
                }
            ?>
        </select>
        </div>
    </div>
    <!-- Liste des marques de vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_brand" class='col-sm-4 col-form-label'>Marque du vélo</label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_brand" id="FK_brand">
            <option value=""></option>
            <?php 
                // parcours le tableau des marques 
                foreach($brands as $brand )
                {
                    echo '<option value="'. $brand["ID_brand"] .'">'. $brand["braName"] .'</option>"';
                }
            ?>
        </select>
        </div>
    </div>
    <!-- Liste des tailles de vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_size" class='col-sm-4 col-form-label'>Taille du vélo </label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_size" id="FK_size">
            <option value=""></option>
            <?php 
                // parcours le tableau des tailles         
                foreach($sizes as $size )
                {
                    echo '<option value="'. $size["ID_size"] .'">'. $size["sizSize"] .'</option>"';
                }
            ?>
        </select>
        </div>
    </div>
    <!-- Liste des communes pouvant acceuilir le vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_commune" class='col-sm-4 col-form-label'>Commune</label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_commune" id="FK_commune">
            <option value=""></option>
            <?php 
                // parcours le tableau des communes 
                foreach($communes as $commune )
                {
                    // si les communes ont une inscription valide, alors il 
                    // crée un choix de plus pour la liste déroulante.
                    if($commune["comInscription"] == 1)
                    echo '<option value="'. $commune["ID_commune"] .'">'. 
                         $commune["comName"] .'</option>"';
                }
            ?>
        </select>
        </div>
    </div>
    <div  class='form-group row mb-3'>
        <!-- Champ permettant de sélectionner plusieurs fichiers images -->
        <label for="bidPathFile" class='col-sm-4 col-form-label'>Choisir jusqu'à 3 images :</label>
        <div class='col-sm-8'>
            <!-- Le nom "images[]" indique un tableau d'images, et "multiple" permet d'en sélectionner plusieurs -->
            <input class='form-control' id="bidPathFile" name="images[]" multiple required type="file">
        </div>
    </div>
    <?php 
        echo  $_SESSION["MessageAdd"];
    ?>
    <div class="text-center">
        <button type="submit" class="btn">Soumettre le formulaire</button>
    </div>
</form>

</body>
</html>