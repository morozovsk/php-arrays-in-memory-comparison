const fs = require('fs');
const start = new Date();

//firs test:
global.visits = [];

//second test:
//global.visits_user = []; global.visits_location = []; global.visits_mark = []; global.visits_visited_at = [];

let i = 1; let visitsData;

global.gc();

//console.log("memory usage: " + parseInt(process.memoryUsage().heapTotal/1000000));

while (fs.existsSync(`data/visits_${i}.json`) && (visitsData = JSON.parse(fs.readFileSync(`data/visits_${i}.json`, 'utf8')))) {
    for (y = 0; y < visitsData.visits.length; y++) {
        visits[visitsData.visits[y]['id']] = visitsData.visits[y];

        //second test:
        //global.visits_user[visitsData.visits[y]['id']] = visitsData.visits[y].user;
        //global.visits_location[visitsData.visits[y]['id']] = visitsData.visits[y].location;
        //global.visits_mark[visitsData.visits[y]['id']] = visitsData.visits[y].mark;
        //global.visits_visited_at[visitsData.visits[y]['id']] = visitsData.visits[y].visited_at;
    }
    i++;
}

global.gc();

console.log("memory usage: " + parseInt(process.memoryUsage().heapTotal/1000000));
console.log('init time: ' + ((new Date()).getUTCSeconds() - start.getUTCSeconds()));

//nodejs --max_old_space_size=4000 --expose-gc index.js
