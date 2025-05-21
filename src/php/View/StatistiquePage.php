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
    $bikes = $db->GetAllBikes();
    // reinitialise le message après une action 
    $_SESSION["MessageAdd"] = "";

    $total = count($bikes);
    $notRender = 0;

    foreach($bikes as $bike)
    {
        if($bike["bikResitutionDate"] == NULL)
        {  
            $notRender = $notRender + 1;
        }
    }
    $rendered = $total - $notRender;    

    $notRender = 100/$total * $notRender;

    $notRender = round($notRender, 2);

    echo $notRender . '<br>';

    $rendered = 100/$total * $rendered;
    $rendered = round($rendered, 2);
    echo $rendered;

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
        <div class="container">
        <h1>Page d'accueil</h1>
            <div class="grid">
            <a class="btn">Trimestre</a>
            <a class="btn">Annuel</a>
            </div>
        </div>
    <div id="graph"></div>
    </bod<>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>

    // Build the chart
Highcharts.chart('graph', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Browser market shares in January, 2018'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  },
  series: [{
    name: 'Vélo',
    colorByPoint: true,
    data: [{
      name: 'Non-Rendu',
      y: <?php echo $notRender; ?>,
      sliced: true,
      selected: true
    }, {
      name: 'Rendu',
      y: <?php echo $rendered; ?>
    }]
  }]
});

</script>