<?php
session_start();

/**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 21.05.2025
 * Description : Vérification et enregistrer le formulaire d'inscirption
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

    $filesCount = count($_FILES['images']['name']);

    if($filesCount > $maxFiles)
    {
        $ErrorMessage = "vous ne pouvez qu'uploader jusqu'à 3 images maximum !";
    }
    else 
    {
        if(isset($_FILES))
        {
            $files = $_FILES['images'];

            foreach($_FILES['images']['name'] as $i => $fileName)
            {
                $tmpName = $files['tmp_name'][$i];
                $type = mime_content_type($tmpName);
                $name = basename($fileName);
                // vérifie si l'extension du fichier et compatible
                if(!in_array($type, $allowedTypes))
                {
                    $ErrorMessage = "le fichier $name n'est pas une image valide !";
                    $isValid = false;
                    continue;
                }
                // préparation du chemin de stockage de l'image.
                $uniqueName = uniqid()."-".$name;
                $destination = $uploadDir . $name;
                // si le déplacement réussi 
                if(move_uploaded_file($tmpName, $destination))
                {
                    //echo "image $name enregistrée avec succès.";
                    $FileNames[] = $uniqueName;
                }
                // si le déplacement ne réussi pas 
                else 
                {
                    $ErrorMessage = "le fichier $name n'a pas pu être enregistrer !";
                }
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