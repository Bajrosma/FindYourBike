<?php
//démarrer une session
session_start();
/**
* Auteur :         Bajro Osmanovic
* Date :           06.05.2025 → Modif : 27.05.2025
* Description :    fichier php qui permet de crée des comptes utilisateurs
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
        'useName' => [
            'regex' => '/^[a-zA-Z0-9]{4,20}$/u',
            'label' => 'nom d\'utilisateur',
            'error' => 'Le nom de compte doit contenir entre 4 et 20 caractères alphanumériques.'
        ],
        'usePassword' => [
            'regex' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/u',
            'label' => 'mot de passe d\'utilisateur',
            'error' => 'Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.'
        ],
        'usePasswordConfirm' => [
            'regex' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/u',
            'label' => 'mot de passe d\'utilisateur',
            'error' => 'Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.'
        ]
    ];
    // Variable de validation globale
    $isValid = true;
    // Boucle pour valider chaque champ
    foreach ($fields as $field => $config) {
        $value = trim($_POST[$field] ?? ''); // Récupère la valeur du champ, ou vide si non défini
        $_SESSION[$field] = $value; // Stocke la valeur dans la session pour réaffichage
        $shortName = ucfirst(str_replace("use", "", $field)); // Exemple : usePassword → Password

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
    // contrôle que les mots de passes soit bien valide.
    if ($_POST["usePassword"] !== $_POST["usePasswordConfirm"]) {
        $_SESSION["ErrorMessagePasswordConfirm"] = "<li>Les mots de passe ne correspondent pas.</li>";
        $isValid = false;
    }
    else
    {
        $password = password_hash($_POST["usePassword"], PASSWORD_DEFAULT);
    }
    // Si tous les champs sont valides
    if ($isValid) {
        // Appelle la méthode pour ajouter un bâtiment dans la base de données
        $db->CreateAccount(
            $_POST["useName"],
            $password,
            $_POST["usePrivilage"]
        );
        // Message de confirmation d'ajout
        $_SESSION["MessageAdd"] = "Utilisateurs créé et ajouté avec succès !";
        // Vider les données de la session après la mise à jour réussie
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]); // Supprime la donnée du champ de la session
        }
        foreach ($fields as $field => $_) {
            unset($_SESSION["ErrorMessage" . ucfirst(str_replace("use", "", $field))]);
        }
        unset($_SESSION["ErrorMessagePasswordConfirm"]);
        unset($_SESSION["ErrorMessagePrivilage"]);
    } else {
        // Si des erreurs ont été détectées, le message d'ajout reste vide
        $_SESSION["MessageAdd"] = "";
    }
    // Redirection vers la page d'ajout de bâtiment avec le message approprié
    header("Location: ../../View/Formulaires/FormulaireCreateUsers.php");
    exit;
} else {
    // Si le paramètre "Update" est présent, cela signifie qu'aucune donnée n'a été reçue
    $_SESSION["ErrorMessage"] = "Aucune donnée reçue !";
    $_SESSION["MessageAdd"] = "";
    // Redirige l'utilisateur vers la page d'ajout de bâtiment
    header("Location:../../View/Formulaires/FormulaireCreateUsers.php");
    exit;
}
