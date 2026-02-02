<?php
session_start(); 
require_once "Fonction.php";  

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: connection.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$pass  = $_POST['pass'] ?? '';

if ($email === '' || $pass === '') {
    die("Tous les champs sont obligatoires.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email invalide.");
}

$mysqli = GetMySQLiInstance();

$sql = "SELECT id, prenom, nom, mot_de_passe 
        FROM clients 
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Erreur préparation SQL : " . htmlspecialchars($mysqli->error));
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Email ou mot de passe incorrect.");
}

$user = $result->fetch_assoc();


if (!password_verify($pass, $user['mot_de_passe'])) {
    die("Email ou mot de passe incorrect.");
}


$_SESSION['user_id'] = $user['id'];
$_SESSION['prenom']  = $user['prenom'];
$_SESSION['nom']     = $user['nom'];

$stmt->close();

header("Location: index.php");
exit;
?>