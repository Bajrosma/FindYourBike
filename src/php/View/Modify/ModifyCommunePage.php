<?php
// Démarrer une session
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 27.05.2025
 * Description : Page du formulaire d'inscription
 */

// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');

// Vérification de la présence et de la validité de l'ID de la commune
if (!isset($_GET["ID"]) || !is_numeric($_GET["ID"])) {
    die("ID de commune invalide.");
}

// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();

// Récupération des informations de la commune
$commune = $db->GetOneCommune($_GET["ID"]);

// Stockage des informations de la commune dans la session
foreach ($commune as $key => $value) {
    $_SESSION['Commune'][$key] = $value;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindYourBike</title>
    <!-- Liens vers les fichiers CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
</head>
<body>

<div class="container mt-4">
    <button onclick="history.back()" class="btn btn-secondary mb-3">← Retour</button>
    <h1>Modification d'une commune</h1>

    <form action="../../Controller/ChecksFormulaires/ModifyCommuneCheck.php" method="post">
        <?php 
        // Définition des champs du formulaire
        $fields = [
            'comName'   => 'Commune',
            'comAdress' => 'Adresse',
            'comCity'   => 'Localité',
            'comNPA'    => 'NPA',
            'comEmail'  => 'Email',D
            'comTel'    => 'Téléphone'
        ];

        // Champ caché pour l'ID de la commune
        echo '<input type="hidden" id="ID_commune" name="ID_commune" value="' . htmlspecialchars($_GET["ID"]) .'">';

        // Affichage des champs du formulaire
        foreach ($fields as $name => $label) {
            // Récupération de la valeur du champ
            $value = $_SESSION[$name] ?? $_SESSION['Commune'][$name] ?? '';
            // Récupération du message d'erreur associé
            $errorKey = "ErrorMessage" . ucfirst(str_replace("com", "", $name));
            $errorMsg = $_SESSION[$errorKey] ?? '';
            // affichage des champs avec les valeurs du vélo
            echo "<div class='mb-3'>";
            echo "<label for=\"$name\" class=\"form-label\">$label</label>";
            echo "<input type=\"text\" class=\"form-control\" name=\"$name\" id=\"$name\" value=\"" . htmlspecialchars($value) . "\">";
            // affichage des messages d'erreurs.
            if (!empty($errorMsg)) {
                echo "<div class=\"text-danger\">$errorMsg</div>";
            }
            echo "</div>";
        }

        // Affichage du message de succès
        if (!empty($_SESSION["MessageAdd"])) {
            echo '<div class="alert alert-success">' . $_SESSION["MessageAdd"] . '</div>';
        }
        ?>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Soumettre le formulaire</button>
        </div>
    </form>
</div>

</body>
</html>
