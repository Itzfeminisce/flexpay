<?php

$sitename = "FlexPay";
$host = "localhost";
$username = "root";
$password = "";
$database = "flexpayonline";


/*
$sitename = "FlexPay";
$host = "localhost";
$username = "id10737375_flexpay";
$password = "flexpay";
$database = "id10737375_flexpay";

*/

$con = mysqli_connect($host, $username, $password);
mysqli_select_db($con, $database) or die("Error selecting database: ".mysqli_error());
$con->set_charset('utf8mb4');
if(!$con){die("Unable to establish connection with the server. ".mysqli_error()); }

if(!isset($_SESSION["id"]) || empty($_SESSION["id"])){
session_start();
}

?>