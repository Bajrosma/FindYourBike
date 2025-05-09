<?php 
//ajoute le fichier qui gère les requette SQL
require_once('../Model/config.php');
require_once('../Model/database.php');

$tests=Database::getInstance()->Getusers(); 

$validConnection = false;

foreach($tests as $test)
{
    if ($test["useName"] == $_POST["username"])
    {
        if ($test["usePassword"] == $_POST["password"])
        {
            $validConnection = true;
            $_SESSION["rights"] = $test["usePrivilage"];
        }   
    }
}

if($validConnection)
{

}
else
{

}