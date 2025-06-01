<?php
//démarrer une session
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
    // Configuration des champs à valider
    $fields = [
        'comName' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'nom de commune',
            'error' => 'Veuillez entrer une commune valide (lettres, espaces, tirets ou apostrophes uniquement) !'
        ],
        'comAdress' => [
            'regex' => '/^[A-Za-zÀ-ÿ0-9\s\-\,\.]{3,}\s\d{2}$/u',
            'label' => 'adresse de la commune',
            'error' => 'Veuillez entrer une adresse complète valide (minimum 5 caractères et un numéro de rue) !'
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
            'regex' => '/^[\w\.-]+@[\w\.-]+\.\w{2,}$/',
            'label' => 'email',
            'error' => 'Veuillez entrer une adresse email valide (ex: exemple@mail.ch) !'
        ],
        'comTel' => [
            'regex' => '/^\+41\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}$/',
            'label' => 'numéro de téléphone',
            'error' => 'Veuillez entrer un numéro de téléphone suisse valide (ex: +41 79 123 45 67) !'
        ],
        'comLastName' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'nom du responsable',
            'error' => 'Veuillez entrer un nom valide (lettres uniquement) !'
        ],
        'comFirstName' => [ // Nom corrigé ici
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'prénom du responsable',
            'error' => 'Veuillez entrer un prénom valide (lettres uniquement) !'
        ],
        'comFonction' => [
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'fonction',
            'error' => 'Veuillez entrer une fonction valide (lettres uniquement) !'
        ]
    ];
    // Variable de validation globale
    $isValid = true;
    // Boucle pour valider chaque champ
    foreach ($fields as $field => $config) {
        $value = trim($_POST[$field] ?? ''); // Récupère la valeur du champ, ou vide si non défini
        $_SESSION[$field] = $value; // Stocke la valeur dans la session pour réaffichage
        $shortName = ucfirst(str_replace("com", "", $field)); // Exemple : comCity → City
        // Vérification si le champ est vide
        if (empty($value)) {
            // Si le champ est vide, ajoute un message d'erreur dans la session
            $_SESSION["ErrorMessage$shortName"] = "Veuillez ne pas laisser le champ {$config['label']} vide !";
            $isValid = false;
        // Vérification de la correspondance avec l'expression régulière
        } elseif (!preg_match($config['regex'], $value)) {
            // Si la validation échoue, ajoute un message d'erreur spécifique
            $_SESSION["ErrorMessage$shortName"] = "{$config['error']}";
            $isValid = false;
        } else {
            // Si la validation est réussie, efface le message d'erreur
            unset($_SESSION["ErrorMessage$shortName"]);
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
            $_POST["comFirstName"],
            $_POST["comFonction"]
        );
        // Message de confirmation d'ajout
        $_SESSION["MessageAdd"] = "Inscription enregistrée avec succès !";        // Vider les données de la session après la mise à jour réussie
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
    $_SESSION["MessageAdd"] = "Aucune donnée reçue !";
    // Redirige l'utilisateur vers la page d'ajout de bâtiment
    header("Location: ../../View/Formulaires/FormulaireInsciptionPage.php");
    exit;
}
