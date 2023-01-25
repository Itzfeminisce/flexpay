<?php declare(strict_types=1);

session_start();
require_once ".../../../config.php";
include ".../../../header.php";

$session_id = $_SESSION["id"];
?>
<title><?php echo "Chat | ".$site_name; ?></title>
<style type="text/css">
.chat-parent {
position:relative;
width:100%; 
max-width:700px;
margin:auto auto 50px auto;
}
.chat-parent div>h4{
margin:10px auto 10px 20px;
padding:5px 2px;
border-bottom:1px solid #aaa;
font-size:medium;
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
.chat-parent .card .card-right .online{
position:absolute;
top:0;
right:10px;
width:10px;
height:10px;
border-radius:100px;
background:#aaa;
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
width:50px;
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
.chat-parent .card .card-right .card-right-placeholder form {
display:flex;
flex-direction:row;
align-items:flex-start;
width:100%;
max-width:200px;
}
.chat-parent .card .card-right .card-right-placeholder form button{
background:rgba(25,25,0,0.2);
padding:5px; 
margin:auto 10px auto 0;
font-weight:bolder;
border-radius:5px;
}
.chat-parent .card .card-right .card-right-placeholder form button .fa{
color:#aaa;
font-size:large;
}
.chat-parent .card .card-right .card-right-placeholder form button:last-of-type {
background:rgba(25,25,0,0.05);
}


.text-box {
position:fixed;
top:-100%;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.2);
z-index:20;
transition:100ms ease-in-out;
text-align:center;
}
.text-box .img{
width:100%;
height:100%;
background:#eee;
}
.text-box .img img{
all:inherit;
}
.text-box .close-modal {
position:absolute;
background:#fff;
padding:2px;
font-weight:bolder;
font-size:large;
width:20px;
height:auto;
top:10px;
right:10px;
border-radius:100px;
}
.text-box .box{
position:absolute;
bottom:0;
left:0;
margin:100% auto 0 auto;
}
.text-box .box .myform form{
display:flex;
flex-direction:row;
align-items:flex-start;
}
.text-box .box .myform form textarea {
width:100%;
height:40px;
border:2px solid $fff;
background:#fff;
border-radius:100px;
margin-right:10px;
padding:10px;
font-size:small;
font-weight:bold;
}
.text-box .box .myform form textarea::-webkit-scrollbar {
display:none;
}
.text-box .box .myform form button {
width:auto;
height:auto;
background:lightgreen;
border-radius:100px;
color:#fff;
margin:0;
font-weight:bolder;
font-size:x-large;
padding:5px 10px;
}
</style>

</head>
<body>
<div style="margin:0 auto 10px auto;" class="container nav" >
<div class="nav-wrapper" >
<button onclick="window.history.go(-1);" class="message" ><i class="fas fa-chevron-left"></i><br>Back</button>
<button onclick="return false; window.location='flexers.php';"class="message" ><i class=""></i><br></button>
<button onclick="return false; window.location='flexers.php';"class="message" ><i class=""></i><br></button>
<button onclick="return false; window.location='.../../../server.php?logout';" class="message" ><i class=""></i><br></button>
<button onclick="return false; window.location='.../../../server.php?logout';" class="message" ><i class=""></i><br></button>
<button onclick="return false; window.location='.../../../server.php?logout';" class="message" ><i class=""></i><br></button>
</div>
</div>
<?php
//...SEARCH BOX CAN GO HERE

$query = $con->query("SELECT user_biodata.id AS card_id,
 user_biodata.fn,
user_biodata.ln,
 user_biodata.profile_pic,
 user_biodata.online AS online,
friends.* 
FROM user_biodata 
JOIN friends 
ON user_biodata.id=friends.friend_id 
WHERE friends.my_id='".$_SESSION["id"]."' 
ORDER BY friends.date DESC");
?>
<div class="chat-parent" >
<div style="" ><h4>Your friends. <?php echo $query->num_rows; ?></h4></div>
<?php
if($query->num_rows > 0):
while($r = $query->fetch_object()):
$my_id = $r->card_id;
$online = ((int)$r->online === 1) ? "lightgreen" : "#aaa";
?>
<div class="card" >
<div class="user-img left" ><img src=".../../../profile/dp/<?php echo $r->profile_pic; ?>" ></div>
<div class="card-right" >
<div style="background:<?php echo $online; ?>;" class="online" ></div>
<div class="card-right-text" ><h2><?php echo $r->fn." ".$r->ln; ?></h2></div>
<div class="card-right-placeholder" >
<form>
<button  onclick="chat_friend(event, this);" value="<?php echo $r->card_id; ?>" class="add btn">
<i class="fa fa-comment" ></i>Chat</button>
</form>
</div>
</div>
</div>
<?php
endwhile;
endif;
if($query->num_rows < 1)
echo ' <div class="" >
 <h1 style="text-align:center;color:black;font-weight:lighter;">You have no friends.</h1>
 </div> ', $con->exit;
 ?>
</div>


<?php 
$query = $con->query("SELECT user_biodata.id AS my_id, user_biodata.fn,
user_biodata.ln, user_biodata.profile_pic FROM user_biodata ORDER BY RAND()");
?>
<div class="chat-parent" >
<div style="" ><h4>Suggestions: <span id="suggestedNumRows" ><?php echo ((int)$query->num_rows - 1); ?></span></h4></div>
<?php
while($r = $query->fetch_object()):
if($r->my_id == $session_id) continue;
?>
<div id="my_card"  class="card" >
<div class="user-img left" ><img src=".../../../profile/dp/<?php echo $r->profile_pic; ?>" ></div>
<div class="card-right" >
<div class="card-right-text" ><h2><?php echo $r->fn." ".$r->ln; ?></h2></div>
<div class="card-right-placeholder" >
<form>
<button onclick="add_friend(event, this);" value="<?php echo $r->my_id; ?>" >Add</button>
<button onclick="dismiss_card(event, this);">Dismiss</button>
</form>
</div>
</div>
</div>

<?php
 endwhile;
?>
</div>


<div id="text_box" class="text-box" >
<div class="img" ><img id="modal_user_pic"  src=".../../../img/loader.gif" ></div>
<div onclick="this.parentElement.style.top='-1000%';" class="close-modal" >&times;</div>
<div class="box container" >
<p>Send a message to <span id="receiver" >user</span></p>
<div class="myform" >
<form>
<input name="user_id"  type="hidden" value="7"  >
<textarea id="chat" name="chat" rows="2" cols="40"  placeholder="Send a polite message" ></textarea>
<button id="send_chat_btn"  type="button" onclick="send_chat(event, this);" value=""><i class="fa fa-paper-plane" ></i></button>
</form>
</div>
</div>
</div>




<?php
include ".../../../footer.php";
?>