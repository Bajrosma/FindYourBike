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
// récupère les informations du vélo sélectionner 
$bike =  $db->GetOneBike($_GET["ID"]);

var_dump($bike);

foreach ($bike as $key => $value) {
    $_SESSION['Bike'][$key] = $value;
}
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
    <h1>Modification d'un vélo trouvé</h1>
    <form action="../../Controller/ChecksFormulaires/FormulaireBikeCheck.php" method="post">
    <?php 
        $fields = [
            'bikPlace' => 'lieu de découverte (adresse complète)',
            'bikFrameNumber' => 'Numéro de série (cadre)'
        ];
        // parcours le tableau des champs de type text
        foreach ($fields as $name => $label) {
            $sessionValue = $_SESSION[$name] ?? $_SESSION['Bike'][0][$name] ?? '';
            $errorKey = "ErrorMessage" . ucfirst(str_replace("bik", "", $name));
            echo "<div class='form-group row mb-3'>";
            echo "<label class='col-sm-4 col-form-label' for=\"$name\">$label</label><br>";
            echo "<div class='col-sm-8'>";
            echo "<input class='form-control' type=\"text\" name=\"$name\" id=\"$name\" value=\"" . htmlspecialchars($sessionValue) . "\" /><br>";
            if (!empty($_SESSION[$errorKey])) {
                echo '<p class="text-danger">' . $_SESSION[$errorKey] . '</p>';
            }
            echo '</div></div>';
        }
    ?>
    <!-- Date de découverte -->
    <div class='form-group row mb-3'>
        <label for="bikDate" class='col-sm-4 col-form-label'>Date de découverte</label>
        <div class='col-sm-8'>
            <input class='form-control' type="date" id="bikDate" name="bikDate" value="<?php echo $bike[0]['bikDate']; ?>">
        </div>
    </div>
    <br>
    <!-- Liste des couleurs de vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_color" class='col-sm-4 col-form-label'>Couleur du vélo</label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_color" id="FK_color">
            <?php 
                // parcours le tableau des couleurs
                foreach($colors as $color )
                {
                    echo '<option value="'. $color["ID_color"] .'">'. $color["colName"] .'</option>"';
                }
            ?>
        </select>
        <br>
        </div>
    </div>
    <!-- Liste des marques de vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_brand" class='col-sm-4 col-form-label'>Marque du vélo</label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_brand" id="FK_brand">
            <?php 
                // parcours le tableau des marques 
                foreach($brands as $brand )
                {
                    echo '<option value="'. $brand["ID_brand"] .'">'. $brand["braName"] .'</option>"';
                }
            ?>
        </select>
        <br>
        </div>
    </div>
    <!-- Liste des tailles de vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_size" class='col-sm-4 col-form-label'>Taille du vélo </label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_size" id="FK_size">
            <?php 
                // parcours le tableau des tailles         
                foreach($sizes as $size )
                {
                    echo '<option value="'. $size["ID_size"] .'">'. $size["sizSize"] .'</option>"';
                }
            ?>
        </select>
        <br>
        </div>
    </div>
    <!-- Liste des communes pouvant acceuilir le vélo -->
    <div class='form-group row mb-3'>
        <label for="FK_commune" class='col-sm-4 col-form-label'>Commune</label>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_commune" id="FK_commune">
            <?php 
                // parcours le tableau des communes 
                foreach($communes as $commune )
                {
                    // si les communes ont une inscription valide, alors il crée un choix de plus pour la liste déroulante.
                    if($commune["comInscription"] == 1)
                    echo '<option value="'. $commune["ID_commune"] .'">'. $commune["comName"] .'</option>"';
                }
            ?>
        </select>
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