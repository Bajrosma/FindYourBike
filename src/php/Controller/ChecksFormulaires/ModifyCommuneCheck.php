<?php
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 27.05.2025
 * Description : Vérification et enregistrement du formulaire de modification de commune
 */

// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');

// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();

// Vérification de la présence de l'ID de la commune
if (!isset($_POST["ID_commune"]) || !is_numeric($_POST["ID_commune"])) {
    $_SESSION["ErrorMessage"] = "ID de la commune invalide ou manquant.";
    header("Location: ../../View/Modify/ModifyCommunePage.php");
    exit;
}

// Configuration des champs à valider avec leurs expressions régulières et messages d'erreur
$fields = [
    'comName' => [
        'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
        'label' => 'nom de la commune',
        'error' => 'Veuillez entrer une commune valide (lettres, espaces, tirets ou apostrophes uniquement) !'
    ],
    'comAdress' => [
        'regex' => '/^[A-Za-zÀ-ÿ0-9\s\-\,\.]{3,}\s\d{1,}$/u',
        'label' => 'adresse de la commune',
        'error' => 'Veuillez entrer une adresse complète valide (minimum 3 caractères et un numéro de rue) !'
    ],
    'comCity' => [
        'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
        'label' => 'localité',
        'error' => 'Veuillez entrer une localité valide (lettres uniquement) !'
    ],
    'comNPA' => [
        'regex' => '/^\d{4}$/',
        'label' => 'NPA',
        'error' => 'Veuillez entrer un NPA suisse à 4 chiffres (ex: 1000) !'
    ],
    'comEmail' => [
        'label' => 'email',
        'error' => 'Veuillez entrer une adresse email valide (ex: exemple@mail.ch) !'
    ],
    'comTel' => [
        'regex' => '/^\+41\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}$/',
        'label' => 'numéro de téléphone',
        'error' => 'Veuillez entrer un numéro de téléphone suisse valide (ex: +41 79 123 45 67) !'
    ]
];

// Variable de validation globale
$isValid = true;

// Boucle pour valider chaque champ
foreach ($fields as $field => $config) {
    $value = trim($_POST[$field] ?? '');
    $_SESSION[$field] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $shortName = ucfirst(str_replace("com", "", $field));

    // Vérification si le champ est vide
    if (empty($value)) {
        $_SESSION["ErrorMessage$shortName"] = "Veuillez ne pas laisser le champ {$config['label']} vide !";
        $isValid = false;
    } else {
        // Validation spécifique pour l'email
        if ($field === 'comEmail') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["ErrorMessage$shortName"] = $config['error'];
                $isValid = false;
            } else {
                unset($_SESSION["ErrorMessage$shortName"]);
            }
        } else {
            // Validation avec expression régulière
            if (!preg_match($config['regex'], $value)) {
                $_SESSION["ErrorMessage$shortName"] = $config['error'];
                $isValid = false;
            } else {
                unset($_SESSION["ErrorMessage$shortName"]);
            }
        }
    }
}

// Si tous les champs sont valides
if ($isValid) {
    // Mise à jour de la commune dans la base de données
    $db->UpdateCommune(
        $_POST["comName"],
        $_POST["comAdress"],
        $_POST["comCity"],
        $_POST["comNPA"],
        $_POST["comEmail"],
        $_POST["comTel"],
        $_POST["ID_commune"]
    );

    // Message de confirmation
    $_SESSION["MessageAdd"] = "Commune modifiée avec succès !";

    // Suppression des données de la session après la mise à jour réussie
    foreach ($fields as $field => $config) {
        unset($_SESSION[$field]);
    }
} else {
    // Si des erreurs ont été détectées, le message d'ajout reste vide
    $_SESSION["MessageAdd"] = "";
}

// Redirection vers la page
