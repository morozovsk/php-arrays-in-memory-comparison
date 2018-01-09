<?php

//pecl install swoole

$start = time();

$visits = new swoole_table(11000000);
$visits->column('user', swoole_table::TYPE_INT);
$visits->column('mark', swoole_table::TYPE_INT);
$visits->column('location', swoole_table::TYPE_INT);
$visits->column('visited_at', swoole_table::TYPE_INT);
$visits->create();

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        $visits->set($row['id'], $row);
    }
    $i++;
}
unset($visitsData);

gc_collect_cycles();

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";
sleep(3600);
