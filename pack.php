<?php

pack('LLLc', 'user');

$key = '/var/www/test/visits/shmop.php';

$shm_key = ftok($key, 'v');

$shm_id = shmop_open($shm_key, "c", 0644, 13*11000000);

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        $data = pack('LLLc', $row['user'], $row['location'], $row['visited_at'], $row['mark']);
        shmop_write ($shm_id , $data, $row['id']*13);
    }
    $i++;echo "$i\n";
}
unset($visitsData);

//$data = shmop_read($shm_id , $row['id']*13, 13);
//$data = unpack('Luser/Llocation/Lvisited_at/cmark', $data);

sleep(3600);
