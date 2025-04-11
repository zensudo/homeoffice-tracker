<?php
$db = new PDO('sqlite:' . __DIR__ . '/db.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS eintraege (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  datum TEXT NOT NULL,
  typ TEXT NOT NULL
)");
$db->exec("CREATE TABLE IF NOT EXISTS einstellungen (
  id INTEGER PRIMARY KEY,
  arbeitstage INTEGER NOT NULL,
  feiertage INTEGER NOT NULL,
  urlaubstage INTEGER DEFAULT 0
)");
$db->exec("INSERT OR IGNORE INTO einstellungen (id, arbeitstage, feiertage) VALUES (1, 220, 10)");
?>
