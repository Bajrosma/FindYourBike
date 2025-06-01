<?php
//démarrer une session
session_start();
/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 28.05.2025
 * Description : Vérification et enregistrer du formulaire d'annonce d'un vélo
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// Vérification si l'utilisateur a soumis le formulaire (paramètre "Update" non défini dans l'URL)
if (!isset($_GET["Update"])) {
    // Définir le répertoire de téléchargement des images
    $uploadDir = '../../../../userContent/img/ImageBike/';
    // Limite : maximum 3 fichiers
    $maxFiles = 3;
    // Types MIME autorisés
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    // Configuration des champs à valider avec leurs expressions régulières et messages d'erreur
    $fields = [
        'bikPlace' => [
            // Adresse complète : lettres, chiffres, espaces, virgules, tirets, points, minimum 5 caractères
            'regex' => '/^[A-Za-zÀ-ÿ0-9\s,\.\-]{5,}$/u',
            'label' => 'lieu de découverte',
            'error' => 'Veuillez entrer une adresse complète valide (au moins 5 caractères).'
        ],
        'bikFrameNumber' => [
            // Numéro de cadre : exactement 15 caractères alphanumériques
            'regex' => '/^[A-Za-z0-9]{5,15}$/',
            'label' => 'numéro de serie du cadre',
            'error' => 'Veuillez entrer un numéro de cadre valide (entre 5 et 15 lettres ou chiffres).'
        ]
    ];
    // Variable de validation globale pour le formulaire
    $isValid = true;
    // Compte le nombre de fichiers sélectionnés dans le champ 'images'
    $filesCount = isset($_FILES['images']['name']) ? count($_FILES['images']['name']) : 0;
    // Initialise un tableau pour stocker les noms uniques des fichiers enregistrés
    $FileNames = [];
    // Vérifie si le nombre de fichiers dépasse la limite autorisée
    if ($filesCount > $maxFiles) {
        $_SESSION["ErrorMessageImage"] = "Vous ne pouvez uploader que 3 images maximum !";
    } 
    // Si le nombre de fichiers est dans les limites autorisées
    else {
        // Vérifie que l'utilisateur a bien sélectionné au moins un fichier
        if (!empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            // Parcourt chaque fichier téléchargé
            foreach ($_FILES['images']['name'] as $i => $fileName) {
                // Récupère le chemin temporaire du fichier
                $tmpName = $files['tmp_name'][$i];
                // Détermine le type MIME du fichier (ex: image/jpeg)
                $type = mime_content_type($tmpName);
                // Récupère le nom de base du fichier (sans le chemin)
                $name = basename($fileName);
                // Vérifie si le type MIME du fichier fait partie des types autorisés
                if (!in_array($type, $allowedTypes)) {
                    $_SESSION["ErrorMessageImage"] = "Le fichier $name n'est pas une image valide !";
                    $isValid = false;
                    continue; // Passe au fichier suivant
                }
                // Génère un nom unique pour éviter les conflits (préfixe unique + nom original)
                $uniqueName = uniqid() . "-" . $name;
                // Définit le chemin de destination complet pour enregistrer l'image
                $destination = $uploadDir . $uniqueName;
                // Tente de déplacer le fichier depuis le dossier temporaire vers le dossier de destination
                if (move_uploaded_file($tmpName, $destination)) {
                    // Enregistrement réussi : ajoute le nom du fichier dans le tableau
                    $FileNames[] = $uniqueName;
                    unset($_SESSION["ErrorMessageImage"]);
                } 
                // Si l'enregistrement échoue, stocke un message d'erreur en session
                else {
                    $_SESSION["ErrorMessageImage"] = "Le fichier $name n'a pas pu être enregistré !";
                    $isValid = false;
                }
            }
        }
        // si aucune donnée trouvé
        else
        {
            $isValid = false;
            $_SESSION["ErrorMessageImage"] = "Aucun Fichier ajouté";
        }
    }

    // Boucle pour valider chaque champ
    foreach ($fields as $field => $config) {
        $value = trim($_POST[$field] ?? ''); // Récupère la valeur du champ, ou vide si non défini
        $_SESSION[$field] = $value; // Stocke la valeur dans la session pour réaffichage
        $shortName = ucfirst(str_replace("bik", "", $field)); // Exemple : bikFrameNumber → FrameNumber
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
        // Appelle la méthode pour ajouter un bâtiment dans la base de données
        $db->NewBike(
            $_POST["bikDate"],
            $_POST["bikPlace"],
            $_POST["bikFrameNumber"],
            $_POST["FK_color"],
            $_POST["FK_brand"],
            $_POST["FK_size"],
            $_POST["FK_commune"],
            $FileNames
        );
        // Message de confirmation d'ajout
        $_SESSION["MessageAdd"] = "vélo ajouté avec succès !";
        // Vider les données de la session après la mise à jour réussie
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]); // Supprime la donnée du champ de la session
        }
        foreach ($requiredFields as $field) {
            unset($_SESSION[$field]);
        }
    } else {
        // Si des erreurs ont été détectées, le message d'ajout reste vide
        $_SESSION["MessageAdd"] = "";
    }
    // Redirection vers la page d'ajout d'un vélo avec le message approprié
    header("Location: ../../View/Formulaires/FormulaireBikePage.php");
    exit;
} 
else 
{
    // Si le paramètre "Update" est présent, cela signifie qu'aucune donnée n'a été reçue
    $_SESSION["ErrorMessage"] = "Aucune donnée reçue !";
    $_SESSION["MessageAdd"] = "";
    // Redirige l'utilisateur vers la page d'ajout d'un vélo
    header("Location: ../../View/Formulaires/FormulaireBikePage.php");
    exit;
}
