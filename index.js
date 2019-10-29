const http = require('http');
const url = require('url');
const sqlite3 = require('sqlite3').verbose();
const express = require("express");
const myParser = require("body-parser");

const port = 80;

const db = new sqlite3.Database('./database.sqlite', (err) => {
    if (err) {
        console.error(err.message);
    } else {
        console.log('Connected to the chinook database.');
    }
});

function runQuery(query) { return db.run(query); }

var app = express();
app.use(myParser.urlencoded({extended:true}));

app.use(express.static("public_html"));

app.post("/cgi-bin/login", (request, response) => {
    const payload = JSON.parse( request.body );
    
});