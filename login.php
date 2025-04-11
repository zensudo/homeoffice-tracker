<?php
session_start();

$LOGIN_USER = 'login';
$LOGIN_PASS = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['user'] === $LOGIN_USER && $_POST['pass'] === $LOGIN_PASS) {
        $_SESSION['eingeloggt'] = true;
        header("Location: index.php");
        exit;
    } else {
        $fehler = "Benutzername oder Passwort ist falsch.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body { font-family: sans-serif; max-width: 400px; margin: auto; padding: 2em; }
    input, button { width: 100%; padding: 10px; margin: 10px 0; font-size: 1em; }
    .fehler { color: red; }
  </style>
</head>
<body>
  <h2>Anmeldung</h2>
  <?php if (!empty($fehler)) echo "<p class='fehler'>$fehler</p>"; ?>
  <form method="post">
    <input type="text" name="user" placeholder="Benutzername" required>
    <input type="password" name="pass" placeholder="Passwort" required>
    <button type="submit">Einloggen</button>
  </form>
</body>
</html>
