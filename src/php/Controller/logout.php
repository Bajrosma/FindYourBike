<?php
/**
 * Auteur : Bajro Osmanovic
 * Date : 09.95.2025 → Modif : 
 * Description : permet de se deconnecter correctement
 */
session_start();
session_destroy();
header("Location: ../../../index.php");
exit();
?>