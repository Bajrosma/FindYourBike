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
    <form action="../../Controller/ChecksFormulaires/FormulaireInsciptionCheck.php" method="post">
    <?php 
        $Champs = [
            'comName' => 'Commune',
            'comAdress' => 'Adresse',
            'comCity' => 'Localité',
            'comNPA' => 'NPA',
            'comEmail' => 'Email',
            'comTel' => 'Tel',
            'comLastName' => 'Nom du responsable',
            'comFisrtName' => 'Prénom du responsable',
            'comFonction' => 'Fonction',
        ];

        foreach ($Champs as $Champ => $label) {
            $errorMessage = isset($_SESSION["ErrorMessage" . ucfirst($Champ)]) ? $_SESSION["ErrorMessage" . ucfirst($field)] : '';
            $value = isset($_SESSION[$Champ]) ? htmlspecialchars($_SESSION[$Champ]) : '';
            echo "
            <div class='form-group row mb-3'>
                <label for='$Champ' class='col-sm-4 col-form-label'>$label</label>
                <div class='col-sm-8'>
                    <input type='text' class='form-control' name='$Champ' id='$Champ' value='$value'>
                    <small class='text-danger'>$errorMessage</small>
                </div>
            </div>";
        }
        // montrer un message si l'ajout a fonctionner
        echo  $_SESSION["MessageAdd"];    
    ?>
    <div class="text-center">
        <button type="submit" class="btn">Soumettre le formulaire</button>
    </div>
</form>

</body>
</html>