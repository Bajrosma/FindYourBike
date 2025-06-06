<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 28.05.2025
 * Description : page du formulaire de rendu d'un vélo
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
// contrôledes valeurs
$id = isset($_GET["ID"]) ? htmlspecialchars($_GET["ID"]) : '';
$dateValue = htmlspecialchars($_SESSION['bikRestitutionDate'] ?? '');
?>

<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--liens avec le CSS personnalisé et css de bootstrap-->
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
            <a href="../Listes/ListeBikePage.php" class="btn-back">← Retour</a>
            <h1>Remise d'un vélo trouvé</h1>
            <small> * : indique les champs obligatoires</small>
            <small>Informations du propriétaire</small>
            <form action="../../Controller/ChecksFormulaires/FormulaireRenderCheck.php?ID=<?php echo $id?>" method="post" enctype="multipart/form-data">
                <?php 
                    $Champs = [
                        'perLastName' => 'Nom*',
                        'perFirstName' => 'prénom*',
                        'perAdress' => 'Adresse*',
                        'perNPA' => 'NPA*',
                        'perCity' => 'Ville*',
                        'perEmail' => 'Adresse email*',
                        'perTel' => 'Téléphone*'
                    ];
            
                    $exemple = [
                        'perFirstName' => 'exemple : Bernard',
                        'perLastName' => 'exemple : Dupont',
                        'perAdress' => 'exemple : Chemin des poissons 16',
                        'perNPA' => 'exemple : 1860',
                        'perCity' => 'exemple : Aigle',
                        'perEmail' => 'exemple : toto@exemple.ch',
                        'perTel' => 'exemple : +41 76 123 45 67'
                    ];
            
                    foreach ($Champs as $Champ => $label) {
                        $errorMessage = $_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $Champ))] ?? '';
                        $value = htmlspecialchars($_SESSION[$Champ] ?? '');
                        $example = $exemple[$Champ] ?? '';
                        
                        echo "
                        <div class='form-group row mb-3 align-items-start'>
                            <div class='col-sm-4 text-start'>
                                <label for='$Champ' class='form-label fw-bold'>$label</label><br>
                                <small class='text-muted'>$example</small>
                            </div>
                            <div class='col-sm-8'>
                                <input type='text' class='form-control' name='$Champ' id='$Champ' value='$value'>
                                <small class='text-danger'>$errorMessage</small>
                            </div>
                        </div>";
                    }
                ?>
                <small>Informations du vélo</small>
                <!-- Date de rendu -->
                <div class='form-group row mb-3 align-items-start'>
                    <div class='col-sm-4 text-start'>
                        <label for="bikRestitutionDate"  class='form-label fw-bold'>Date de rendu</label><br>
                        <small class='text-muted'>22.01.2023</small>
                    </div>
                    <div class='col-sm-8'>
                        <input class='form-control' type="date" id="bikRestitutionDate" name="bikRestitutionDate" value="<?php echo $dateValue ?>">
                        <?php
                            // si le message d'erreur pour image est présent alors affiché
                            if(isset($_SESSION["ErrorMessageDate"]))
                            {
                                echo  "<small class='text-danger'>" .$_SESSION["ErrorMessageDate"] . "</small>";
                            }
                            
                        ?>
                    </div>
                </div>
                <div class='form-group row mb-3 align-items-start'>
                    <div class='col-sm-4 text-start'>
                        <!-- Champ permettant de sélectionner plusieurs fichiers images -->
                        <label for="proPathFile" class='form-label fw-bold'>Choisir jusqu'à 3 preuves :</label>
                        <small class='text-muted'>Image.jpg, ticketCaisse.pdf</small>
                    </div>
                    <div class='col-sm-8'>
                        <!-- Le nom "images[]" indique un tableau d'images, et "multiple" permet d'en sélectionner plusieurs -->
                        <input class='form-control' id="proPathFile" name="images[]" multiple  type="file">
                        <?php
                            // si le message d'erreur pour image est présent alors affiché
                            if(isset($_SESSION["ErrorMessageImage"]))
                            {
                                echo  "<small class='text-danger'>" .$_SESSION["ErrorMessageImage"] . "</small>";
                            }
                        ?>
                    </div>
                </div>
                <?php
                    // sinon contrôler le message d'ajout 
                    if (isset($_SESSION["MessageAdd"])) 
                    {
                        echo "<div class='alert alert-success'>" . $_SESSION["MessageAdd"] . "</div>";
                    }
                ?>
                <div class="text-center">
                    <button type="submit" class="btn">Soumettre le formulaire</button>
                </div>
            </form>
            <?php
                foreach ($Champs as $Champ => $_) {
                    unset($_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $Champ))]);
                    unset($_SESSION[$Champ]);
                }
                unset($_SESSION["MessageAdd"]);
                // unset les varibles de dates
                unset($_SESSION['bikRestitutionDate'], $_SESSION["ErrorMessageDate"]);
                // unset les varibles de fichiers
                unset($_SESSION['proPathFile'], $_SESSION["ErrorMessageImage"]);
            ?>
        </div>
    </body>
</html>
