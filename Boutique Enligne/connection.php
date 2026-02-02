<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="Css/style.css" rel="stylesheet" /> 
    <title>Connection</title>
</head>
<body>
    <div class="auth-container">
    <form class="auth-form" action="login-validation.php" method="post">
        <h2>Connexion</h2>

        <label>Email</label>
        <input type="email" placeholder="ex: aguibou@email.com" name="email" required>

        <label>Password</label>
        <input type="password" placeholder="********" name="pass" required>

        <button type="submit">Connexion</button>

        <p class="auth-link">
            Pas de compte ?
            <a href="inscription.php">Inscrivez-vous</a>
        </p>
    </form>
</div>
</body>
</html>