<?php

$start = time();

$redis = new Redis();
$redis->pconnect('127.0.0.1', 6379);

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        //first test:
        $redis->MSet(["u{$row['id']}" => $row['user'], "l{$row['id']}" => $row['location'], "m{$row['id']}" => $row['mark'], "v{$row['id']}" => $row['visited_at']]);
        //second test:
        //$redis->hMSet("v{$row['id']}", ['user' => $row['user'], 'location' => $row['location'], 'mark' => $row['mark'], 'visited_at' => $row['visited_at']]);
        //third test:
        //$redis->hMSet("v{$row['id']}", ['u' => $row['user'], 'l' => $row['location'], 'm' => $row['mark'], 'v' => $row['visited_at']]);
    }
    $i++;echo "$i\n";
}
unset($visitsData);

$r = $redis->dbSize();var_export($r);

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";
