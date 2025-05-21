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
    // récupèrer toutes les informations pour l'affichage 
    if(isset($_POST["trimestre"]))
    {
      $bikes = $db->GetDataForStatistiqueTrimestre($_POST["Year"], $_POST["trimestre"]);
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
          <button onclick="history.back()" style="margin-bottom: 15px;">← Retour</button>
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
          <table>
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
    
    </bod<>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
      // Script form Code Pen
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