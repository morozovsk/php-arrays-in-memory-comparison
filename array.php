<?php

$start = time();

$visits = [];

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        $visits[$row['id']] = $row;
    }
    $i++;echo "$i\n";
}
unset($visitsData);

gc_collect_cycles();

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";
sleep(3600);
