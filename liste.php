<?php
session_start();
if (!isset($_SESSION['eingeloggt'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['loeschen'])) {
        $stmt = $db->prepare("DELETE FROM eintraege WHERE id = ?");
        $stmt->execute([$_POST['loeschen']]);
    }

    if (isset($_POST['bearbeiten'], $_POST['typ'], $_POST['datum'])) {
        $stmt = $db->prepare("UPDATE eintraege SET datum = ?, typ = ? WHERE id = ?");
        $stmt->execute([$_POST['datum'], $_POST['typ'], $_POST['bearbeiten']]);
    }
}

$eintraege = $db->query("SELECT * FROM eintraege ORDER BY datum")->fetchAll(PDO::FETCH_ASSOC);
$einstellungen = $db->query("SELECT * FROM einstellungen WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

$gesamt = $einstellungen['arbeitstage'] ?? 0;
$frei = $einstellungen['feiertage'] ?? 0;
$urlaub = $einstellungen['urlaubstage'] ?? 0;
$anzahl = count($eintraege);
$verbleibend = max(0, $gesamt - $frei - $urlaub - $anzahl);

// Einträge nach Typ zählen
$homeoffice = 0;
$remote = 0;
$krank = 0;

foreach ($eintraege as $e) {
    switch ($e['typ']) {
        case 'Homeoffice': $homeoffice++; break;
        case 'Remotearbeit': $remote++; break;
        case 'Krankheit': $krank++; break;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Übersicht</title>
  <style>
    body {
      font-family: sans-serif;
      max-width: 1200px;
      margin: auto;
      padding: 2em;
      position: relative;
    }
    .logout {
      position: absolute;
      top: 1em;
      right: 1em;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .buttons a {
      text-decoration: none;
      padding: 8px 12px;
      background: #eee;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-left: 8px;
      font-size: 0.9em;
    }
    .content-grid {
      display: flex;
      gap: 40px;
      align-items: flex-start;
    }
    .eintraege {
      flex: 2;
    }
    .infos {
      flex: 1;
      background: #f7f7f7;
      padding: 1em;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .infos p {
      margin: 0.5em 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
    }
    input {
      width: 100px;
    }
  </style>
</head>
<body>

  <div class="logout">
    <a href="logout.php">🔒 Logout</a>
  </div>

  <div class="topbar">
    <h1>Übersicht</h1>
    <div class="buttons">
      <a href="index.php">← Zurück</a>
      <a href="export.php">📄 Export</a>
    </div>
  </div>

  <div class="content-grid">
    <!-- Linke Spalte: Tabelle -->
    <div class="eintraege">
      <table>
        <thead>
          <tr><th>#</th><th>Datum</th><th>Typ</th><th>Aktion</th></tr>
        </thead>
        <tbody>
          <?php foreach ($eintraege as $e): ?>
            <tr>
              <form method="post">
                <td><?= $e['id'] ?></td>
                <td><input name="datum" value="<?= $e['datum'] ?>"></td>
                <td>
                  <select name="typ">
                    <option value="Homeoffice" <?= $e['typ'] === 'Homeoffice' ? 'selected' : '' ?>>Homeoffice</option>
                    <option value="Remotearbeit" <?= $e['typ'] === 'Remotearbeit' ? 'selected' : '' ?>>Remotearbeit</option>
                    <option value="Krankheit" <?= $e['typ'] === 'Krankheit' ? 'selected' : '' ?>>Krankheit</option>
                  </select>
                </td>
                <td>
                  <button name="bearbeiten" value="<?= $e['id'] ?>">💾</button>
                  <button name="loeschen" value="<?= $e['id'] ?>" onclick="return confirm('Wirklich löschen?')">🗑️</button>
                </td>
              </form>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Rechte Spalte: Infos -->
    <br>
	  <div class="infos">
	  <p><strong>Arbeitstage:</strong> <?= $gesamt ?></p>
		  <hr>
      <p><strong>Einträge:</strong> <?= $anzahl ?></p>
      <ul style="list-style: none; padding-left: 0;">
        <li>🏠 Homeoffice: <strong><?= $homeoffice ?></strong></li>
        <li>🌐 Remotearbeit: <strong><?= $remote ?></strong></li>
        <li>🩺 Krankheit: <strong><?= $krank ?></strong></li>
      </ul>
      <p><strong>Feiertage:</strong> <?= $frei ?></p>
      <p><strong>Urlaubstage:</strong> <?= $urlaub ?></p>
		        <hr>
	  <p><strong>Verbleibend:</strong> <?= $verbleibend ?></p>
    </div>
  </div>

</body>
</html>
