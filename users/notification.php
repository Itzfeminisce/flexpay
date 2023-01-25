<?php declare(strict_types=1);

session_start();
require_once ".../../../config.php";
require_once ".../../../header.php";

$session_id = $_SESSION["id"];


$query = $con->query("SELECT DISTINCT user_biodata.id AS my_id, user_biodata.fn,
user_biodata.ln, user_biodata.profile_pic, friend_request.* 
FROM user_biodata 
JOIN friend_request 
ON user_biodata.id=friend_request.from_id
WHERE friend_request.to_id='".$_SESSION["id"]."' 
ORDER BY friend_request.date DESC");


?>
<title><?php echo "Chat | ".$site_name; ?></title>
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
?>
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
transition:500ms ease-in-out;
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
width:60px;
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
margin:auto 15px auto 0;
font-weight:bolder;
border-radius:5px;
}
.chat-parent .card .card-right .card-right-placeholder form button:last-of-type {
background:rgba(25,25,0,0.05);
}
</style>


<div class="chat-parent" >
<div style="" ><h4>Notifications</h4></div>
<?php
if($query->num_rows < 1) 
echo '<div style="background:#eee;" class="container" >
<h1 style="text-align:center;color:grey;font-weight:lighter;">You have no new notifications.</h1>
</div>'; $con->close;


while($r = $query->fetch_assoc()):
$date = date_create($r["date"]);
$date = date_format($date, "D, h:i");
?>
<div id="my_card" class="card" >
<div class="user-img left" ><img src=".../../../profile/dp/<?php echo $r["profile_pic"]; ?>" ></div>
<div class="card-right" >
<div class="card-date" ><p><?php echo $date; ?></p></div>
<div class="card-right-text" ><h2><?php echo $r["fn"]." ".$r["ln"]; ?></h2></div>
<div class="card-right-placeholder" >
<form>
<button onclick="accept_friend(event, this);" value="<?php echo $r["my_id"]; ?>" >Accept</button>
<button onclick="reject_friend(event, this);" value="<?php echo $r["my_id"]; ?>">Reject</button>
</form>
</div>
</div>
</div>
<?php
endwhile;
$con->close;
?>
</div>


<?php
$query = $con->query("SELECT user_biodata.id AS my_id, user_biodata.fn,
user_biodata.ln, user_biodata.profile_pic FROM user_biodata ORDER BY RAND()");
?>
<div class="chat-parent" >
<div style="" ><h4>Suggestions: <span id="suggestedNumRows" ><?php echo ((int)$query->num_rows - 1); ?></span></h4></div>
<?php
while($r = $query->fetch_assoc()):
if($r["my_id"] == $session_id){
continue;
}
?>
<div id="my_card" class="card" >
<div class="user-img left" ><img src=".../../../profile/dp/<?php echo $r["profile_pic"]; ?>" ></div>
<div class="card-right" >
<div class="card-right-text" ><h2><?php echo $r["fn"]." ".$r["ln"]; ?></h2></div>
<div class="card-right-placeholder" >
<form>
<button onclick="add_friend(event, this);" value="<?php echo $r["my_id"]; ?>" >Add</button>
<button onclick="dismiss_card(event, this);" value="" >Dismiss</button>
</form>
</div>
</div>
</div>

<?php
 endwhile;
?>
</div>
<?php
require_once ".../../../footer.php";
?>