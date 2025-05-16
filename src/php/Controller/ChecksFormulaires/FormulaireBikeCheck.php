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

    // Dossier où les fichiers seront enregistrés
    $uploadDir = __DIR__ . '../../../../userContent/img/ImageBike/';
    // Limite : maximum 3 fichiers
    $maxFiles = 3;
    // Types MIME autorisés
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    // Taille maximale de chaque fichier : 2 Mo
    $maxSize = 2 * 1024 * 1024;
    // Configuration des champs à valider avec leurs expressions régulières et messages d'erreur
    $fields = [
        'bikPlace' => [
            // Adresse complète : lettres, chiffres, espaces, virgules, tirets, points, minimum 5 caractères
            'regex' => '/^[A-Za-zÀ-ÿ0-9\s,\.\-]{5,}$/u',
            'error' => 'Veuillez entrer une adresse complète valide (au moins 5 caractères).'
        ],
        'bikFrameNumber' => [
            // Numéro de cadre : exactement 15 caractères alphanumériques
            'regex' => '/^[A-Za-z0-9]{5,15}$/',
            'error' => 'Veuillez entrer un numéro de cadre valide (15 lettres ou chiffres).'
        ]
    ];
    // Variable de validation globale
    $isValid = true;  
    // Vérifie si des fichiers ont été envoyés
    if (isset($_FILES['images'])) 
    {
        $files = $_FILES['images'];
        $fileCount = count($files['name']);
        // Vérifie que le nombre de fichiers ne dépasse pas la limite
        if ($fileCount > $maxFiles) 
        {
            die("Vous ne pouvez uploader qu'un maximum de $maxFiles images.");
            $isValid = false;
        }
        // Parcours chaque fichier
        for ($i = 0; $i < $fileCount; $i++) 
        {
            // Vérifie qu'il n'y a pas d'erreur pour ce fichier
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];         // Nom temporaire
                $name = basename($files['name'][$i]);      // Nom original
                $type = mime_content_type($tmpName);       // Type MIME réel
                $size = filesize($tmpName);                // Taille du fichier

                // Vérifie que le type MIME est autorisé
                if (!in_array($type, $allowedTypes)) {
                    echo "Le fichier $name n'est pas une image valide.<br>";
                    $isValid = false;
                    continue;
                }

                // Vérifie que le fichier ne dépasse pas la taille maximale
                if ($size > $maxSize) {
                    echo "Le fichier $name dépasse la taille maximale de 2 Mo.<br>";
                    $isValid = false;
                    continue;
                }

                // Génère un nom de fichier unique pour éviter les conflits
                $uniqueName = uniqid() . '-' . $name;
                $destination = $uploadDir . $uniqueName;

                // Déplace le fichier vers le dossier final
                if (move_uploaded_file($tmpName, $destination)) {
                    echo "Image $name enregistrée avec succès.<br>";
                } else {
                    echo "Erreur lors de l’envoi de l’image $name.<br>";
                    $isValid = false;
                }
            } else {
                echo "Erreur avec le fichier numéro " . ($i + 1) . "<br>";
                $isValid = false;
            }
        }
    }

    // Boucle pour valider chaque champ
    foreach ($fields as $field => $config) {
        $value = $_POST[$field] ?? ''; // Récupère la valeur du champ, ou vide si non défini
        $_SESSION[$field] = $value; // Stocke la valeur dans la session pour réaffichage

        // Vérification si le champ est vide
        if (empty($value)) {
            // Si le champ est vide, ajoute un message d'erreur dans la session
            $_SESSION["ErrorMessage" . ucfirst(str_replace("bik", "", $field))] = "<li>Veuillez ne pas laisser le champ " . ucfirst(str_replace("bui", "", $field)) . " vide !</li>";
            $isValid = false;
        // Vérification de la correspondance avec l'expression régulière
        } elseif (!preg_match($config['regex'], $value)) {
            // Si la validation échoue, ajoute un message d'erreur spécifique
            $_SESSION["ErrorMessage" . ucfirst(str_replace("bik", "", $field))] = "<li>{$config['error']}</li>";
            $isValid = false;
        } else {
            // Si la validation est réussie, efface le message d'erreur
            $_SESSION["ErrorMessage" . ucfirst(str_replace("bik", "", $field))] = "";
        }
    }
    

    // Si tous les champs sont valides
    if ($isValid) {
        // Appelle la méthode pour ajouter un bâtiment dans la base de données
        $db->AddBike(
            $_POST["bikDate"],
            $_POST["bikPlace"],
            $_POST["bikFrameNumber"],
            $_POST["FK_color"],
            $_POST["FK_brand"],
            $_POST["FK_size"],
            $_POST["FK_commune"]
        );
        // Message de confirmation d'ajout
        $_SESSION["MessageAdd"] = "vélo ajouté avec succès !";
        // Vider les données de la session après la mise à jour réussie
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]); // Supprime la donnée du champ de la session
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