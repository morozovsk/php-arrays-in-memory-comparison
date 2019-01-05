<?php

$start = time();

$tnt = new Tarantool();
//$tnt->evaluate("");

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);

    foreach ($visitsData['visits'] as $k => $row) {
        $tnt->insert("visits", [$row['id'], $row['user'], $row['location'], $row['mark'], $row['visited_at']]);
    }
    echo "$i\n";
    $i++;
}
unset($visitsData);

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";

#tarantool>
#box.cfg{listen = 3301, memtx_memory = 1024*1024*1024}
#box.schema.user.grant('guest', 'read,write,execute', 'universe')
#s = box.schema.space.create('visits')
#p = s:create_index('primary', {type = 'tree', parts = {1, 'unsigned'}})
#box.cfg
