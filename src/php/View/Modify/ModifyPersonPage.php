<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 16.95.2025 → Modif : 
 * Description : page de modification d'une personne
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
//récupère les informations de la personne
$Person=$db->GetOnePerson($_GET["ID"]);
// commande pour réaliser les listes déroulantes de la page
$Communes=$db->GetAllCommunesDropDown();


foreach ($Person as $key => $value) {
    $_SESSION['Person'][$key] = $value;
}

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
    <h1>Page de modification d'une personne</h1>
    <form action="../../Controller/ChecksFormulaires/ModifyPersonCheck.php" method="post">
    <?php 
        $fields = [
            'perLastName' => 'Nom du responsable',
            'perFirstName' => 'Prénom du responsable',
            'perAdress' => 'Adresse',
            'perCity' => 'Localité',
            'perNPA' => 'NPA',
            'perEmail' => 'Email',
            'perTel' => 'Tel',
            'perRole' => 'Fonction'
        ];
        
        echo '<input type="hidden" id="ID_personne" name="ID_personne" value="' . $_GET["ID"] .'">';

        foreach ($fields as $name => $label) {
            $sessionValue = $_SESSION[$name] ?? $_SESSION['Person'][0][$name] ?? '';
            $errorKey = "ErrorMessage" . ucfirst(str_replace("per", "", $name));
            echo "<div class='form-group row mb-3'>";
            echo "<label class='col-sm-4 col-form-label' for=\"$name\">$label</label><br>";
            echo "<div class='col-sm-8'>";
            echo "<input class='form-control' type=\"text\" name=\"$name\" id=\"$name\" value=\"" . htmlspecialchars($sessionValue) . "\" /><br>";
            if (!empty($_SESSION[$errorKey])) {
                echo '<p class="text-danger">' . $_SESSION[$errorKey] . '</p>';
            }
            echo '</div></div>';
        }
        foreach($Communes as $Commune)
        {

        }
        echo $_SESSION["MessageAdd"];
    ?>
    <div class="text-center">
        
        <button type="submit" class="btn">Soumettre le formulaire</button>
    </div>
</form>

</body>
</html>