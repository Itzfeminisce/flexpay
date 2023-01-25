<?php

class Connection{

public $con;
public $host;
public $uname;
public $pwd;
public $db;
public $connect;
/*
* Set connection parameters
*/
public function set_connect($db){
$this->db = $db;
$this->host = "localhost";
$this->uname = "root";
$this->pwd = "";
}

/*
* Get/establish connection
*/
public function get_connect(){
$con = mysqli_connect($this->host, $this->unname, $this->pwd);
$con->select_db($this->db);
if(!$con){
echo "Filed to connect to database".$this->con->error;
}else{
$this->connect = $con;
}
}

}

$now = date("H");
$date = new DateTime("00:00:00");
$date->modify("+24 hours");
echo $date->format("H");
echo "<br>";
echo $now;



?>