<?php declare(strict_types=1);

session_start();
require_once ".../../../config.php";
require_once ".../../../header.php";

$session_id = $_SESSION["id"];

$no_of_friends = $con->query("SELECT friends.*, user_biodata.online FROM friends JOIN user_biodata ON friends.friend_id=user_biodata.id WHERE friends.my_id='".$session_id."' AND user_biodata.online=1");
$no_of_friends = ($no_of_friends->num_rows >= 1) ? $no_of_friends->num_rows : null ;

$no_of_notifications = $con->query("SELECT * FROM friend_request WHERE to_id='".$session_id."'");
$no_of_notifications = ($no_of_notifications->num_rows >= 1) ? '<span class="notifier" ></span>' : null;

?>
<title><?php echo "Chat | ".$site_name; ?></title>
</head>
<body>
<div style="margin:0 auto 10px auto;" class="container nav" >
<div class="nav-wrapper" >
<button onclick="window.location='.../../../profile.php';" class="message" ><i class="fas fa-user"></i><br>Profile</button>
<button onclick="window.location='flexers.php';"class="message" ><i class="fas fa-user-plus"></i><br>Meet</button>
<button onclick="window.location='friends.php';"class="message" ><i class="fas fa-users"></i><br>Friends<span style="top:-5px;right:10px;background:transparent;" class="notifier" ><?php echo $no_of_friends; ?></span></button>
<button onclick="window.location='notification.php';"class="message" ><i class="fas fa-bell"></i><br>Notification<?php echo $no_of_notifications; ?></button>
<button onclick="window.location='.../../../server.php?logout';" class="message" ><i class="fas fa-chevron-right"></i><br>Signout</button>
</div>
</div>
<?php
//...SEARCH BOX CAN GO HERE
?>
<style type="text/css">
.chat-parent {
position:relative;
width:100%; 
max-width:700px;
margin:auto;
}
.chat-parent .card{
display:flex;
flex-direction:row;
align-content:flex-start;
align-items:flex-start;
margin:auto auto 10px auto;
padding:10px;
height:auto;
width:100%;
max-height:100px;
}
.chat-parent .card .user-img{
width:70px;
height:70px;
max-height:100px;
background:#aaa;
border-radius:100px;
margin:0px 10px auto 1px;
}
.chat-parent .card .user-img img{
all:inherit;
padding:0;
margin:0;
}
.chat-parent .card .card-right{
position:relative;
width:100%;
height:70px;
padding:5px;
border-bottom:1px solid #aaa;
}

.chat-parent .card .card-right .card-text-no{
position:absolute;
top:0;
right:0;
width:20px;
height:20px;
}
.chat-parent .card .card-right .card-text-no p{
font-weight:bolder;
color:#fff;
padding:5px;
background:green;
text-align:center;
border-radius:100px;
}
.chat-parent .card .card-right .card-date {
position:absolute;
bottom:0;
right:5px;
width:70px;
height:20px;
text-align:right;
}
.chat-parent .card .card-right .card-date {
font-weight:bolder;
}
.chat-parent .card .card-right .card-right-text {
width:100%;
max-width:200px;
height:20px;
margin-bottom:5px;
overflow:hidden;
}
.chat-parent .card .card-right .card-right-placeholder  {
width:100%;
max-width:210px;
height:auto;
overflow:hidden;
}
.chat-parent .card .card-right .card-right-placeholder h3{
font-weight:normal;
}
</style>



<?php

$query = $con->query("SELECT DISTINCT
user_biodata.id AS card_id,
user_biodata.fn,
user_biodata.ln,
user_biodata.profile_pic, 
tmp_chat.*
FROM user_biodata 
JOIN tmp_chat 
ON user_biodata.id=tmp_chat.to_tmp_id
WHERE tmp_chat.to_tmp_id='{$session_id}' 
OR tmp_chat.from_tmp_id='{$session_id}'
ORDER BY tmp_chat.tmp_date 
DESC");
if($query->num_rows > 0){
while($r = $query->fetch_assoc()){
/*if($r["my_id"] == $session_id){
continue;
}*/
$query_ = $con->query("SELECT id, fn, ln FROM user_biodata WHERE id = '{$r['sender_id']}'");
$sender_ = $query_->fetch_assoc();
$sender_name = $sender_["fn"];
$sender_id = $sender_["id"];


$query__ = $con->query("SELECT chat_tmp_chat FROM tmp_chat WHERE sender_id='{$sender_id}'");
$a_ = $query__->fetch_assoc();
$last_sent_or_received_text = $a_["chat_tmp_chat"];


$date = date_create($r["tmp_date"]);
$date = date_format($date, "D, h:i");
$time = substr($date, 4);
$today = (substr($date, 0, 3) == date("D"))? "Today, ".$time : $date;

$sender = ($session_id == $r["sender_id"]) ? "You" : $sender_name;
/*$s_r_id = ($session_id == $r["card_id"]) ? $sender_id : $session_id;*/
$full_name =  $r["fn"]." ".$r["ln"] ;






$card = '<div id="chat_parent" class="chat-parent" >';
$card .= '<a href="messages.php?c=true&i='.$r["card_id"].'#d='.md5($r["to_tmp_id"]).'=true" >';
$card .= '<div class="card" >';
$card .= '<div class="user-img left" ><img src=".../../../uploads/'.$r["profile_pic"].'" ></div>';
$card .= '<div class="card-right" >';
$card .= '<div class="card-date" ><p>'.$today.'</p></div>';
$card .= '<div class="card-right-text" ><h2>'.$full_name.'</h2></div>';
$card .= '<div class="card-right-placeholder" ><h3><b>'.$sender.":</b>&nbsp;".substr($last_sent_or_received_text, 0, 70).'...</h3></div>';
$card .= '<div class="card-text-no" ><p>5</p></div>';
$card .= '</div></div></a></div>';


echo $card;

}

}else{
echo '<div class="container"><h4>No messages</h4></div> ';
}

?>






<?php require_once ".../../../footer.php";?>
<!--
<script type="text/javascript">
function fetch_users_chat_one(session_id){
$.ajax({
url:"./chat.server.inc.php",
type:"POST",
dataType:"JSON",
data:{action:"fetch_chat",from_id:session_id},
success:function(data){
//...What do do with data
/*alert(data);*/
/*alert(chat_parent.nodeName);*/
let chat_parent = document.querySelector("#chat_parent");

chat_parent.innerHTML = data;

},
error:function(jxHQR, textStatus){
alert("An error occured: "+textStatus);
}
});
}

fetch_users_chat_one('<?php echo $session_id; ?>');
</script>-->