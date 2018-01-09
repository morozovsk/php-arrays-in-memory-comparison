<?php

$start = time();

$user = new SplFixedArray(11000000);
$location = new SplFixedArray(11000000);
$mark = new SplFixedArray(11000000);
$visited_at = new SplFixedArray(11000000);

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        $user[$row['id']] = $row['user'];
        $location[$row['id']] = $row['location'];
        $mark[$row['id']] = $row['mark'];
        $visited_at[$row['id']] = $row['visited_at'];
    }
    $i++;echo "$i\n";
}
unset($visitsData);

gc_collect_cycles();

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";
sleep(3600);
