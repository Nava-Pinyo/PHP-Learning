<?php
$server = 'mssql-server';
$user = 'sa';
$pass = 'YourStrong!Passw0rd';
$database = 'master';

// เชื่อมฐานข้อมูล MSSQL
$link = mssql_connect($server, $user, $pass);

if (!$link) {
    die('Could not connect to MSSQL: ' . mssql_get_last_message());
}

// เลือก database
if (!mssql_select_db($database, $link)) {
    die('Could not select database: ' . mssql_get_last_message());
}


?>