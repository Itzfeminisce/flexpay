<?php declare(strict_types=1);

session_start();
require_once ".../../../config.php";
require_once ".../../../header.php";

if(!isset($_SESSION["ph"]) || !isset($_SESSION["em"]) || !$loggedin){
echo "<script> location ='.../../../server.php?logout'; </script>";
}


?>
<title><?php echo "Flexers | ".$site_name; ?></title>
<style type="text/css">
.live-card-container {
padding:10px;
white-space:nowrap;
overflow-y:hidden;
overflow-x:scroll;
}

.live-card-container::-webkit-scrollbar {
display:none;
}


.live-card-container .card{
position:relative;
display:inline-block;
width:120px;
height:170px;
background:#eee;
border:0.5px solid #aaa;
border-radius:10px;
margin-right:10px;
overflow:hidden;
}

.live-card-container .card img{
width:100%;
height:100%;
}

.live-card-container .card .card-add-btn {
position:absolute;
top:10px;
left:10px;
width:35px;
height:35px;
border-radius:50%;
border:2px solid #aaa;
background:white;
overflow:hidden;
transition:1000ms;
}

.live-card-container .card .card-add-btn.add{
animation:5000ms rotate linear infinite;
}

.live-card-container .card .card-add-btn img{
width:100%;
}

.live-card-container .card .card-name {
position:absolute;
bottom:5px;
left:5px;
width:auto;
max-width:115px;
height:auto;
background:transparent;
border-radius:5px;
}

.live-card-container .card .card-name p{
padding:10px 0;
color:#fff;
white-space:pre-wrap;
text-overflow:wrap;
text-shadow:0px 0px 1.5px #000;
text-align:left;
font-weight:bold;
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

.storyModal {
position:fixed;
width:100%;
height:100%;
bottom:-5000px;
left:0;
transition:100ms;
animation:500ms ease-in-out;
background:rgba(0,0,0,0.5);
z-index:50;
}

.storyModal .closeStoryModal {
position:absolute;
top:10px;
right:0;
padding:5px 50px 5px 10px;
background:navy;
color:white;
font-size:20px;
font-weight:bolder;
text-align:left;
border-radius:50px 0 0 50px;
}
.storyModal .closeStoryModal h4{
/*animation:500ms rotate linear infinite;*/
}
</style>
</head>
<body>
<div style="margin:0 auto 5px auto;" class="container nav" >
<div class="nav-wrapper" >
<div class="go-back" ><a href="javascript: history.go(-1);" ><i class="fas fa-arrow-left" ></i></a></div>
<button style="visibility:hidden;" onclick="window.location='/';" class="message" ><i class="fas fa-university"></i><br>Home</button>
<button style="visibility:hidden;" onclick="window.location='/';" class="message" ><i class="fas fa-university"></i><br>Home</button>
<button onclick="window.location='/';" class="message" ><i class="fas fa-university"></i><br>Home</button>
<button onclick="window.location='.../../../profile.php';" class="message" ><i class="fas fa-user"></i><br>Profile</button>
</div>
</div>

<div class="container" >
<h4>You may also like these:</h4>
<div id="storyContainer" class="live-card-container" >

<div id="addStory" class="card" style="background-image:linear-gradient(to bottom, #fff, #000);"  >
<button onclick="addStory();" class="card-add-btn add" style="border:none;padding:5px;text-align:center;" >
<i class="fa fa-plus" style="color:lightblue;font-size:x-large;"  ></i>
</button>
<div class="card-name" ><p>Add to story</p></div>
</div>

<?php
$query = $con->query("SELECT * FROM user_biodata ORDER BY online DESC");
if($query->num_rows > 0):
while($r = $query->fetch_assoc()):
$img = ($r["profile_pic"] == "")? null :'<img src=".../../../uploads/'.$r["profile_pic"].'">';
$online = ($r["online"] == 1)? "lightgreen":"red";
$card_id = $r["id"];
$you = ($card_id == $_SESSION["id"])? "You" : ucfirst($r["fn"])." ".ucfirst($r["ln"]);
if(empty($r["profile_pic"])) continue;
?>
<div class="card" >
<?php echo "<img src='.../../../profile/dp/".$r["profile_pic"]."'>";?>
<div class="card-add-btn" >
<?php echo "<img src='.../../../profile/dp/".$r["profile_pic"]."'>";?>
</div>
<div class="card-name" ><p><?php echo $you; ?></p></div>
</div>
<?php
endwhile;
endif;
?>
</div>
</div>




<div class="flexers container">
<?php 
$query = $con->query("SELECT user_biodata.* FROM user_biodata");
if($query->num_rows < 1)
echo '<div class="container" >
<h1 style="text-align:center;color:grey;font-weight:lighter;">No users available.</h1>
</div> ', $con->close();

while($r = $query->fetch_assoc()):
$img = ($r["profile_pic"] == "")? null :'<img src=".../../../profile/dp/'.$r["profile_pic"].'">';
$online = ($r["online"] == 1)? "lightgreen":"red";
$card_id = $r["id"];
$you = ($card_id == $_SESSION["id"])? "You" : $r["fn"]." ".$r["ln"];
?>
<div class="user_cont" >
<div style="background:<?php echo $online; ?>;"  class="premium-identifier btn" ></div>
<div class="user_icon" onclick="show_user(event, '<?php echo $r['id']; ?>');" ><?php echo $img;?></div>
<form onsubmit="return false;">
<button onclick="add_friend(event, this);" value="<?php echo $r["id"]; ?>" class="poke btn">
<i class="fa fa-user-plus" ></i>
</button>
<button  onclick="chat_friend(event, this);" value="<?php echo $r['id']; ?>" class="add btn">
<i class="fa fa-comment" ></i>
</button>
<p><?php echo $you; ?></p>
</form>
<p>Lagos</p>
</div>
<?php
endwhile;
?>
</div>














<div class="container" >
<div class="category-wrapper" >
<h1 style="color:red;" >Trending<i class="fa fa-star" ></i>: </h1>
<p><a href="./post/politics/index.html" >Politics</a></p>
<?php
$sql = "SELECT DISTINCT post_desc FROM post ORDER BY id DESC";
$sql = $con->query($sql);
if($sql->num_rows > 0){
foreach($sql as $count => $row){
echo '<p>'.ucfirst($row["post_desc"]).'</p>';
}
}
?>
<p>Education</p>
<p>Comedy</p>
<p>Entertainment</p>
<p>Election</p>
<p><i class="fas fa-spinner" ></i></p>
</div>
</div>

<div class="container" >
<h1 class="sp" >Sponsored</h1>
<?php
$p = $con->query("SELECT post_att FROM post_att ORDER BY RAND() LIMIT 1");
if($p->num_rows > 0):
while($q = $p->fetch_assoc()):
?>
<div class="ads" >
<div class="ads-wrapper"><img src=".../../../uploads/<?php echo $q["post_att"] ?>" ><div class="sp" ><p>Ads</p></div></div>
<?php
endwhile;
endif;
?>
<?php
$p = $con->query("SELECT post_att FROM post_att ORDER BY RAND() LIMIT 1");
if($p->num_rows > 0):
while($q = $p->fetch_assoc()):
?>
<div class="ads-wrapper"><img src=".../../../uploads/<?php echo $q["post_att"] ?>" ><div class="sp" ><p>Ads</p></div></div>
</div>
<?php
endwhile;
endif;
?>
<?php
$p = $con->query("SELECT post_att FROM post_att ORDER BY RAND() LIMIT 1");
if($p->num_rows > 0):
while($q = $p->fetch_assoc()):
?>
<div class="ads" >
<div class="ads-wrapper"><img src=".../../../uploads/<?php echo $q["post_att"] ?>" ><div class="sp" ><p>Ads</p></div></div>
<?php
endwhile;
endif;
?>
<?php
$p = $con->query("SELECT post_att FROM post_att ORDER BY RAND() LIMIT 1");
if($p->num_rows > 0):
while($q = $p->fetch_assoc()):
?>
<div class="ads-wrapper"><img src=".../../../uploads/<?php echo $q["post_att"] ?>" ><div class="sp" ><p>Ads</p></div></div>
</div>
<?php
endwhile;
endif;
?>
</div>


<div id="text_box" class="text-box" >
<div class="img" id=""  ><img id="modal_user_pic"  src=".../../../uploads/femi.jpg" ></div>
<div onclick="this.parentElement.style.top='-100%';" class="close-modal" >&times;</div>
<div class="box container" >
<p>Send a message to Rotimi</p>
<div class="myform" >
<form>
<input name="user_id"  type="hidden" value="7"  >
<textarea id="chat" name="chat" rows="2" cols="40"  placeholder="Send a polite message" ></textarea>
<button id="send_chat_btn"  type="button" onclick="send_chat(event, this);" value=""><i class="fa fa-paper-plane" ></i></button>
</form>
</div>
</div>
</div>

<div onclick="modal_handler(event);" id="modal"></div>
<div id="storyModal"  class="storyModal" >
<div class="closeStoryModal" ><h4>×</h4></div>

<div class="" ></div>


</div>
<?php
include ".../../../footer.php";
?>


<script type="text/javascript">
let card = document.querySelectorAll(".card");
card.onclick(function(){
alert();
});


function addStory(event){
let storyModal = document.querySelector("#storyModal");
storyModal.style.bottom = "0px";
let closeBtn = storyModal.firstElementChild;

closeBtn.onclick = function(){
this.parentElement.style.bottom = "-5000px";
}

}



</script>


