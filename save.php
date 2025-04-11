<?php
session_start();
if (!isset($_SESSION['eingeloggt'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aktion = $_POST['aktion'] ?? '';

    // Eintrag speichern
    if ($aktion === 'eintragen' && isset($_POST['typ'])) {
        $typ = $_POST['typ']; // z. B. Homeoffice, Remotearbeit, Krankheit
        $datum = date('Y-m-d');
        $stmt = $db->prepare("INSERT INTO eintraege (datum, typ) VALUES (?, ?)");
        $stmt->execute([$datum, $typ]);
        header("Location: liste.php");
        exit;
    }

    // Einstellungen speichern (inkl. Urlaub)
    if (
        $aktion === 'einstellungen' &&
        isset($_POST['arbeitstage'], $_POST['feiertage'], $_POST['urlaubstage'])
    ) {
        $stmt = $db->prepare("UPDATE einstellungen SET arbeitstage=?, feiertage=?, urlaubstage=? WHERE id=1");
        $stmt->execute([
            intval($_POST['arbeitstage']),
            intval($_POST['feiertage']),
            intval($_POST['urlaubstage'])
        ]);
        header("Location: liste.php");
        exit;
    }
}
?>
