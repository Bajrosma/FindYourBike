<?php
 //commence le système de session
 session_start();
 /**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 27.05.2025
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
        <title>FindYourBike</title>
    </head>
<body>

  <div class="container">
    <a href="../Accueil/AccueilCommune.php" class="btn-back">← Retour</a>
    <h1>Ajout d'un vélo trouvé</h1>
    <small> * : indique les champs obligatoires</small>
    <form action="../../Controller/ChecksFormulaires/FormulaireBikeCheck.php" method="post" enctype="multipart/form-data">
    <?php 
        $Champs = [
            'bikPlace' => 'lieu de découverte (adresse complète)*',
            'bikFrameNumber' => 'Numéro de série (cadre)*'
        ];

        $exemple = [
            'bikPlace' => "exemple : Lausanne gare 10, 1004 Lausanne",
            'bikFrameNumber' => 'exemple : PY98765432 ( minimum 5 et maximum 15 caractères )',
        ];
        // parcours le tableau des champs de type text
        foreach ($Champs as $Champ => $label) 
        {
            // récolte les informations sur d'éventuelle message d'erreurs
            $errorMessage = $_SESSION["ErrorMessage" . ucfirst(str_replace("bik", "", $Champ))] ?? '';
            // vérifie si une entrée a été sauvée dans la session, si oui le champ reprend le même texte et si non, il laisse vide
            $value = isset($_SESSION[$Champ]) ? htmlspecialchars($_SESSION[$Champ]) : '';
            $example = $exemple[$Champ] ?? '';
            echo "
            <div class='form-group row mb-3 align-items-start'>
                <div class='col-sm-4 text-start'>
                    <label for='$Champ' class='form-label fw-bold''>$label</label><br>
                    <small class='text-muted'>$example</small>
                </div>
                <div class='col-sm-8'>
                    <input type='text' class='form-control' name='$Champ' id='$Champ' value='$value'>
                    <small class='text-danger'>$errorMessage</small> <!-- Affichage du message d'erreur si présent -->
                </div>
            </div>";
        }
    ?>
    <!-- Date de découverte -->
    <div class='form-group row mb-3 align-items-start'>
        <div class='col-sm-4 text-start'>
            <label for="bikDate" class='form-label fw-bold'>Date de découverte*</label><br>
            <small class='text-muted'>exemple : 22.01.2023</small>
        </div>
        <div class="col-sm-8">
            <?php
                if(isset($_SESSION["bikDate"]))
                {
                    echo '<input class="form-control" type="date" id="bikDate" name="bikDate" value="'. $_SESSION["bikDate"] .'">';
                }
                else
                {
                    echo '<input class="form-control" type="date" id="bikDate" name="bikDate">';
                }
            ?>
            <?php
                if(isset($_SESSION["ErrorMessageDate"]))
                {
                    echo "<small class='text-danger'>" . $_SESSION["ErrorMessageDate"] . "</small>" ;
                }
            ?>
        </div>
    </div>
    <!-- Liste des couleurs de vélo -->
    <div class='form-group row mb-3 align-items-start'>
        <div class='col-sm-4 text-start'>
            <label for="FK_color" class='form-label fw-bold'>Couleur du vélo*</label><br>
            <small class='text-muted'>exemple : Rouge</small>
        </div>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_color" id="FK_color">
            <option value=""></option>
            <?php 
                // parcours le tableau des couleurs
                foreach($colors as $color )
                {
                    // contrôle si la variable existe
                    if(isset($_SESSION["FK_color"]))
                    {
                        // permet de sauver le choix de l'utilisateurs
                        if($_SESSION["FK_color"] == $color["ID_color"])
                        {
                            echo '<option value="'. $color["ID_color"] .'" selected>'. $color["colName"] .'</option>';
                        }
                        else
                        {
                            echo '<option value="'. $color["ID_color"] .'">'. $color["colName"] .'</option>';
                        }
                    }
                    // si la variable n'existe pas, alors crée le dropdown sans chercher a mettre une valeur en selected   
                    else
                    {
                        echo '<option value="'. $color["ID_color"] .'">'. $color["colName"] .'</option>';
                    }
                }
            ?>
        </select>
        <?php
                if(isset($_SESSION["ErrorMessageColor"]))
                {
                    echo "<small class='text-danger'>" . $_SESSION['ErrorMessageColor'] . "</small>" ;
                }
            ?>
        </div>
    </div>
    <!-- Liste des marques de vélo -->
    <div class='form-group row mb-3 align-items-start'>
        <div class='col-sm-4 text-start'>
            <label for="FK_brand" class='form-label fw-bold'>Marque du vélo*</label><br>
            <small class='text-muted'>exemple : Canyon</small>
        </div>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_brand" id="FK_brand">
            <option value=""></option>
            <?php 
                // parcours le tableau des marques
                foreach($brands as $brand )
                {
                    // contrôle si la variable existe
                    if(isset($_SESSION["FK_brand"]))
                    {
                        // permet de sauver le choix de l'utilisateurs
                        if($_SESSION["FK_brand"] == $brand["ID_brand"])
                        {
                            echo '<option value="'. $brand["ID_brand"] .'" selected>'. $brand["braName"] .'</option>';
                        }
                        else
                        {
                            echo '<option value="'. $brand["ID_brand"] .'">'. $brand["braName"] .'</option>';
                        }
                    }   
                    // si la variable n'existe pas, alors crée le dropdown sans chercher a mettre une valeur en selected
                    else
                    {
                        echo '<option value="'. $brand["ID_brand"] .'">'. $brand["braName"] .'</option>';
                    }
                }
            ?>
        </select>
        <?php
                if(isset($_SESSION["ErrorMessageBrand"]))
                {
                    echo "<small class='text-danger'>" . $_SESSION['ErrorMessageBrand'] . "</small>" ;
                }
            ?>
        </div>
    </div>
    <!-- Liste des tailles de vélo -->
    <div class='form-group row mb-3 align-items-start'>
        <div class='col-sm-4 text-start'>
            <label for="FK_size" class='form-label fw-bold'>Taille du vélo*</label><br>
            <small class='text-muted'>exemple : XL</small>
        </div>
        <div class='col-sm-8'>
            <select class='form-control' name="FK_size" id="FK_size">
                <option value=""></option>
                <?php 
                    // parcours le tableau des tailles
                    foreach($sizes as $size )
                    {
                        // contrôle si la variable existe
                        if(isset($_SESSION["FK_size"]))
                        {
                            // permet de sauver le choix de l'utilisateurs
                            if($_SESSION["FK_size"] == $size["ID_size"])
                            {
                                echo '<option value="'. $size["ID_size"] .'" selected>'. $size["sizSize"] .'</option>';
                            }
                            else
                            {
                                echo '<option value="'. $size["ID_size"] .'">'. $size["sizSize"] .'</option>';
                            }
                        }  
                        // si la variable n'existe pas, alors crée le dropdown sans chercher a mettre une valeur en selected
                        else
                        {
                            echo '<option value="'. $size["ID_size"] .'">'. $size["sizSize"] .'</option>';
                        }
                    }
                ?>
            </select>
            <?php
                if(isset($_SESSION["ErrorMessageSize"]))
                {
                    echo "<small class='text-danger'>" . $_SESSION['ErrorMessageSize'] . "</small>" ;
                }
            ?>
        </div>
    </div>
    <!-- Liste des communes pouvant acceuilir le vélo -->
    <div class='form-group row mb-3 align-items-start'>
        <div class='col-sm-4 text-start'>
            <label for="FK_commune" class='form-label fw-bold'>Commune*</label><br>
            <small class='text-muted'>exemple : Commune de Payerne</small>
        </div>
        <div class='col-sm-8'>
        <select class='form-control' name="FK_commune" id="FK_commune">
            <option value=""></option>
            <?php 
                // parcours le tableau des communes
                foreach ($communes as $commune) 
                {
                    // si les communes ont une inscription  valide, alors il crée un choix de plus pour la liste déroulante.
                    if ($commune["comInscription"] == 1) 
                    {
                        // contrôle si la variable existe
                        if (isset($_SESSION["FK_commune"])) 
                        {
                            // permet de sauver le choix de l'utilisateur
                            if ($_SESSION["FK_commune"] == $commune["ID_commune"]) 
                            {
                                echo '<option value="' . $commune["ID_commune"] . '" selected>' . $commune["comName"] . '</option>';
                            } else 
                            {
                                echo '<option value="' . $commune["ID_commune"] . '">' . $commune["comName"] . '</option>';
                            }
                        } 
                        else 
                        {
                            // si la variable n'existe pas, alors crée le dropdown sans selected
                            echo '<option value="' . $commune["ID_commune"] . '">' . $commune["comName"] . '</option>';
                        }
                    }
                }
            ?>
        </select>
        <?php
                if(isset($_SESSION["ErrorMessageCommune"]))
                {
                    echo "<small class='text-danger'>" . $_SESSION['ErrorMessageCommune'] . "</small>" ;
                }
            ?>
        </div>
    </div>
    <div class='form-group row mb-3 align-items-start'>
        <div class='col-sm-4 text-start'>
            <!-- Champ permettant de sélectionner plusieurs fichiers images -->
            <label for="bidPathFile" class='form-label fw-bold'>Choisir jusqu'à 3 images et au minimum 1 image*</label><br>
            <small class='text-muted'>exemple : Image.jpg</small>
        </div>
        <div class='col-sm-8'>
            <!-- Le nom "images[]" indique un tableau d'images, et "multiple" permet d'en sélectionner plusieurs -->
            <input class='form-control' id="bidPathFile" name="images[]" multiple  type="file">
            <?php
                if(isset($_SESSION["ErrorMessageImage"]))
                {
                    echo "<small class='text-danger'>" . $_SESSION["ErrorMessageImage"] . "</small>" ;
                }
            ?>
        </div>
    </div>
    <?php 
        if (isset($_SESSION["MessageAdd"])) 
        {
            echo $_SESSION["MessageAdd"];
        }
    ?>
    <div class="text-center">
        <button type="submit" class="btn">Soumettre le formulaire</button>
    </div>
</form>
<?php 
    // supprime les messages pour les prochaine ouverture de formulaire
    unset($_SESSION["ErrorMessageColor"], $_SESSION["ErrorMessagePlace"], $_SESSION["ErrorMessageFrameNumber"], $_SESSION["ErrorMessageBrand"], $_SESSION["ErrorMessageDate"], $_SESSION["ErrorMessageCommune"], $_SESSION["MessageAdd"], $_SESSION["ErrorMessageImage"]); // etc.
    ?>
    </body>
</html>
