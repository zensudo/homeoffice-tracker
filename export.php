<?php
session_start();
if (!isset($_SESSION['eingeloggt'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

// === CSV 1: Einträge ===
$eintraege = $db->query("SELECT datum, typ FROM eintraege ORDER BY datum")->fetchAll(PDO::FETCH_ASSOC);
$csv1 = fopen("eintraege.csv", "w");
fputcsv($csv1, ["Datum", "Typ"]);

foreach ($eintraege as $row) {
    fputcsv($csv1, [$row['datum'], $row['typ']]);
}
fclose($csv1);

// === Zähle Einträge pro Typ ===
$stat = [
    "Homeoffice" => 0,
    "Remotearbeit" => 0,
    "Krankheit" => 0,
];

foreach ($eintraege as $e) {
    if (isset($stat[$e['typ']])) {
        $stat[$e['typ']]++;
    }
}

// === Einstellungen holen ===
$einstellungen = $db->query("SELECT * FROM einstellungen WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$gesamt = $einstellungen['arbeitstage'] ?? 0;
$frei = $einstellungen['feiertage'] ?? 0;
$urlaub = $einstellungen['urlaubstage'] ?? 0;
$gesamtEintraege = count($eintraege);
$verbleibend = max(0, $gesamt - $frei - $urlaub - $gesamtEintraege);

// === CSV 2: Summen ===
$csv2 = fopen("summen.csv", "w");
fputcsv($csv2, ["Kategorie", "Wert"]);

foreach ($stat as $typ => $anzahl) {
    fputcsv($csv2, [$typ, $anzahl]);
}

fputcsv($csv2, ["Arbeitstage", $gesamt]);
fputcsv($csv2, ["Feiertage", $frei]);
fputcsv($csv2, ["Urlaubstage", $urlaub]);
fputcsv($csv2, ["Verbleibend", $verbleibend]);

fclose($csv2);

// === Ausgabe ===
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>CSV Export</title>
</head>
<body>
  <h2>CSV-Dateien erstellt</h2>
  <ul>
    <li><a href="eintraege.csv" download>📄 Einträge herunterladen</a></li>
    <li><a href="summen.csv" download>📊 Summen herunterladen</a></li>
  </ul>
  <p><a href="liste.php">← Zurück zur Übersicht</a></p>
</body>
</html>
