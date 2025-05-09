<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 09.95.2025 → Modif : 
 * Description : page du formulaire  d'inscription
 */
?>

<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <!--liens avec le css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../ressources/css/codepen.css">
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
    <form action="FormulaireInsciptionCheck.php">
        <div class="grid">
            <p>Nom de la comune</p>
            <input name="comName" type="Commune">
            <p>Adresse</p>
            <input name="comAdress" type="username" required>
            <p>Localité</p>
            <input name="comCity" type="City" required>
            <p>NPA</p>
            <input name="comNPA" type="NPA" required>   
            <p>Email</p>
            <input name="comEmail" type="Email" required>  
            <p>Tel</p>
            <input name="comTel" type="comTel" required>  
            <p>Nom du responsable</p>
            <input name="comLastName" type="LastName" required>  
            <p>Prénom du responsable</p>
            <input name="comFisrtName" type="FisrtName" required>
            <p>Fonction</p>
            <input name="comFonction" type="Fonction" required> 
    </div>
    <button type="submit" value="Login">Soumettre le formulaire</button>
</form>
</body>
</html>