<?php
    //commence le système de session
    session_start();

    /**
     * Auteur : Bajro Osmanovic
     * Date : 21.05.2025 → Modif : 
     * Description : page affichants les statistiques 
     */
    // Inclusion des fichiers de configuration et de gestion de la base de données
    require_once('../Model/config.php');
    require_once('../Model/database.php');
    // Création d'une instance de la classe Database pour l'accès à la base de données
    $db = Database::getInstance();
    // information nécessaire au liste décourlantes 
    $sizes = $db->GetAllSizes();
    $brands = $db->GetAllBrands();
    $colors = $db->GetAllColors();
    // récupèrer toutes les informations pour l'affichage 
    if(isset($_POST["trimester"]))
    {
      $bikes = $db->GetDataForStatistiqueTrimestre($_POST["Year"], $_POST["trimester"]);
    }
    else
    {
      $bikes = $db->GetDataForStatistiqueYear($_POST["Year"]);
    }
    // Compte le nombre de vélos présent dans le tableau retournée
    $total = count($bikes);
    // fixe le nombre de vélo non rendu à 0 
    $notRender = 0;
    // repasse en revu tout les vélos et compte les vélos qui ne sont pas rendu
    foreach($bikes as $bike)
    {
        if($bike["bikResitutionDate"] == NULL)
        {  
            $notRender = $notRender + 1;
        }
    }
    // calcule le pourcentage des vélos rendu
    $rendered = round(100/$total * ($total - $notRender), 2);
    // calcule le pourcentage des vélos non rendu
    $notRender = round(100/$total * $notRender, 2);
?>
<!DOCTYPE html>
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
        <div class="table-container">
          <a href="ChooseStatistiquePage.php" class="btn-back">← Retour</a>
          <h1>Statistique <?php 
          if(isset($_POST["trimester"]))
          {
          echo $_POST["Year"] . ", Semestre" . $_POST["trimester"];
          }
          else 
          {
            echo $_POST["Year"];
          }
          
          ?></h1>
          <div id="graph"></div>
          <br>
          <div style="margin-bottom: 15px;">
              <label for="filterSerial">Numéro de série :</label>
              <input type="text" id="filterSerial" oninput="filterTable()">
              <label for="filterBrand">Marque :</label>
              <select id="filterBrand" onchange="filterTable()">
                  <option value="">Toutes</option>
                  <?php 
                      // parcours le tableau des marques 
                      foreach($brands as $brand )
                      {
                          echo '<option value="'. $brand["braName"] .'">'. $brand["braName"] .'</option>"';
                      }
                  ?>
              </select>

              <label for="filterSize">Taille :</label>
              <select id="filterSize" onchange="filterTable()">
                  <option value="">Toutes</option>
                  <?php 
                      // parcours le tableau des tailles         
                      foreach($sizes as $size )
                      {
                          echo '<option value="'. $size["sizSize"] .'">'. $size["sizSize"] .'</option>"';
                      }
                  ?>
              </select>

              <label for="filterColor">Couleur :</label>
              <select id="filterColor" onchange="filterTable()">
                  <option value="">Toutes</option>
                  <?php 
                      // parcours le tableau des couleurs
                      foreach($colors as $color )
                      {
                          echo '<option value="'. $color["colName"] .'">'. $color["colName"] .'</option>"';
                      }
                  ?>
              </select>
          </div>
          <div class="table-responsive">
                <table class="table">
                  <thead>
                      <tr>
                          <th>Numéro de séries</th>
                          <th>Marque</th>
                          <th>Taille</th>
                          <th>couleur</th>
                          <th>Adresse où il a été retrouvé</th>
                          <th>Date découverte</th>
                          <th>Commune de référence</th>
                          <th>Rendu</th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- données -->
                      <?php
                          foreach($bikes as $bike)
                          {
                                  // Numéro de serie du cadre 
                                  echo '<tr><td>' . $bike["bikFrameNumber"] . '</td>';
                                  // Marque du vélo 
                                  echo '<td>' . $bike["braName"] . '</td>';
                                  // Taille du vélo 
                                  echo '<td>' . $bike["sizSize"] . '</td>';
                                  // Couleur du vélo 
                                  echo '<td>' . $bike["colName"] . '</td>';
                                  // lieu ou le vélo a été retrouvé
                                  echo '<td>' . $bike["bikPlace"] . '</td>';
                                  // date de la découverte du vélo 
                                  echo '<td>' . $bike["bikDate"] . '</td>';
                                  // Commune oû le vélo est stocker 
                                  echo '<td>' . $bike["comName"] . '</td>';
                                // affiche si il a été rendu ou pas 
                                if($bike["bikResitutionDate"] == NULL)
                                {
                                  echo '<td>non Rendu</td>';
                                } 
                                else 
                                {
                                  echo '<td>Rendu</td>';
                                }
                          }
                      ?>
                  </tbody>
              </table>
            </div>          
        </div>
    
    </bod<>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
  // Fonction qui filtre les lignes du tableau en fonction des filtres sélectionnés
  function filterTable() 
    {
        // Récupère les valeurs des filtres (converties en minuscules pour éviter les problèmes de casse)
        const serialFilter = document.getElementById("filterSerial").value.toLowerCase(); // numéro de série
        const brandFilter = document.getElementById("filterBrand").value.toLowerCase();   // marque
        const sizeFilter = document.getElementById("filterSize").value.toLowerCase();     // taille
        const colorFilter = document.getElementById("filterColor").value.toLowerCase();   // couleur

        // Sélectionne toutes les lignes du tableau (à l'intérieur du tbody)
        const rows = document.querySelectorAll("table tbody tr");

        // Parcours chaque ligne du tableau
        rows.forEach(row => 
        {
            // Récupère les données des cellules correspondantes aux filtres
            const serial = row.cells[0].textContent.toLowerCase(); // cellule 1 : numéro de série
            const brand = row.cells[1].textContent.toLowerCase();  // cellule 2 : marque
            const size = row.cells[2].textContent.toLowerCase();   // cellule 3 : taille
            const color = row.cells[3].textContent.toLowerCase();  // cellule 4 : couleur

            // Vérifie si la ligne correspond à tous les filtres
            const show =
                serial.includes(serialFilter) && // le numéro de série contient la chaîne recherchée
                (brandFilter === "" || brand === brandFilter) && // si un filtre marque est défini, il doit correspondre
                (sizeFilter === "" || size === sizeFilter) &&     // idem pour la taille
                (colorFilter === "" || color === colorFilter);    // idem pour la couleur

            // Affiche la ligne si elle correspond, sinon la masque
            row.style.display = show ? "" : "none";
        });
    }

      // Script venant de Code Pen
      Highcharts.chart('graph', {
        chart: {
          type: 'pie'
        },
        title: {
          text: 'Répartition des vélos rendu ou pas'
        },
        series: [{
          name: 'Statut',
          colorByPoint: true,
          data: [{
            name: 'Non-Rendu',
            y: <?php echo $notRender; ?>,
            drilldown: 'nonRendu'
          }, {
            name: 'Rendu',
            y: <?php echo $rendered; ?>,
            drilldown: 'rendu'
          }]
        }]
      });
</script>