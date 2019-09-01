<?php

// tarantool 1.10
// https://en.wikipedia.org/wiki/Tarantool

require(__DIR__ . '/vendor/autoload.php');

use Tarantool\Client\Client;

$start = time();

$client = Client::fromDefaults();

$client->evaluate("s = box.schema.space.create('visits')");
$client->evaluate("p = s:create_index('primary', {type = 'hash', parts = {1, 'unsigned'}})");

$space = $client->getSpace('visits');

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);

    foreach ($visitsData['visits'] as $k => $row) {
        $space->insert([$row['id'], $row['user'], $row['location'], $row['mark'], $row['visited_at']]);
    }
    echo "$i\n";
    $i++;
}
unset($visitsData);

echo 'init time: ' . (time() - $start) . ', memory: ' . intval(memory_get_usage() / 1000000) . "\n";


# /etc/tarantool/instances.available/example.lua:
# memtx_memory = 1024 * 1024 * 1024;
# memtx_max_tuple_size = 1024 * 1024 * 1024;
# tarantoolctl start example
