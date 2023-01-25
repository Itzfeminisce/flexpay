<?php
session_start();
require_once ".../../../config.php";

if(isset($_POST["action"])){
$chat  = $_POST["action"];
if($chat == "fetch_chat"){
$to_id = $_POST["to_id"];
$from_id = $_POST["from_id"];
$card = '';
$response = array();

$query = $con->query("SELECT 
user_biodata.id,
user_biodata.fn,
user_biodata.ln,
user_biodata.profile_pic, 
tmp_chat.*
FROM user_biodata 
JOIN tmp_chat 
ON user_biodata.id=tmp_chat.from_tmp_id
WHERE tmp_chat.to_tmp_id='{$from_id}' 
OR tmp_chat.from_tmp_id='{$from_id}'
ORDER BY tmp_chat.tmp_date 
DESC");
if($query->num_rows > 0){
while($r = $query->fetch_assoc()){
/*if($r["my_id"] == $session_id){
continue;
}*/
$date = date_create($r["tmp_date"]);
$date = date_format($date, "D, h:i");
$time = substr($date, 4);
$today = (substr($date, 0, 3) == date("D"))? "Today, ".$time : $date;

$card = '<div id="chat_part" class="chat-parent" >';
$card .= '<a href="messages.php?c=true&i='.$r["to_tmp_id"].'#d='.md5($r["to_tmp_id"]).'=true" >';
$card .= '<div class="card" >';
$card .= '<div class="user-img left" ><img src=".../../../uploads/'.$r["profile_pic"].'" ></div>';
$card .= '<div class="card-right" >';
$card .= '<div class="card-date" ><p>'.$today.'</p></div>';
$card .= '<div class="card-right-text" ><h2>'.$r["fn"]." ".$r["ln"].'</h2></div>';
$card .= '<div class="card-right-placeholder" ><h3>'.substr($r["chat_tmp_chat"], 0, 70).'...</h3></div>';
$card .= '<div class="card-text-no" ><p>5</p></div>';
$card .= '</div></div></a></div>';


echo json_encode($card);

}
}
}
$con->close();
}

?>