<?php

class connection{

public $host;
public $dbname;
public $dbpass;
public $db;

public function set_connect($host,$dbname,$dbpass,$db){
$this->host = $host;
$this->dbname = $dbname;
$this->dbpass = $dbpass;
$this->db = $db;
}

public function get_connect($use_connection = true){
if($use_connection){
$myCon = mysqli_connect($this->host, $this->dbname, $this->dbpass, $this->db);
if(!$myCon){
die("Error connecting to database");
}else{
return $myCon;
}
}else{
 echo "Connection already established";
}
}
}

class User{

public $id;
public $fetch_data;
public $numRows;


public function fetch_data($con, $query){
$stmt = $con->query($query);
return $stmt;
}

public function numRows($fetched_data){
return $fetched_data->num_rows;
}

public function store_data($con, $query){
$stmt = $con->query($query);
if($stmt){
return $stmt;
}else{
echo "Could not store data: ".$con->error;
}
}

public function profile_pic($pic, $pic_height, $pic_width){
return "<img style='border-radius:100px;' src='.../../../uploads/".$pic."' width='".$pic_width."' height='".$pic_height."'>";
}

public function destroy($con){
$stmt = $con->close();
return $stmt;
}

public function sanitize($con, $var){
$var = $con->real_escape_string($var);
$var = htmlentities($var);
$var = trim($var);
return $var;
}

public function display($result){
echo $result;
}


}



$data = new User;
$con = new connection;

$con->set_connect("localhost","root","","flexpay");

$query = $data->fetch_data($con->get_connect(false), "SELECT * FROM user_biodata");


$rows = $data->numRows($query);

$data::display("<h1>List of Flexpay users</h1>");
$data::display("<hr>");
$data::display("<br>");
$data::display("<br>");
$row = 0;
while($r = $query->fetch_assoc()){
$row = $row + 1;
$data::display("<h1>{Row $row}</h1>");
$data::display("<div style='border:2px solid #000;padding:10px;'>");
$data::display($data::profile_pic($r["profile_pic"], 100, 100)."\n\r");
$data::display("<b>Name:</b> ".$r["fn"]." ".$r["ln"]."\n\r");
$data::display("<hr>");
$data::display("<b>Email:</b> ".$r["em"]."\n\r");
$data::display("<hr>");
$data::display("<b>Password:</b> ".$r["pwd"]."\n\r");
$data::display("<hr>");
$data::display("<b>Phone:</b> ".$r["ph"]."\n\r");
$data::display("<hr>");
$data::display("<b>Mode of Payment: </b>".$r["pm"]."\n\r");
$data::display("<hr>");
$data::display("<b>Refferer: </b>".$r["rf"]."\n\r");
$data::display("<hr>");
$data::display("<b>Referred link: </b>".$r["fprid"]."\n\r");
$data::display("<hr>");
$data::display("<b>Status:</b> ".$r["online"]."\n\r");
$data::display("</div>");




$data::display("<br>");
$data::display("<br>");
$data::display("<br>");
}

?>