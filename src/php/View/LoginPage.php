<?php
 //commence le système de session
 session_start();

 /**
 * Auteur : Bajro Osmanovic
 * Date : 09.95.2025 → Modif : 
 * Description : page d'index/login
 */
?>

<div class="login-container">
    <h2>Page de connexion</h2>
    <form action="src/php/controller/checkLogin.php" method="post">
        <input type="hidden" name="action" value="login">    
        <p>Nom d'utilisateur</p>
        <input name="username" type="username" required><br>
        <p>Mot de passe</p>
        <input name="password" type="password" required><br>
        <?php 
        if(isset($_SESSION["MessageErrorLogin"]))
        {
            echo $_SESSION["MessageErrorLogin"];
        }
        ?>
        <button type="submit" value="Login">Se connecter</button>
    </form>
    <a href="src/php/View/FormulaireInsciptionPage.php">
        <button type="submit" value="Login">S’inscrire...?</button>
    </a>  
</div>