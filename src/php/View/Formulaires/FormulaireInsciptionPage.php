<?php
//commence le système de session
session_start();
/**
 * Auteur : Bajro Osmanovic
 * Date : 09.95.2025 → Modif : 12.05.2025
 * Description : page du formulaire  d'inscription
 */

// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();

$displayErrors = true;
?>

<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--liens avec le css et css de bootstrap-->
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
    <h1>Inscription d'une commune</h1>
    <small> * : indique les champs obligatoire</small>
    <form action="../../Controller/ChecksFormulaires/FormulaireInsciptionCheck.php" method="post">
    <?php 
        $Champs = [
            'comName' => 'Commune*',
            'comAdress' => 'Adresse*',
            'comCity' => 'Localité*',
            'comNPA' => 'NPA*',
            'comEmail' => 'Email*',
            'comTel' => 'Tel*',
            'comLastName' => 'Nom du responsable*',
            'comFisrtName' => 'Prénom du responsable*',
            'comFonction' => 'Fonction*',
        ];

        $exemple = [
            'comName' => "exemple : Commune d'Aigle",
            'comAdress' => 'exemple : Chemin des poissons 16',
            'comCity' => 'exemple : Aigle',
            'comNPA' => 'exemple : 1860',
            'comEmail' => 'exemple : toto@exemple.ch',
            'comTel' => 'exemple : +41 76 123 45 67',
            'comLastName' => 'exemple : Dupont',
            'comFisrtName' => 'exemple : Bernard',
            'comFonction' => 'exemple : Secretaire communal',
        ];

        foreach ($Champs as $Champ => $label) {
            $errorMessage = $_SESSION["ErrorMessage" . ucfirst(str_replace("com", "", $Champ))] ?? '';
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
        // montrer un message si l'ajout a fonctionner
        if(isset($_SESSION["MessageAdd"]))
        {
            echo  "<p style='color: #2396a2;'>" . $_SESSION["MessageAdd"] . "</p>" ;  
        }  
    ?>
    <div class="text-center">
        <button type="submit" class="btn">Soumettre le formulaire</button>
    </div>
</form>

</body>
</html>