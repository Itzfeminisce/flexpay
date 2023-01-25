<?php


include_once "./connection.class.php";


class User extends Connection{

public $fn;
public $ln;
public $em;
public $pwd;
public $fullNname;
public $verified;
public $reg_date;
public $id;
public $ph;
public $referrer;
public $referrer_id;
public $profile_pic;
public $online;
public $select;
public $numRows;





public function set_fn($fn){
$this->fn = $fn;
}




public function set_ln($ln){
$this->ln = $ln;
}




public function set_em($em){
$this->em = $em;
}




public function set_ph($ph){
$this->ph = $ph;
}





public function set_pic($profile_pic, $other_pic = null){
if(empty(sanitize($profile_pic))){
$this->profile_pic = $other_pic;
}else{
$this->profile_pic = $profile_pic;
}
}





public function set_id($id){
$this->id = $id;
}





public function set_online($online){
if((int)$online == 1){
$this->online = "Online";
}else{
$this->online = "Offline";
}
}





public function set_reg_date($reg_date){
$date = date_create($reg_date);
$date = date_format($date, "D d, h:i");
$this->reg_date = $date;
}





public function set_referrer($referrer){
$this->referrer = $referrer;
}





public function set_referrer_id($referrer_id){
$this->referrer_id = $referrer_id;
}






public function set_verified($verified){
if((int)$online == 1){
$this->verified = "Verified";
}else{
$this->verified = "Not Verified";
}
}



public function select_data($con, $query){
/*$query = "SELECT ".$fields." FROM ".$table." ".$conditions;*/
$query = $con->query($query);
if($query->affected_rows < 1){
echo "No rows found";
}else{
$this->numRows = $query->num_rows;
}
}



/*
public function numRows($data){
return $data->num_rows;
}
*/



//sanitize data
function sanitize_data($data){
$data = trim($data);
return $data;
}





public function fn(){
return $this->fn;
}




public function ln(){
return $this->ln;
}




//Full name
public function full_name(){
return $this->fn." ".$this->ln;
}




public function em(){
return $this->em;
}





public function ph(){
return $this->ph;
}





public function online(){
return $this->online;
}




public function pic(){
return $this->profile_pic;
}



public function referrer(){
return $this->referrer;
}




public function referrer_id(){
return $this->referrer_id;
}




public function verified(){
return $this->verified;
}




public function reg_date(){
return $this->reg_date;
}




public function id(){
return $this->id;
}

}


$user = new User;
$user->set_connect("flexpay");
$user->select_data($user->connect(), "SELECT * FROM user_biodata");
echo $user->numRows;

?>