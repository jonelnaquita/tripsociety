<?php

$database_username = 'root';
$database_password = '';
$pdo = new PDO('mysql:host=localhost;dbname=tripsociety_db', $database_username, $database_password);

$SMTPEMAIL = 'tripsociety0@gmail.com';
$SMTPPASSWORD = '';
$URL = 'http://localhost/tripsociety_latest';
?>