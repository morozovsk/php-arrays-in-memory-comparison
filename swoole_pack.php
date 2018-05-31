<?php

//pecl install swoole

$key = '/var/www/test/visits/shmop.php';

$shm_key = ftok($key, 'v');

//first test:
$shm_id = shmop_open($shm_key, "c", 0644, 49*11000000);
//second test:
//$shm_id = shmop_open($shm_key, "c", 0644, 31*11000000);
//third test:
//$shm_id = shmop_open($shm_key, "c", 0644, 19*11000000);

$i = 1;
while ($visitsData = @file_get_contents("data/visits_$i.json")) {
    $visitsData = json_decode($visitsData, true);
    foreach ($visitsData['visits'] as $k => $row) {
        //first test:
        $data = swoole_pack(['user' => $row['user'], 'location' => $row['location'], 'visited_at' => $row['visited_at'], 'mark' => $row['mark']]);
        shmop_write ($shm_id , $data, $row['id']*49);
        //second test:
        //$data = swoole_pack(['u' => $row['user'], 'l' => $row['location'], 'v' => $row['visited_at'], 'm' => $row['mark']]);
        //shmop_write ($shm_id , $data, $row['id']*31);
        //third test:
        //$data = swoole_pack([$row['user'], $row['location'], $row['visited_at'], $row['mark']]);
        //shmop_write ($shm_id , $data, $row['id']*19);
    }
    $i++;echo "$i\n";
}
unset($visitsData);

//$data = shmop_read($shm_id , $row['id']*13, 13);
//$data = unpack('Luser/Llocation/Lvisited_at/cmark', $data);

sleep(3600);
