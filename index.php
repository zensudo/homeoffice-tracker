<?php
session_start();
if (!isset($_SESSION['eingeloggt'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Arbeitslogger</title>
  <style>
    body {
      font-family: sans-serif;
      max-width: 800px;
      margin: auto;
      padding: 2em;
      position: relative;
    }
    .logout {
      position: absolute;
      top: 1em;
      right: 1em;
    }
    input, button, select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      font-size: 1em;
    }
    .buttons {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .buttons form {
      flex: 1;
      min-width: 150px;
    }
    .inline-inputs {
      display: flex;
      gap: 10px;
    }
    .inline-inputs input {
      flex: 1;
      margin: 0;
    }
	.small-button {
 	 display: inline-block;
	 text-decoration: none;
 	 background: #eee;
 	 border: 1px solid #ccc;
 	 padding: 6px 12px;
 	 font-size: 0.9em;
	  border-radius: 4px;
	  margin-top: 10px;
	}
  </style>
</head>
<body>

  <div class="logout">
    <a href="logout.php">🔒 Logout</a>
  </div>

  <h1>Arbeitsort eintragen</h1>

  <form action="save.php" method="post">
    <input type="hidden" name="aktion" value="einstellungen">
    <div class="inline-inputs">
      <input type="number" name="arbeitstage" placeholder="Jährliche Arbeitstage" required>
      <input type="number" name="feiertage" placeholder="Jährliche Feiertage" required>
	  <input type="number" name="urlaubstage" placeholder="Jährliche Urlaubstage" required>
    </div>
    <button type="submit">Einstellungen speichern</button>
  </form>

  <div class="buttons">
    <form action="save.php" method="post">
      <input type="hidden" name="aktion" value="eintragen">
      <input type="hidden" name="typ" value="Homeoffice">
      <button type="submit">🏠 Homeoffice</button>
    </form>

    <form action="save.php" method="post">
      <input type="hidden" name="aktion" value="eintragen">
      <input type="hidden" name="typ" value="Remotearbeit">
      <button type="submit">🌐 Remotearbeit</button>
    </form>

    <form action="save.php" method="post">
      <input type="hidden" name="aktion" value="eintragen">
      <input type="hidden" name="typ" value="Krankheit">
      <button type="submit">🩺 Krankheit</button>
    </form>
  </div>

  <div class="top-links">
  <a href="liste.php" class="small-button">→ Zur Liste</a>
  </div>

</body>
</html>
