<?php
require 'config.php';
// generate a random integer for the main page
function gen_uid($l=9){
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $l);
}

/* Updating the Database */

function getConnection() {
    global $dbhost, $dbuser, $dbpass, $dbname;
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
