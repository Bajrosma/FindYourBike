<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 09.95.2025 → Modif : 
 * Description : page d'accueil en tant que membre de la commune
 */

echo $_SESSION["rights"];
?>

<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <!--liens avec le css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Inclure Bootstrap JS et jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FindYourBike</title>
       
        <!--Change l'icone de la page (celle a côté du titre)-->
        <link rel="icon" href="userContent/img/Logo2.png" type="image/x-icon">
    </head>
<body>

  <div class="container">
    <h1>Page d'accueil</h1>
    <div class="grid">
      <a class="btn" href="../Listes/ListeBike.php">Liste des vélos</a>
      <a class="btn" href="../Formulaires/FormulaireBikePage.php">Ajouter un vélo retrouvé</a>
      <div class="btn">Statistiques</div>
      <div class="btn">Liste des communes / informations</div>
    </div>
    <a href="../../Controller/logout.php" class="logout">Déconnexion</a>
  </div>

</body>
</html>