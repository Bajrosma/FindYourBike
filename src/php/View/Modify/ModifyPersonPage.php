<?php
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 16.05.2025 → Modif : 01.06.2025
 * Description : Page de modification d'une personne
 */

// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');

// Vérification de la présence de l'ID dans l'URL
if (!isset($_GET["ID"]) || empty($_GET["ID"])) {
    die("ID de la personne non spécifié.");
}

$db = Database::getInstance();

// Récupération des informations de la personne
$person = $db->GetOnePerson($_GET["ID"]);
if (!$person) {
    die("Personne non trouvée.");
}

// Stockage des informations dans la session
foreach ($person as $key => $value) {
    $_SESSION['Person'][$key] = $value;
}

// Récupération des communes pour la liste déroulante
$communes = $db->GetAllCommunesDropDown();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Modification d'une personne - FindYourBike</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
</head>
<body>
<div class="container mt-4">
    <button onclick="history.back()" class="btn btn-secondary mb-3">← Retour</button>
    <h1>Modification d'une personne</h1>
    <form action="../../Controller/ChecksFormulaires/ModifyPersonCheck.php" method="post">
        <!-- Champ caché pour l'ID de la personne -->
        <input type="hidden" id="ID_personne" name="ID_personne" value="<?= htmlspecialchars($_GET["ID"]) ?>">

        <?php 
        $fields = [
            'perLastName' => 'Nom du responsable',
            'perFirstName' => 'Prénom du responsable',
            'perAdress' => 'Adresse',
            'perCity' => 'Localité',
            'perNPA' => 'NPA',
            'perEmail' => 'Email',
            'perTel' => 'Téléphone',
            'perRole' => 'Fonction'
        ];

        foreach ($fields as $name => $label) {
            $value = $_SESSION[$name] ?? $_SESSION['Person'][$name] ?? '';
            $errorKey = "ErrorMessage" . ucfirst(str_replace("per", "", $name));
            ?>
            <div class="form-group mb-3">
                <label for="<?= $name ?>"><?= $label ?></label>
                <input class="form-control" type="text" name="<?= $name ?>" id="<?= $name ?>" value="<?= htmlspecialchars($value) ?>">
                <?php if (!empty($_SESSION[$errorKey])): ?>
                    <p class="text-danger"><?= htmlspecialchars($_SESSION[$errorKey]) ?></p>
                <?php endif; ?>
            </div>
            <?php
        }
        ?>

        <!-- Liste déroulante des communes -->
        <div class="form-group mb-3">
            <label for="FK_commune">Commune</label>
            <select class="form-control" name="FK_commune" id="FK_commune">
                <option value="">-- Sélectionner --</option>
                <?php
                $selectedCommune = $_SESSION['FK_commune'] ?? $_SESSION['Person']['FK_commune'] ?? '';
                foreach ($communes as $commune) {
                    if ($commune['comInscription'] != 1) continue;
                    $value = $commune['ID_commune'];
                    $label = $commune['comName'];
                    $selected = ($selectedCommune == $value) ? 'selected' : '';
                    echo "<option value=\"$value\" $selected>$label</option>";
                }
                ?>
            </select>
            <?php 
            $errorKey = "ErrorMessageCommune";
            if (!empty($_SESSION[$errorKey])) {
                echo "<p class='text-danger'>" . htmlspecialchars($_SESSION[$errorKey]) . "</p>";
            }
            ?>
        </div>

        <!-- Message de confirmation -->
        <?php if (!empty($_SESSION["MessageAdd"])): ?>
            <p class="text-success"><?= htmlspecialchars($_SESSION["MessageAdd"]) ?></p>
        <?php endif; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Soumettre le formulaire</button>
        </div>
    </form>
</div>
<?php 
    unset($_SESSION["MessageAdd"]);
    foreach ($fields as $name => $label) {
        $errorKey = "ErrorMessage" . ucfirst(str_replace("per", "", $name));
        unset($_SESSION[$errorKey]);
    }
    unset($_SESSION["ErrorMessageCommune"]);
?>
</body>
</html>
