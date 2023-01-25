<?php declare(strict_types=1);

session_start();

require_once "config.php";
require_once "header.php";

if(!isset($_SESSION["ph"]) || !isset($_SESSION["em"]) || !$loggedin){
echo "<script> location ='.../../../server.php?logout'; </script>";
}


$mined_earning = 0;
$ref_earning = 0;
$with_bal = 0;
$wallet = 0;
$total = 0;
$activity = 0;
$posted_articles = 0;
$no_of_ref = 0;

$sql = "SELECT * FROM post WHERE user_id='".$_SESSION["id"]."'" ;
$sql = mysqli_query($con, $sql);
$posted_articles = mysqli_num_rows($sql);
mysqli_free_result($sql);


$sql = "SELECT * FROM all_earning WHERE user='".$_SESSION["id"]."'" ;
$sql = mysqli_query($con, $sql);
if(mysqli_num_rows($sql) > 0){
while($row = mysqli_fetch_assoc($sql)){
$mined_earning = $row["fc"];
$ref_earning = $row["ref"];
$with_bal = $row["with_bal"];
$wallet = $row["wallet"];
$total = $row["total"];
$activity = $row["activity"];
}
}
mysqli_free_result($sql);

$ref = "SELECT * FROM referral WHERE referral_id='".$_SESSION["id"]."'" ;
$ref = mysqli_query($con, $ref);
$no_of_ref = mysqli_num_rows($ref);
mysqli_free_result($ref);



$stmt = $con->query("SELECT * FROM user_biodata WHERE id='".$_SESSION["id"]."'");
$data = $stmt->fetch_object();


$query = "SELECT user_biodata.id, user_biodata.fn, 
						     user_biodata.ln,
							 user_biodata.profile_pic,
							 post.id,
							 post.user_id,
							 post.post_desc,
							 post.post_cont,
							 post.post_p_att,
							 DATE_FORMAT(post.date, '%a, %D at %h:%i') as date
							 FROM user_biodata JOIN
							 post ON user_biodata.id=post.user_id 
							 WHERE user_biodata.id=".$_SESSION["id"]." ORDER BY
							 post.id DESC
						";
$query = $con->query($query);


?>
<title><?php echo $site_name." | Profile "; ?></title>
<style type="text/css">
#profileUploadModal {
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.3);
z-index:10;
display:none;
transition:500ms;
}

.uploadContainer{
position:absolute;
bottom:0;
width:100%;
height:auto;
padding:10px;
background:white;
color:black;
margin:0 auto;
}
.uploadContainer button{
text-align:left;
display:block;
}
.uploadContainer button:first-of-type{
border-bottom:1px solid #eee;
background:purple;
color:white;
}
.uploadContainer button:nth-of-type(2){
border-bottom:1px solid #eee;
background:lightblue;
color:white;
}
.uploadContainer button  .fa{
padding:10px;
font-size:large;
}

</style>


</head>
<body>
<div style="margin:0 auto 5px auto;" class="container nav" >
<div class="nav-wrapper" >
<div class="go-back" ><a href="javascript: history.go(-1);" ><i class="fas fa-arrow-left" ></i></a></div>
<button onclick="window.location='/';" class="message" ><i class="fas fa-university"></i><br>Home</button>
<button onclick="window.location='.../../../users/flexers.php';"class="message" ><i class="fas fa-users"></i><br>Meet</button>
<button onclick="window.location='.../../../users/chat.php';" class="message" ><i class="fas fa-envelope"></i><br>Chat(<span style="color:red;font-weight:bolder;" >5</span>)</button>
</div>
</div>


<div class="alert info" >
<span onclick="closeAlert();" class="close" >×</span>
<p>Please <b><a href="/profile.manager.php#accountActivation" >activate your account </a></b>as 
soon as possible to avoid loosing your funds.
</p>
</div>


<div class="container">
<div class="change_notificator" ><p>You just got 1fc, congrats!</p></div>
<br><br>
<div style="
position:relative;
margin:auto;
width:100%;
height:250px;
border-radius:20px 20px 0 0;
background:#aaa;
z-index:1;
"  class="coverPhoto" >
<?php if($paid === true): ?>
<img style="
position:absolute;
top:10px;
right:10px;
width:20px;
height:20px;
text-align:center;
border-radius:50%;
"  src=".../../../img/done.png" >
<?php endif; ?>
<div class="user-icon" style="
position:absolute;
bottom:-75px;
border:4px solid #fff;
left:23%;
margin:auto;
z-index:2;
" >
<div class="upload-btn" >
<form action="server.php" enctype="multipart/form-data"  >
<i onclick="listener(this);" class="fa fa-camera" id="camera" ></i>
<input type="file" name="upload" id="upload" multiple="multiple"  >
</form>
</div>
<img id="profile_image" border="none" alt=" " src=".../../../profile/dp/<?php echo $data->profile_pic;?>" >
<img>
</div>
<img id="cover_image" style="width:100%;height:100%; border-radius:inherit;" src=".../../../profile/cover/<?php echo $data->cover_pic;?>" >
</div>

<br><br><br>
<br><br>
<!--<h4 style="text-align:center;color:green;" id="response" ></h4>-->
<h3 class="user-name" ><?php echo $data->fn." ".$data->ln; ?></h3>
<div id="profileStatusDiv" >This is my status</div>
<textarea id="profileStatus" value="<?php echo $status; ?>" placeholder="Tap here to update status!" ></textarea>
<hr style="margin:10px;opacity:0.4;" >


<div style="text-align:center;"  class="coin container" >
<h4 style="text-align:center;font-size:large;" ><i class="fa fa-scale" ></i><span id="flex-generated">00.00</span> FC</h4>
<p style="font-weight:bold;">Widthrawable FlexCoin = <span id="withdrawableCoin" >100.00</span></p>
</div>



<div class="user-menu" onclick="collapse(this);" onmouseover="collapse(this);">
<p>Wallet(#): <span><?php echo($wallet); ?></span></p>
<p>Activity Earnings(#): <span><?php echo($activity); ?></span></p>
<p class="dl" >Number of Referrals: <span><?php echo($no_of_ref); ?></span></p>
<p class="dl" >Referral Earning: <span><?php echo $ref_earning; ?></span></p>
<p class="dl" >Posted Articles: <span><?php echo($posted_articles); ?></span></p>
</div>
</div>


<div class="container">
<div id="actn_cont" >
<div class="stg-st">
<table cellspacing="10" cellpadding="10" >
<tr>
<th id="0" ><a onclick=""  href="profile.manager.php" ><i class="fas fa-wrench" ></i></a></th>
<th id="1" ><a href="post/sponsored-post.php" ><i class="fas fa-quote-left" ></i></a></th>
<th id="2" ><a onclick="operation_handler(this);"  href="#" ><i class="fa fa-random" ></i></a></th>
<th id="3" ><a onclick="operation_handler(this);" href="#" ><i class="fa fa-user" ></i></a></th>
<th id="4" ><a onclick="operation_handler(this);"href="#" ><i class="fa fa-comments" ></i></a></th>

<th id="5" ><a href="#" ><i class="fas fa-credit-card" ></i></a></th>
<th id="6" ><a onclick="operation_handler(this);" href="#" ><i class="fas fa-shopping-bag" ></i></a></th>
<th id="7" ><a href="#" ><i class="fa fa-users" ></i></a></th>
<th id="8" ><a href="#" ><i class="fa fa-balance-scale" ></i></a></th>
<th id="9" ><a href="#" ><i class="fa fa-trash" ></i></a></th>
<th id="10" ><a onclick="style_button(this);"href="server.php?logout" name="logout" ><i class="fa fa-share" ></i></a></th>
</tr>
<tr>
<td>Edit profile</td>
<td>Sponsored posts</td>
<td>Change password</td>
<td>Referral ID</td>
<td>Create Posts</td>

<td>Withdraw cash</td>
<td>Trade flexCoin</td>
<td>Forum</td>
<td>Upgrade Account</td>
<td>Delete Account</td>
<td>Logout</td>
</tr>
</table>
</div>
</div>
</div>


<?php
if($query->num_rows == 0) 
echo '<div class="container" >
<h1 style="text-align:center;color:grey;font-weight:lighter;">
You have no posts available.
</h1></div>', $con->close;


while($q = $query->fetch_assoc()):
$at_from_post = $q["post_p_att"];
$upvote = $con->query("SELECT upvote FROM post_upvotes WHERE post_id='".$q["id"]."'");
$upvote = $upvote->num_rows;


$comment = $con->query("SELECT post_id FROM comments WHERE post_id='".$q["id"]."'");
$comment =$comment->num_rows;

$post_att = $con->query("SELECT post_att FROM post_att WHERE post_id='".$q["id"]."'");
$post_att = ($at_from_post != "") ? $post_att->num_rows :0;

$p_att = ($q["post_p_att"] == "") ? "" : '<img src=".../../../uploads/'.$q["post_p_att"].'">';
?>
<a href=".../../../post/now.php?pid=<?php echo $q["id"]; ?>">
<div style="width:100%;border-radius:0;margin:10px auto;box-shadow:0px 0px 2px #aaa;" class="user_modal" >
<h5>You <b>shared</b> this post</h5>
<hr style="opacity:0.1;" >
<div class="modal_header" >
<div class="modal_user_icon" ><img src=".../../../profile/dp/<?php echo $q["profile_pic"]; ?>" ></div>
<div class="modal_user_info" >
<p><?php echo $q["fn"]." ".$q["ln"]; ?></p>
<p><?php echo $q["date"]; ?></p>
<span><?php echo $q["post_desc"]; ?></span>
</div>
</div>
<div class="modal_body" >
<div class="modal_body_parag" ><p><?php echo html_entity_decode(substr($q["post_cont"],0, 100)); ?>...•<span style="color:#aaa;">Read more</span></p></div>
<div class="post_att" ><?php echo $p_att; ?></div>
<div class="post_att_no" ><p>Photos•<?php echo($post_att) ?></p></div>
</div>
<div class="modal_footer" >
<button ><i onclick="style_button(this); return false;" class="fa fa-heart">Likes•<?php echo($upvote); ?></i></button>
<button ><i onclick="style_button(this); return false;" class="fa fa-comment">Comments•<?php echo($comment); ?></i></button>
<button ><i onclick="style_button(this); return false;" class="fa fa-pen">Edit</i></button>
<button ><a href="server.php?r=del-post&pid=<?php echo $q["id"]; ?>"><i onclick="style_button(this);" class="fa fa-trash">Delete</i></button>
</div>
</div>
</a>
<?php
endwhile;											
?>




<div class="container" >
<div class="category-wrapper recent-post" >
<h1 style="color:;" >Recent posts: </h1>
<p><a href="./post/politics/index.php" >Today</a></p>
<?php
$sql = "SELECT date FROM post ORDER BY date DESC LIMIT 5";
$sql = mysqli_query($con, $sql);
if(mysqli_num_rows($sql) > 0){
foreach($sql as $count => $post){
$date = $post["date"];
$day = null;
$d = null;
$today_date = substr($date, 8, -9);
switch($today_date){
case date("d"): $d = ""; $day = "today";
break;
case date("d") - 1: $d = date("d") - 1; $day = "yesterday";
break;
case date("d") - 2: $d = date("d") - 2; $day = "2days ago";
break;
case date("d") - 3: $d = date("d") - 3; $day = "3days ago";
break;
case date("d") - 4: $d = date("d") - 4; $day = "4days ago";
break;
case date("d") - 5: $d = date("d") - 5; $day = "5days ago";
break;

echo '<p><a href="index.php?pdr='.$d.'">'.$day.'</a></p>';
}
}
}
?>
<p><i class="fas fa-spinner" ></i></p>
</div>
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

<div onclick="modal_handler(event);" id="modal"></div>


<div id="profileUploadModal" >
<div class="container uploadContainer" >
<button id="cov" ><i class="fa fa-camera" ></i>Update Cover Photo</li></button>
<button id="prof" ><i class="fa fa-user" ></i>Update Profile Photo</li></button>
</div>
</div>




<?php
require_once "footer.php";
?>

<script type="text/javascript">
function closeAlert(){
let parent = $(this).parentElement;
parent.style.display = 'none';
}
</script>