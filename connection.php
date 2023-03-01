<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "reiiprak_v2";
if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)) {
    die("failed to connect!");
}
