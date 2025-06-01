<?php
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 27.05.2025
 * Description : Vérification et enregistrement du formulaire de modification d'une personne
 */

// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');

// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();

// Vérification si l'utilisateur a soumis le formulaire (paramètre "Update" non défini dans l'URL)
if (!isset($_GET["Update"])) {
    // Configuration des champs à valider avec leurs expressions régulières et messages d'erreur
    $fields = [
        'perAdress' => [
            'regex' => '/^[A-Za-zÀ-ÿ0-9\s\-\,\.]{5,}$/u',
            'error' => 'Veuillez entrer une adresse complète valide (minimum 5 caractères, lettres/chiffres autorisés) !'
        ],
        'perCity' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer une localité valide (lettres uniquement) !'
        ],
        'perNPA' => [
            'regex' => '/^\d{4}$/',
            'error' => 'Veuillez entrer un NPA suisse à 4 chiffres (ex: 1000) !'
        ],
        'perTel' => [
            'regex' => '/^(\+41\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}|0041\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}|0\d{9})$/',
            'error' => 'Veuillez entrer un numéro de téléphone suisse valide (ex: +41 79 123 45 67) !'
        ],
        'perFirstName' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer un prénom valide (lettres uniquement) !'
        ],
        'perLastName' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer un nom valide (lettres uniquement) !'
        ],
        'perRole' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer une fonction valide (lettres uniquement, espaces, tirets, apostrophes ou points autorisés).'
        ]
    ];

    // Variable de validation globale
    $isValid = true;

    // Boucle pour valider chaque champ
    foreach ($fields as $field => $config) {
        $value = $_POST[$field] ?? '';
        $_SESSION[$field] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        if (empty($value)) {
            $_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $field))] =
                "<li>Veuillez ne pas laisser le champ " . ucfirst(str_replace("per", "", $field)) . " vide !</li>";
            $isValid = false;
        } elseif ($field === 'perEmail') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["ErrorMessageEmail"] = "<li>Veuillez entrer une adresse email valide (ex: exemple@mail.ch) !</li>";
                $isValid = false;
            } else {
                $_SESSION["ErrorMessageEmail"] = "";
            }
        } elseif (!preg_match($config['regex'], $value)) {
            $_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $field))] =
                "<li>{$config['error']}</li>";
            $isValid = false;
        } else {
            $_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $field))] = "";
        }
    }

    // Si tous les champs sont valides
    if ($isValid) {
        $db->UpdatePerson(
            $_POST["perFirstName"],
            $_POST["perLastName"],
            $_POST["perAdress"],
            $_POST["perCity"],
            $_POST["perNPA"],
            $_POST["perEmail"],
            $_POST["perTel"],
            $_POST["perRole"],
            $_POST["ID_personne"]
        );

        $_SESSION["MessageAdd"] = "Personne modifiée avec succès !";

        // Nettoyage des sessions
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]);
            unset($_SESSION["ErrorMessage" . ucfirst(str_replace("per", "", $field))]);
        }
        unset($_SESSION["ErrorMessageEmail"]);
    } else {
        $_SESSION["MessageAdd"] = "";
    }

    // Redirection vers la page de modification
    if (!isset($_POST["ID_personne"]) || !is_numeric($_POST["ID_personne"])) {
        header("Location: ../../View/Modify/ModifyPersonPage.php");
        exit;
    } else {
        header("Location: ../../View/Modify/ModifyPersonPage.php?ID=" . $_POST["ID_personne"]);
        exit;
    }
} else {
    $_SESSION["ErrorMessage"] = "Aucune donnée reçue !";
    $_SESSION["MessageAdd"] = "";

    if (!isset($_POST["ID_personne"]) || !is_numeric($_POST["ID_personne"])) {
        header("Location: ../../View/Modify/ModifyPersonPage.php");
        exit;
    } else {
        header("Location: ../../View/Modify/ModifyPersonPage.php?ID=" . $_POST["ID_personne"]);
        exit;
    }
}
?>
