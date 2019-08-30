<?php

$start = time();

$pdo = new PDO("sqlite::memory:");

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE visits (
  id int NOT NULL PRIMARY KEY,
  user int NOT NULL,
  location int NOT NULL,
  visited_at int NOT NULL,
  mark tinyint NOT NULL
)");

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        $sql = "INSERT INTO visits (id, user, location, visited_at, mark) VALUES ({$row['id']}, {$row['user']}, {$row['location']}, {$row['mark']}, {$row['visited_at']})";
        $pdo->exec($sql);
    }

    echo "$i\n";
    $i++;
}
unset($visitsData);

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";

//$count = $pdo->query("SELECT count(*) FROM visits")->fetch(); var_export($count);

sleep(3600);