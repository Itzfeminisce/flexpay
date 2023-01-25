<?php
class Chat{
public $sql;
public $num_rows;
public $fetch;
public $con;
public $host = "localhost";
public $username = "root";
public $password = "";
public $db_name = null;

public function connection($db_name){
mysqli_select_db($db_name);
mysqli_set_charset("UTF-8");
return mysqli_connect($this->host, $this->username, $this->password, $db_name);
}

public function fetch($con){
$sql = $con->query("SELECT * FROM post LIMIT 10");
$f = $sql->fetch_assoc();
foreach($f as $r => $w){
echo $w."<br>";
}
}

}

$con = new Chat();
/*$con->connection("flexpa");*/
$con->fetch($con->connection("flexpay"));

?>