<?php
 //commence le système de session
 session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <!--liens avec le css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="ressources/css/codepen.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Inclure Bootstrap JS et jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BuildPro</title>
       
        <!--Change l'icone de la page (celle a côté du titre)-->
        <link rel="icon" href="userContent/img/Logo2.png" type="image/x-icon">
    </head>


<?php
    //ajoute le fichier qui gère les requette SQL
    require_once('src/php/Model/config.php');
    require_once('src/php/Model/database.php');
 
    //inclue le contenu de la page
    include("src/php/View/LoginPage.php");

    //création de la session
    $database = Database::getInstance();//->createSession

    $_SESSION["admin"] = 0;

    //création de la session
    //$tests=Database::getInstance()->GetAllCommunes(); 

    var_dump($tests);

?>
<html>
    <body>
        

    </body>

</html>
