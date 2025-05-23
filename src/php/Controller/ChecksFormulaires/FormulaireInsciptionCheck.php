<?php
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 12.05.2025
 * Description : Vérification et enregistrer le formulaire d'inscirption
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
        'comName' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer une commune valide (lettres, espaces, tirets ou apostrophes uniquement) !'
        ],
        'comAdress' => [
            'regex' => '/^[A-Za-zÀ-ÿ0-9\s\-\,\.]{3,}\s\d{2}$/u',
            'error' => 'Veuillez entrer une adresse complète valide (minimum 5 caractères, lettres/chiffres autorisés ET le numéro de la rue) !'
        ],
        'comCity' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer une localité valide (lettres uniquement) !'
        ],
        'comNPA' => [
            'regex' => '/^\d{4}$/',
            'error' => 'Veuillez entrer un NPA suisse à 4 chiffres (ex: 1000) !'
        ],
        'comEmail' => [
            'regex' => '/^[\w\.-]+@[\w\.-]+\.\w{2,}$/',
            'error' => 'Veuillez entrer une adresse email valide (ex: exemple@mail.ch) !'
        ],
        'comTel' => [
            'regex' => '/^\+41\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}$/',
            'error' => 'Veuillez entrer un numéro de téléphone suisse valide (ex: +41 79 123 45 67) !'
        ],
        'comLastName' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer un nom valide (lettres, tirets ou apostrophes uniquement) !'
        ],
        'comFisrtName' => [ // À renommer en 'FirstName' dans ton code si c’est une faute
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer un prénom valide (lettres, tirets ou apostrophes uniquement) !'
        ],
        'comFonction' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'error' => 'Veuillez entrer une fonction valide (lettres uniquement) !'
        ]
    ];
    $message = [
        'Name' => 'nom de commune',
        'Adress ' => 'adresse de la commune',
        'City ' => 'localité',
        'NPA' => 'NPA',
        'Name' => 'nom de commune',
        'Email' => 'email ',
        'Tel' => 'tel ',
        'LastName ' => 'nom du responsable ',
        'FisrtName ' => 'prénom du responsable',
        'Fonction ' => 'fonction',
    ];

    // Variable de validation globale
    $isValid = true;

    // Boucle pour valider chaque champ
    foreach ($fields as $field => $config) {
        $value = $_POST[$field] ?? ''; // Récupère la valeur du champ, ou vide si non défini
        $_SESSION[$field] = $value; // Stocke la valeur dans la session pour réaffichage

        // Vérification si le champ est vide
        if (empty($value)) {
            // Si le champ est vide, ajoute un message d'erreur dans la session
            $_SESSION["ErrorMessage" . ucfirst(str_replace("com", "", $field))] =
                      "<li>Veuillez ne pas laisser le champ " . $message[
                      ucfirst(str_replace("com", "", $field))] . " vide !</li>";
            $isValid = false;
        // Vérification de la correspondance avec l'expression régulière
        } elseif (!preg_match($config['regex'], $value)) {
            // Si la validation échoue, ajoute un message d'erreur spécifique
            $_SESSION["ErrorMessage" . ucfirst(str_replace("com", "", $field))] =
                "<li>{$config['error']}</li>";
            $isValid = false;
        } else {
            // Si la validation est réussie, efface le message d'erreur
            $_SESSION["ErrorMessage" . ucfirst(str_replace("com", "", $field))] = "";
        }
    }

    // Si tous les champs sont valides
    if ($isValid) {
        // Appelle la méthode pour ajouter un bâtiment dans la base de données
        $db->InscriptionAdd(
            $_POST["comName"],
            $_POST["comAdress"],
            $_POST["comCity"],
            $_POST["comNPA"],
            $_POST["comEmail"],
            $_POST["comTel"],
            $_POST["comLastName"],
            $_POST["comFisrtName"],
            $_POST["comFonction"]
        );
        // Message de confirmation d'ajout
        $_SESSION["MessageAdd"] = "Inscription enregistré avec succès !";
        // Vider les données de la session après la mise à jour réussie
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]); // Supprime la donnée du champ de la session
        }
    } else {
        // Si des erreurs ont été détectées, le message d'ajout reste vide
        $_SESSION["MessageAdd"] = "";
    }

    // Redirection vers la page d'ajout de bâtiment avec le message approprié
    header("Location: ../../View/Formulaires/FormulaireInsciptionPage.php");
    exit;
} else {
    // Si le paramètre "Update" est présent, cela signifie qu'aucune donnée n'a été reçue
    $_SESSION["ErrorMessage"] = "Aucune donnée reçue !";
    $_SESSION["MessageAdd"] = "";
    // Redirige l'utilisateur vers la page d'ajout de bâtiment
    header("Location: ../../View/Formulaires/FormulaireInsciptionPage.php");
    exit;
}