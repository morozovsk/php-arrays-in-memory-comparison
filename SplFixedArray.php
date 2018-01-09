<?php

$start = time();

$visits = new SplFixedArray(11000000);

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        //first test:
        $visits[$row['id']] = $row;
        //second test:
        //$visits[$row['id']] = new SplFixedArray(4);
        //$visits[$row['id']][0] = $row['user'];
        //$visits[$row['id']][1] = $row['location'];
        //$visits[$row['id']][2] = $row['visited_at'];
        //$visits[$row['id']][3] = $row['mark'];
    }
    $i++;echo "$i\n";
}
unset($visitsData);

gc_collect_cycles();

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";
sleep(3600);
