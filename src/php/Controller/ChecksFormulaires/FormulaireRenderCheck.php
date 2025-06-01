<?php
session_start();
/**
 * Auteur : Bajro Osmanovic
 * Date : 14.05.2025 → Modif : 27.05.2025
 * Description : Vérification et enregistrer le formulaire de rendu
 */
// Inclusion des fichiers de configuration et de gestion de la base de données
require_once('../../Model/config.php');
require_once('../../Model/database.php');
// Création d'une instance de la classe Database pour l'accès à la base de données
$db = Database::getInstance();
// Vérification si l'utilisateur a soumis le formulaire (paramètre "Update" non défini dans l'URL)
if (!isset($_GET["Update"])) {
    // Définir le répertoire de téléchargement des images
    $uploadDir = '../../../../userContent/img/ImageProof/';
    // Limite : maximum 3 fichiers
    $maxFiles = 3;
    // Types MIME autorisés
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
    // Configuration des champs à valider avec leurs expressions régulières et messages d'erreur
    $fields = [
        'perAdress' => [
            'regex' => '/^[A-Za-zÀ-ÿ0-9\s\-\,\.]{5,}$/u',
            'label' => 'de l\'adresse du propriétaire',
            'error' => 'Veuillez entrer une adresse complète valide (minimum 5 caractères, lettres/chiffres autorisés) !'
        ],
        'perCity' => [ // Ville ou localité : lettres uniquement (espaces et ponctuation ok)
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'de la localité',
            'error' => 'Veuillez entrer une localité valide (lettres uniquement) !'
        ],
        'perNPA' => [ // Code postal suisse : 4 chiffres
            'regex' => '/^\d{4}$/',
            'label' => 'NPA',
            'error' => 'Veuillez entrer un NPA suisse à 4 chiffres (ex: 1000) !'
        ],
        'perEmail' => [ // Email classique
            'regex' => '/^[\w\.-]+@[\w\.-]+\.\w{2,}$/',
            'label' => 'de l\'email du propriétaire',
            'error' => 'Veuillez entrer une adresse email valide (ex: exemple@mail.ch) !'
        ],
        'perTel' => [ // Numéro suisse : +41 79 123 45 67 ou sans espaces
            'regex' => '/^\+41\s?\d{2}\s?\d{3}\s?\d{2}\s?\d{2}$/',
            'label' => 'du téléphone du propriétaire',
            'error' => 'Veuillez entrer un numéro de téléphone suisse valide (ex: +41 79 123 45 67) !'
        ],
        'perFirstName' => [ // Prénom : lettres avec accents, tirets ou apostrophes
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'du prénom du propriétaire',
            'error' => 'Veuillez entrer un prénom valide (lettres uniquement) !'
        ],
        'perLastName' => [ // Nom corrigé
            'regex' => '/^[A-Za-zÀ-ÿ\s\-\'\.]{2,}$/u',
            'label' => 'du nom du propriétaire',
            'error' => 'Veuillez entrer un nom valide (lettres uniquement) !'
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
        $shortName = ucfirst(str_replace("per", "", $field)); // Exemple : perLastName → LastName

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

    $date = $_POST["bikRestitutionDate"] ?? '';
    // Vérifie si le champ de date de restitution du vélo est vide
    if (empty($date)) {
         // Stocke un message d'erreur dans la session si aucune date n'est sélectionnée
        $_SESSION["ErrorMessageDate"] = "Veuillez sélectionner une date !";
        $isValid = false;
    } 
    else {
        // Si une date est fournie, elle est enregistrée en session (pour réaffichage si besoin)
        $_SESSION['bikRestitutionDate'] = $date;
    }

    // Si tous les champs sont valides
    if ($isValid) {
        // Appelle la méthode pour mettre à jour la BD 
        $db->RestitutionUpdate(
            $_POST["perFirstName"],
            $_POST["perLastName"],
            $_POST["perAdress"],
            $_POST["perCity"],
            $_POST["perNPA"],
            $_POST["perEmail"],
            $_POST["perTel"],
            $_POST["bikRestitutionDate"],
            $_GET["ID"],
            $FileNames
        );
        // Message de confirmation d'ajout
        $_SESSION["MessageAdd"] = "vélo rendu avec succès !";
        // Vider les données de la session après la mise à jour réussie
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]); // Supprime la donnée du champ de la session
        }
        unset($_SESSION['bikRestitutionDate']);
        // supprimer les messages d'erreurs
        foreach ($fields as $field => $config) {
            unset($_SESSION[$field]);
        }
        unset($_SESSION["ErrorMessageDate"]);
        unset($_SESSION["ErrorMessageImage"]); 

    } else {
        // Si des erreurs ont été détectées, le message d'ajout reste vide
        $_SESSION["MessageAdd"] = "Erreur trouvé";
    }
    $id = isset($_GET["ID"]) ? intval($_GET["ID"]) : 0;
    // Redirection vers la page d'ajout de bâtiment avec le message approprié
    header("Location: ../../View/Formulaires/FormulaireRenderBike.php?ID=" . $id);
    exit;
} else {
    // Si le paramètre "Update" est présent, cela signifie qu'aucune donnée n'a été reçue
    $_SESSION["ErrorMessage"] = "Aucune donnée reçue !";
    $_SESSION["MessageAdd"] = "";
    // Redirige l'utilisateur vers la page d'ajout de bâtiment 
    header("Location: ../../View/Formulaires/FormulaireRenderBike.php?ID=" . $id);
    exit;
}
