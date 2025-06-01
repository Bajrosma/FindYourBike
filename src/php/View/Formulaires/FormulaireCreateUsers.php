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
        <a href="../Accueil/AccueilAdmin.php" class="btn-back">← Retour</a>
        <h1>Création d'un compte</h1>
        <form action="../../Controller/ChecksFormulaires/FormulaireCreateUserCheck.php" method="post">
            <div class='form-group row mb-3'>
                <label foruseName' class='col-sm-4 col-form-label'>Nom de l'utilisateurs</label>
                <div class='col-sm-8'>
                    <input type='Text' class='form-control' name='useName' id='useName'>
                </div>
            </div>
            <div class='form-group row mb-3'>
                <label for='usePassword' class='col-sm-4 col-form-label'>Mot de passe</label>
                <div class='col-sm-8'>
                    <input type='password' class='form-control' name='usePassword' id='usePassword'>
                </div>
            </div>
            <div class='form-group row mb-3'>
                <label for='usePasswordConfirm' class='col-sm-4 col-form-label'>Confirmer mot de passe</label>
                <div class='col-sm-8'>
                    <input type='password' class='form-control' name='usePasswordConfirm' id='usePasswordConfirm'>
                </div>
            </div>
            <!-- Liste des rôles que le compte peut se voir attribuer -->
            <div class='form-group row mb-3'>
                <label for="usePrivilage" class='col-sm-4 col-form-label'>Rôle du compte</label>
                <div class='col-sm-8'>
                <select class='form-control' name="usePrivilage" id="usePrivilage">
                    <option value="">Veuillez sélectionné le rôle</option>
                    <option value="0">Consultation</option>
                    <option value="1">Administration</option>
                </select>
                </div>
            </div>
            <?php
                if(isset( $_SESSION["ErrorMessagePasswordConfirm"]))
                {
                    echo  $_SESSION["ErrorMessagePasswordConfirm"];
                }
            ?>
            <div class="text-center">
                <button type="submit" class="btn">Soumettre le formulaire</button>
            </div>
        </form>
    </div>
    <?php
        /* suppression des valeurs et des messages d'erreurs
        foreach ($Champs as $Champ => $_) {
            unset($_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $Champ))]);
            unset($_SESSION[$Champ]);
        }
        unset($_SESSION["MessageAdd"]);*/
        ?>
</body>
</html>
