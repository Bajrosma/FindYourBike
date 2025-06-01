<?php
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 12.05.2025 → Modif : 01.06.2025
 * Description : Vérification et enregistrement du formulaire de modification d'un vélo trouvé
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

// Configuration des champs à valider
$fields = [
    'bikPlace' => [
        'regex' => '/^[A-Za-zÀ-ÿ0-9\s\-\,\.]{5,}$/u',
        'label' => 'lieu de découverte',
        'error' => 'Veuillez entrer une adresse complète valide (minimum 5 caractères, lettres/chiffres autorisés) !'
    ],
    'bikFrameNumber' => [
        'regex' => '/^[A-Za-z0-9\-]{3,}$/',
        'label' => 'numéro de cadre',
        'error' => 'Veuillez entrer un numéro de cadre valide (minimum 3 caractères, lettres/chiffres autorisés) !'
    ]
];

// Variable de validation globale
$isValid = true;

// Boucle pour valider chaque champ
foreach ($fields as $field => $config) {
    $value = trim($_POST[$field] ?? '');
    $_SESSION[$field] = $value;
    $shortName = ucfirst(str_replace("bik", "", str_replace("FK_", "", $field)));

    // Vérification si le champ est vide
    if (empty($value)) {
        $_SESSION["ErrorMessage$shortName"] = "Veuillez ne pas laisser le champ {$config['label']} vide !";
        $isValid = false;
    }
    // Vérification de la correspondance avec l'expression régulière
    elseif (!preg_match($config['regex'], $value)) {
        $_SESSION["ErrorMessage$shortName"] = $config['error'];
        $isValid = false;
    } else {
        unset($_SESSION["ErrorMessage$shortName"]);
    }
}

// Vérification et sauvegarde des champs obligatoires liés au vélo (dont la date et les clés étrangères)
    $requiredFields = ['bikDate', 'FK_color', 'FK_brand', 'FK_size', 'FK_commune'];
    // Parcourt chaque champ requis pour effectuer la validation
    foreach ($requiredFields as $field) {
        // Vérifie si le champ est vide
        if (empty($_POST[$field])) {
            // Marque le formulaire comme invalide
            $isValid = false;
            // Enregistre un message d'erreur spécifique dans la session (ex: "Ce champ est requis.")
            // Le nom de la clé est généré dynamiquement : FK_color devient "ErrorMessageColor"
            $_SESSION["ErrorMessage" . ucfirst(str_replace("FK_", "", $field))] = "Ce champ est requis.";
            // Stocke une valeur vide dans la session pour le champ concerné
            $_SESSION[$field] = "";
        } else {
            // Si le champ est rempli, on l'enregistre en session pour le réafficher dans le formulaire
            $_SESSION[$field] = $_POST[$field];
            // Réinitialise le message d'erreur pour ce champ (au cas où il y en avait un auparavant)
            $_SESSION["ErrorMessage" . ucfirst(str_replace("FK_", "", $field))] = "";
        }
    }

// Si tous les champs sont valides
if ($isValid) {
    // Appelle la méthode pour mettre à jour le vélo dans la base de données
    $db->UpdateBike(
        $_POST["bikPlace"],
        $_POST["bikFrameNumber"],
        $_POST["bikDate"],
        $_POST["FK_color"],
        $_POST["FK_brand"],
        $_POST["FK_size"],
        $_POST["FK_commune"],
        $id
    );

    // Message de confirmation de modification
    $_SESSION["MessageAdd"] = "Vélo modifié avec succès !";

    // Vider les données de la session après la mise à jour réussie
    foreach ($fields as $field => $config) {
        unset($_SESSION[$field]);
    }
} else {
    $_SESSION["MessageAdd"] = "";
}

// Redirection vers la page de modification du vélo avec le message approprié
header("Location: ../../View/Formulaires/ModifyBikePage.php?ID=" . $id);
exit;
?>
