<?php
require_once "Fonction.php";   

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inscription_form.php"); 
    exit;
}

$email       = trim($_POST['email'] ?? '');
$numero      = trim($_POST['numero'] ?? '');
$prenom      = trim($_POST['prenom'] ?? '');
$nom         = trim($_POST['nom'] ?? '');
$adresse     = trim($_POST['adresse'] ?? '');
$pass        = $_POST['pass'] ?? '';
$passConfirm = $_POST['passConfirmin'] ?? '';


if ($email === '' || $numero === '' || $prenom === '' || $nom === '' || $adresse === '' || $pass === '' || $passConfirm === '') {
    die("Tous les champs sont obligatoires.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email invalide.");
}

if ($pass !== $passConfirm) {
    die("Les mots de passe ne correspondent pas.");
}


$passHash = password_hash($pass, PASSWORD_DEFAULT);

$mysqli = GetMySQLiInstance();

// (Optionnel mais très utile) : vérifier si email existe déjà
$check = $mysqli->prepare("SELECT id FROM clients WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    die("Cet email est déjà utilisé.");
}
$check->close();

// INSERT
$sql = "INSERT INTO clients (email, numero, prenom, nom, adresse, mot_de_passe)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Erreur préparation SQL: " . htmlspecialchars($mysqli->error));
}

$stmt->bind_param("ssssss", $email, $numero, $prenom, $nom, $adresse, $passHash);

if (!$stmt->execute()) {
    die("Erreur lors de l'ajout : " . htmlspecialchars($stmt->error));
}

$stmt->close();

// Redirection après succès
header("Location: index.php");
exit;
