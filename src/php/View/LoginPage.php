<?php
 /**
 * Auteur : Bajro Osmanovic
 * Date : 09.05.2025 → Modif : 
 * Description : page d'index/login
 */
?>

<div class="login-container">
    <h2>Page de connexion</h2>
    <form action="src/php/controller/checkLogin.php" method="post">
        <div>
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
        </div>
        <button type="submit" value="Login" class="btn">Se connecter</button>
        <a href="src/php/View/Formulaires/FormulaireInsciptionPage.php">
            <div class="btn">S’inscrire...?</div>
        </a>  
    </form>

</div>