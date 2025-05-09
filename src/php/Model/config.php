<?PHP
    // Définir et dire que le text est égal à mysql
    define("DB_LANGUAGE","mysql");
    // Définir et dire que le text est égal à 127.0.0.1
    define("HOST_NAME","127.0.0.1;");
    // Définir et dire que le text est égal au nom de la base de données
    define("DB_NAME","db_findYourBike;");
    // Définir et dire que le text est égal au charset utf-8
    define("CHARSET","charset=utf8");
    // Définir et assembler tout les éléments pour faire la connexion de la base de donnée
    define("DB",DB_LANGUAGE.":host=".HOST_NAME."dbname=".DB_NAME.CHARSET);
    // Définir et dire que le text est égal au nom de l'utilisateur
    define("USER","FindYourBikeRequest");
?>