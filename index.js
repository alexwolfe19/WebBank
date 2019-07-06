const http = require('http');
const url = require('url');
const sqlite3 = require('sqlite3').verbose();

const port = 80;

const db = new sqlite3.Database('./database.sqlite', (err) => {
    if (err) {
        console.error(err.message);
    } else {
        console.log('Connected to the chinook database.');
    }
});

function runQuery(query) { return db.run(query); }

function onConnection(request, response) {
    const path = request.url;
    const arguments = url.parse(request.url, true).query;

    

}

http.createServer(onConnection).listen(port);