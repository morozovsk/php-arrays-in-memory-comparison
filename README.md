### Data
11kk items in 101 json file 1.5MB each. https://github.com/sat2707/hlcupdocs/tree/master/data/FULL/data

schema:
* id — unique id посещения: int32 unsigned
* location — loaction id: int32 unsigned
* user — user id: int32 unsigned
* visited_at — timestamp.
* mark — int from 0 to 5

Example: {"visits": [{"user": 34, "location": 6, "visited_at": 1330898799, "id": 1, "mark": 4}, ...]}

|   |   |memory, Mb|initialize|filling|
|---|---|---|---|---|
|php|array of objects|6231|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = (object) $row;|
|php|[array (hash)](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/array.php)|6057|$visits = [];|$visits[$row['id']] = $row;|
|php|SplFixedArray|5696|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = $row;|
|php|array (index)|3936|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = [$row['user'],...];|
|redis|[mset](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/redis.php)|3354||MSet(["u{$row['id']}" => $row['user'], "l{$row['id']}" => $row['location'], ...])|
|php|SplFixedArray|2790|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = new SplFixedArray(4);|
|php|swoole_table|2200|$visits = new swoole_table(11000000);|$visits->set($row['id'], $row);|
|php|[arrays](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/arrays.php)|2147|$user = $location = ... = [];|$user[$row['id']] = $row['user'];$location[$row['id']] = $row['location'];...|
|php|my object|1430|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = new MyArrayClass($row['user'], ...);|
|redis|[hmset](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/redis.php)|1409||hMSet("v{$row['id']}", ['user' => $row['user'], 'location' => $row['location'], ...]);|
|redis|[hmset](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/redis.php)|1225||hMSet("v{$row['id']}", ['u' => $row['user'], 'l' => $row['location'], ...]);|
|[node.js](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/node.js)|array|780|visits = [];|visits[visitsData.visits[y]['id']] = {user:visitsData.visits[y].user,...}|
|php|strings|736|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = join(',', [$row['user'], $row['location'], ...]);|
|php|SplFixedArrays|704|$user = new SplFixedArray(11000000);...|$user[$row['id']] = $row['user'];$location[$row['id']] = $row['location'];...|
|php|swoole_pack|539|shmop_open($k, "c", 0644, 49*11000000);|swoole_pack(['user' => $row['user'], 'location' => $row['location'], ...])|
|[node.js](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/node.js)|arrays|514|visits_user = []; visits_location = [];...|visits_user[visitsData.visits[y]['id']] = visitsData.visits[y].user;...|
|php|swoole_pack|341|shmop_open($k, "c", 0644, 31*11000000);|swoole_pack(['u' => $row['user'], 'l' => $row['location'], ...])|
|php|swoole_pack|209|shmop_open($k, "c", 0644, 19*11000000);|swoole_pack([$row['user'], $row['location'], $row['visited_at'], $row['mark']]);|
|php|pack|143|shmop_open($k, "c", 0644, 13*11000000);|pack('LLLc', $row['user'], $row['location'], $row['visited_at'], $row['mark']);|
