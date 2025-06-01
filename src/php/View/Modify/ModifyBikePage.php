<?php
session_start();
/**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 27.05.2025
 * Description : page du formulaire d'ajout d'un vélo
 */
require_once('../../Model/config.php');
require_once('../../Model/database.php');

$db = Database::getInstance();

// Vérification de l'ID du vélo
if (!isset($_GET["ID"]) || !is_numeric($_GET["ID"])) {
    $_SESSION["ErrorMessage"] = "ID de vélo invalide.";
    header("Location: ../../View/Formulaires/ListeVelos.php");
    exit;
}

$id = intval($_GET["ID"]);
$bike = $db->GetOneBike($id);

if (!$bike) {
    $_SESSION["ErrorMessage"] = "Vélo introuvable.";
    header("Location: ../../View/Formulaires/ListeVelos.php");
    exit;
}

// Récupération des données du vélo
$bikeData = $bike[0];

$sizes = $db->GetAllSizes();
$brands = $db->GetAllBrands();
$colors = $db->GetAllColors();
$communes = $db->GetAllCommunesDropDown();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>FindYourBike</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
</head>
<body>
<div class="container">
    <button onclick="history.back()" class="btn btn-secondary my-3">← Retour</button>
    <h1>Modification d'un vélo trouvé</h1>

    <form action="../../Controller/ChecksFormulaires/FormulaireBikeCheck.php?ID=<?= $id ?>" method="post">
        <?php 
        $fields = [
            'bikPlace' => 'Lieu de découverte (adresse complète)',
            'bikFrameNumber' => 'Numéro de série (cadre)'
        ];
        foreach ($fields as $name => $label) {
            $value = $_SESSION[$name] ?? $bikeData[$name] ?? '';
            $errorKey = "ErrorMessage" . ucfirst(str_replace("bik", "", $name));
            ?>
            <div class="form-group mb-3">
                <label for="<?= $name ?>"><?= $label ?></label>
                <input class="form-control" type="text" name="<?= $name ?>" id="<?= $name ?>" value="<?= htmlspecialchars($value) ?>">
                <?php if (!empty($_SESSION[$errorKey])): ?>
                    <p class="text-danger"><?= $_SESSION[$errorKey] ?></p>
                <?php endif; ?>
            </div>
            <?php
        }
        ?>

        <!-- Date -->
        <div class="form-group mb-3">
            <label for="bikDate">Date de découverte</label>
            <input class="form-control" type="date" name="bikDate" id="bikDate" value="<?= htmlspecialchars($_SESSION['bikDate'] ?? $bikeData['bikDate'] ?? '') ?>">
            <?php if (!empty($_SESSION["ErrorMessageDate"])): ?>
                <p class="text-danger"><?= $_SESSION["ErrorMessageDate"] ?></p>
            <?php endif; ?>
        </div>

        <!-- Dropdowns -->
        <?php
        $dropdowns = [
            'FK_color' => ['label' => 'Couleur du vélo', 'data' => $colors, 'nameKey' => 'colName', 'idKey' => 'ID_color'],
            'FK_brand' => ['label' => 'Marque du vélo', 'data' => $brands, 'nameKey' => 'braName', 'idKey' => 'ID_brand'],
            'FK_size' => ['label' => 'Taille du vélo', 'data' => $sizes, 'nameKey' => 'sizSize', 'idKey' => 'ID_size'],
            'FK_commune' => ['label' => 'Commune', 'data' => $communes, 'nameKey' => 'comName', 'idKey' => 'ID_commune', 'filter' => true]
        ];

        foreach ($dropdowns as $name => $info):
            $selectedValue = $_SESSION[$name] ?? $bikeData[$name] ?? '';
            ?>
            <div class="form-group mb-3">
                <label for="<?= $name ?>"><?= $info['label'] ?></label>
                <select class="form-control" name="<?= $name ?>" id="<?= $name ?>">
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($info['data'] as $item): 
                        if (!empty($info['filter']) && $item['comInscription'] != 1) continue;
                        $value = $item[$info['idKey']];
                        $label = $item[$info['nameKey']];
                        $selected = ($selectedValue == $value) ? 'selected' : '';
                        ?>
                        <option value="<?= $value ?>" <?= $selected ?>><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php 
                $errorKey = "ErrorMessage" . ucfirst(str_replace("FK_", "", $name));
                if (!empty($_SESSION[$errorKey])) {
                    echo "<p class='text-danger'>{$_SESSION[$errorKey]}</p>";
                }
                ?>
            </div>
            <?php
        endforeach;
        ?>

        <!-- Message de retour -->
        <?php if (!empty($_SESSION["MessageAdd"])): ?>
            <p class="text-success"><?= $_SESSION["MessageAdd"] ?></p>
        <?php endif; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Soumettre le formulaire</button>
        </div>
    </form>
</div>
</body>
</html>
