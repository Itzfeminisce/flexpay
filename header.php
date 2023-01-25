<?php

$rdr = isset($_GET["rdr"]) ?? "";
 if($rdr=="fpid"){
 $url = sanitize($_GET["url"]);
 $cert = sanitize($_GET["rdr"]);
 header("Location: register.php?url=".$url."&cert=".$cert);
 }

$search_form = '
<div class="search-wrapper container" >
<form>
<input onkeyup="checkKeys(event, this);"type="text" name="search-input" placeholder="e.g: Politics, Comedy" id="search">
<button name="search" value="search" >Search</button>
</form>
</div>
<div class="container" id="search-box" >Good <span id="greeting" >day!</span> your search results appears here.</div>

';
 
 $site_name = "Flexpay _Nigerian No.1 site";
 
 function sanitize($var){
 $var = stripslashes($var);
 $var = htmlentities($var);
 $var = trim($var);
 return $var;
 }
 if(isset($_SESSION["em"]) || isset($_SESSION["ph"])){
  $USER_ID = $_SESSION["id"];
 $em = $_SESSION["em"];
 $ph = $_SESSION["ph"];
 $fn = $_SESSION["fn"];
 $ln = $_SESSION["ln"];
 $paid = ($_SESSION["pmc"] == 1) ? true : false;
 $date = $_SESSION["date"];
 $loggedin = true;

 }else{
 $loggedin = false;
 }
 
 ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="viewport" content="width:device-width, initial-scale=1.0, user-scalable=no">
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.css" rel="stylesheet"> -->
<link href=".../../../styles/index.css" rel="stylesheet" >
<link href=".../../../styles/fa/css/all.css" rel="stylesheet" >
<link href="https://fonts.googleapis.com/css?family=Quicksand|Open+Sans&display=swap" rel="stylesheet">