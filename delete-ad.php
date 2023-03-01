<?php
session_start();
include("connection.php");
include("functions.php");
$user_data = check_login_and_redirect($con);
$advert_id = $_GET['advert_id'];
echo "you are gonna delete ad nr: $advert_id";
