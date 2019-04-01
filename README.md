### Data
11kk items in 101 json file 1.5MB each. https://github.com/sat2707/hlcupdocs/tree/master/data/FULL/data

schema:
* id — unique id: int32 unsigned
* location — location id: int32 unsigned
* user — user id: int32 unsigned
* visited_at — timestamp.
* mark — int from 0 to 5

Example:<br />
{"visits": [{"user": 34, "location": 6, "visited_at": 1330898799, "id": 1, "mark": 4}, ...]}<br />
$row = ["user" => 34, "location" => 6, "visited_at" => 1330898799, "id" => 1, "mark" => 4];

### Results (on php7 x64):

|   |   |memory, Mb|initialize|filling|
|---|---|---|---|---|
|php|array of objects|6231|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = (object) $row;|
|php|[array (hash)](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/array.php)|6057|$visits = [];|$visits[$row['id']] = $row;|
|php|[SplFixedArray](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/SplFixedArray.php)|5696|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = $row;|
|php|array (index)|3936|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = [$row['user'],...];|
|redis|[mset](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/redis.php)|3354||MSet(["u{$row['id']}" => $row['user'], "l{$row['id']}" => $row['location'], ...])|
|php|[SplFixedArray](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/SplFixedArray.php)|2790|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = new SplFixedArray(4);|
|php|[swoole_table](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/swoole_table.php)|2200|$visits = new swoole_table(11000000);|$visits->set($row['id'], $row);|
|php|[arrays](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/arrays.php)|2147|$user = $location = ... = [];|$user[$row['id']] = $row['user'];$location[$row['id']] = $row['location'];...|
|php|my object|1430|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = new MyArrayClass($row['user'], ...);|
|redis|[hmset](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/redis.php)|1409||hMSet("v{$row['id']}", ['user' => $row['user'], 'location' => $row['location'], ...]);|
|redis|[hmset](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/redis.php)|1225||hMSet("v{$row['id']}", ['u' => $row['user'], 'l' => $row['location'], ...]);|
|node.js|[array](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/node.js)|780|visits = [];|visits[visitsData.visits[y]['id']] = {user:visitsData.visits[y].user,...}|
|php|strings|736|$visits = new SplFixedArray(11000000);|$visits[$row['id']] = join(',', [$row['user'], $row['location'], ...]);|
|php|[SplFixedArrays](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/SplFixedArrays.php)|704|$user = new SplFixedArray(11000000);...|$user[$row['id']] = $row['user'];$location[$row['id']] = $row['location'];...|
|php|[swoole_pack](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/swoole_pack.php)|539|shmop_open($k, "c", 0644, 49*11000000);|swoole_pack(['user' => $row['user'], 'location' => $row['location'], ...])|
|node.js|[arrays](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/node.js)|514|visits_user = []; visits_location = [];...|visits_user[visitsData.visits[y]['id']] = visitsData.visits[y].user;...|
|tarantool|[insert](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/tarantool.php)|507|create_index('primary', {type = 'tree', parts = {1, 'unsigned'}})|insert($row['id'], $row['user'], $row['location'], ...]);|
|php|[swoole_pack](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/swoole_pack.php)|341|shmop_open($k, "c", 0644, 31*11000000);|swoole_pack(['u' => $row['user'], 'l' => $row['location'], ...])|
|php|[swoole_pack](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/swoole_pack.php)|209|shmop_open($k, "c", 0644, 19*11000000);|swoole_pack([$row['user'], $row['location'], $row['visited_at'], $row['mark']]);|
|php|[pack](https://github.com/morozovsk/php-arrays-in-memory-comparison/blob/master/pack.php)|143|shmop_open($k, "c", 0644, 13*11000000);|pack('LLLc', $row['user'], $row['location'], $row['visited_at'], $row['mark']);|

### See also comparison [swoole vs workerman vs roadrunner vs node.js vs fasthttp](https://github.com/morozovsk/webserver-performance-comparison)
