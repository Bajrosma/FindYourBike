<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 21.05.2025 → Modif : 28.05.2025
 * Description : choix du semestre de statistique ou d'année de statistique
 */

 $choice = $_GET["Choice"] ?? null;
?>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--liens avec le css personnelle et css de bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../../../ressources/css/codepen.css">
      
        <title>FindYourBike</title>
    </head>
    <body>
        <div class="container">
            <a href="../ChooseStatistiquePage.php" class="btn-back">← Retour</a>
            <h1>Choix de la durée des statistiques</h1>
            <form action="../StatistiquePage.php" method="post">
                <input type="hidden" name="Choice" value="<<?php echo htmlspecialchars($choice); ?>">
                <div class='form-group row mb-3'>
                    <label for='Year' class='col-sm-4 col-form-label'>Année</label>
                    <div class='col-sm-8'>
                        <input type='number' class='form-control' name='Year' id='Year' required>
                        <small id="yearHelp">ex : 2024, 2025, 2026</small>
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
                                - taper 1 pour le premier trimestre : Janvier, Février, Mars<br>
                                - taper 2 pour le deuxième trimestre : Avril, Mai, Juin<br>
                                - taper 3 pour le troisième trimestre : Juillet, Août, Septembre<br>
                                - taper 4 pour le quatrième trimestre : Octobre, Novembre, Décembre<br>
                            </small>
                        </div>
                    </div>  
                    <?php 
                        }
                    ?>          
                    <div class="text-center">
                        <button type="submit" class="btn">Générer statistique</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
