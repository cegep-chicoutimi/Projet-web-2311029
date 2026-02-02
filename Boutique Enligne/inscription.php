<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="Css/style.css" rel="stylesheet" /> 
    <title>Document</title>
</head>
<body>
    <div class="auth-container">
    <form class="auth-form" action="inscription-valid.php" method="post">
        <h2>Créer un compte</h2>

        <div class="form-row">
            <div class="input-group">
                <label>Email</label>
                <input type="email" placeholder="ex: aguibou@email.com" name="email" required>
            </div>

            <div class="input-group">
                <label>Numero</label>
                <input type="number" placeholder="123456789" name="numero" required>
            </div>
        </div>

        <div class="form-row">
            <div class="input-group">
                <label>Prenom</label>
                <input type="text" required name="prenom">
            </div>

            <div class="input-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>
        </div>

        <div class="input-group">
            <label>Adresse</label>
            <input type="text" name="adresse" required>
        </div>

        <div class="form-row">
            <div class="input-group">
                <label>Mot de passe</label>
                <input type="password" name="pass" required>
            </div>

            <div class="input-group">
                <label>Confirmer mot de passe</label>
                <input type="password" name="passConfirmin" required>
            </div>
        </div>

        <button type="submit">Inscription</button>

        <p class="auth-link">
            Déjà un compte ?
            <a href="connection.php">Connexion</a>
        </p>

    </form>
</div>
</body>
</html>