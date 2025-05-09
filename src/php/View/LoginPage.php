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
    <a href="#">
        <button type="submit" value="Login">S’inscrire...?</button>
    </a>
    
</div>