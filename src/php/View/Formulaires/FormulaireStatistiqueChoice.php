<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 21.05.2025 → Modif : 
 * Description : choix du semestre de statistique ou d'année de statistique
 */
?>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--liens avec le css personnelle et css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
        <!-- Inclure Bootstrap JS et jQuery -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       
        <title>FindYourBike</title>
    </head>
    <body>
        <div class="container">
        <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button>
        <h1>Choix de la durée des statistiques</h1>
        <form action="../StatistiquePage.php" method="post">
            <div class='form-group row mb-3'>
                <label for='Year' class='col-sm-4 col-form-label'>Année</label>
                <div class='col-sm-8'>
                    <input type='number' class='form-control' name='Year' id='Year'>
                    <small>
                        ex : 2024, 2025, 2026
                    </small>
                </div>
            </div>
            <?php 
                if($_GET["Choice"] == 1)
                {
            ?>
            <div class='form-group row mb-3'>
                <label for='trimester' class='col-sm-4 col-form-label'>Trimestre</label>
                <div class='col-sm-8'>
                    <input type='number' class='form-control' name='trimester' id='trimester'>
                    <small>
                        - Premier trimestre : Janvier, Février, Mars<br>
                        - Deuxième trimestre : Avril, Mai, Juin<br>
                        - Troisième trimestre : Juillet, Août, Septembre<br>
                        - Quatrième trimestre : Octobre, Novembre, Décembre<br>
                    </small>
                </div>
            </div>  
            <?php 
                }
            ?>          
            <div class="text-center">
                <button type="submit" class="btn">Générer statistique</button>
            </div>
        </form>
    </body>
</html>