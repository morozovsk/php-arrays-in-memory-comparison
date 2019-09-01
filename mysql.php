<?php

// mysql 5.8

$start = time();

$pdo = new PDO("mysql:host=localhost;dbname=testdb", 'login', 'password');
$pdo->exec('SET GLOBAL tmp_table_size = 1024 * 1024 * 1024 * 2; SET GLOBAL max_heap_table_size = 1024 * 1024 * 1024 * 2;');

$pdo->exec("CREATE TABLE visits (
  id int NOT NULL PRIMARY KEY,
  user int NOT NULL,
  location int NOT NULL,
  visited_at int NOT NULL,
  mark tinyint NOT NULL
) ENGINE='MEMORY';");

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);

    $sql = "INSERT INTO visits (id, user, location, visited_at, mark) VALUES ";
    foreach ($visitsData['visits'] as $k => $row) {
        if ($k) {
            $sql .= ',';
        }

        $sql .= "({$row['id']}, {$row['user']}, {$row['location']}, {$row['visited_at']}, {$row['mark']})";
    }

    $r = $pdo->exec($sql);
    if (!$r) {
        var_export($pdo->errorInfo());
    }

    echo "$i\n";
    $i++;
}
unset($visitsData);

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";
