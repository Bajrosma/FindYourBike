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
            <h1>Restitution d'un vélo trouvé</h1>
            <form action="../../Controller/ChecksFormulaires/FormulaireRenderCheck.php?ID=<?php echo $_GET["ID"]?>" method="post" enctype="multipart/form-data">
                <?php 
                    $Champs = [
                        'perFirstName' => 'prénom du propriètaire',
                        'perLastName' => 'Nom du propriètaire',
                        'perAdress' => 'Adress du propriètaire',
                        'perNPA' => 'NPA',
                        'perCity' => 'Ville',
                        'perEmail' => 'Adresse email du propriètaire',
                        'perTel' => 'Téléphone du propriètaire'

                    ];
                    // parcours le tableau des champs de type text
                    foreach ($Champs as $Champ => $label) 
                    {
                        // vérifie si une entrée a été sauvée dans la session, si oui le champ reprend le même texte et si non, il laisse vide
                        $value = isset($_SESSION[$Champ]) ? htmlspecialchars($_SESSION[$Champ]) : '';
                        echo "
                        <div class='form-group row mb-3'>
                            <label for='$Champ' class='col-sm-4 col-form-label'>$label</label>
                            <div class='col-sm-8'>
                                <input type='text' class='form-control' name='$Champ' id='$Champ' value='$value'>
                            </div>
                        </div>";
                    }
                ?>
                <!-- Date de rendu -->
                <div class='form-group row mb-3'>
                    <label for="bikRestitutionDate" class='col-sm-4 col-form-label'>Date de rendu</label>
                    <div class='col-sm-8'>
                        <input class='form-control' type="date" id="bikRestitutionDate" name="bikRestitutionDate">
                    </div>
                </div>
                <div  class='form-group row mb-3'>
                    <!-- Champ permettant de sélectionner plusieurs fichiers images -->
                    <label for="proPathFile" class='col-sm-4 col-form-label'>Choisir jusqu'à 3 images :</label>
                    <div class='col-sm-8'>
                        <!-- Le nom "images[]" indique un tableau d'images, et "multiple" permet d'en sélectionner plusieurs -->
                        <input class='form-control' id="proPathFile" name="images[]" multiple required type="file">
                    </div>
                </div>
                <?php 
                    echo  $_SESSION["MessageAdd"];
                ?>
                <div class="text-center">
                    <button type="submit" class="btn">Soumettre le formulaire</button>
                </div>
            </form>
        </div>
    </body>
</html>