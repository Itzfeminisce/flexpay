<?php declare(strict_types=1);

session_start();
require_once "config.php";
include "header.php";




?>
<title><?php echo "Home | ".$site_name; ?></title>
</head>
<body>
<a id="top" ></a>
<div style="margin:0 auto 10px auto;" class="container nav" >
<?php
if(isset($_GET["r"])) echo "<h4 id='logout_notifier' style='color:lightgreen;'>You are logged out.</h4>";
if(isset($_GET["res"])) echo "<h4 id='logout_notifier' style='color:blue;'>Post has been deleted.</h4>", $res = $_GET["res"];
?>
<div class="nav-wrapper" >
<?php if($loggedin): ?>
<p><a href="/" >FlexPay</a></p>
<p><a href=".../../../users/flexers.php" >Flexers<sup>NEW</sup></a></p>
<p><a style="color:#000;"  href="profile.php" >Profile</a><i class="fa fa-envelope"></i></p>
<?php endif; ?>
</div>
<div class="nav-stats" >
<p>Stats:</p>
<?php

$active_members = $con->query("SELECT online FROM user_biodata WHERE online=1");
$active_members = $active_members->num_rows;



$total_members = $con->query("SELECT DISTINCT * FROM user_biodata");
$total_members = $total_members->num_rows;


$total_post = $con->query("SELECT DISTINCT id FROM post");
$total_post = $total_post->num_rows;

?>
<p>Active Members: <br><span><?php echo($active_members); ?></span><sup><i style="color:orange;"  class="fas fa-check-circle" ></i></sup></p>
<p>Total Members: <br><span><?php echo($total_members); ?></span></p>
<p>Total Posts: <br><span><?php echo $total_post; ?></span></p>
<p>Date: <br><span id="date" >0</span></p>
</div>
<div class="acc" >
<?php if(!$loggedin): ?>
<p><a href="register.php" >Login</a></p>
<p><a href="register.php" >Register</a></p>
<?php endif; ?>
</div>
</div>

<?php
if ($loggedin) echo($search_form);
?>



<div class="container" >
<h3 style="margin-left:;" ><b>Other posts you may love</b></h3>
<div class="online-users" >
<?php
$query = "SELECT post.*, post_att.* FROM post JOIN post_att ON post.id=post_att.post_id ORDER BY RAND()";
$query = $con->query($query);
if($query->num_rows > 0): 
while($r = mysqli_fetch_assoc($query)):

?>
<a href=".../../../post/now.php?pid=<?php echo $r['post_id'] ?>">
<div style="min-width:200px;max-width:400px;" class="users" >
<div class="pre_iden" ><img src=".../../../img/done.png"></div>
<div class="online_user_icons" ><img src=".../../../uploads/<?php echo $r['post_att']; ?>" alt="" ></div>
<p><?php echo substr($r["post_title"], 0, 50); ?>•...<span style="color:#aaa;font-size:smaller;">Click</span></p>
<p><?php echo $r["post_desc"]; ?></p>
</div>
</a>
<?php
endwhile;
endif;
?>
</div>
<p style="color:#aaa;" >Sort by <span style="color:#000;font-size:xx-small;" ></span></p>
<form>
<select onchange="location='index.php?category=1<?php echo $row["post_desc"]; ?>';" style="background:transparent;border:none;" >
<?php
$sql = "SELECT DISTINCT post_desc FROM post ORDER BY id DESC";
$sql = $con->query($sql);
echo '<option>Choose by category</option>';
if($sql->num_rows > 0){
foreach($sql as $count => $row){
echo '<option>'.ucfirst($row["post_desc"]).'</option>';
}
$sql->close;
}
?>
</select>
</form>
</div>


<?php
$query = "SELECT post.*, user_biodata.fn, user_biodata.ln, user_biodata.profile_pic FROM user_biodata JOIN post ON user_biodata.id=post.user_id ORDER BY post.date DESC";
$query = $con->query($query);
$c = 0;
if($query->num_rows < 1)
{
echo '<div class="container" >
 <h1 style="text-align:center;color:grey;font-weight:lighter;">
 No posts available.
 </h1></div>'; $con->close;
}else{
while($q = $query->fetch_assoc()):
$c++;
$at_from_post = $q["post_p_att"];
$upvote = $con->query("SELECT upvote FROM post_upvotes WHERE post_id='".$q["id"]."'");
$upvote =$upvote->num_rows;

$poster = ($q["user_id"] == $_SESSION["id"])? "You": $q["fn"]." ".$q["ln"];
$comment = $con->query("SELECT post_id FROM comments WHERE post_id='".$q["id"]."'");
$comment =$comment->num_rows;

$post_att = $con->query("SELECT post_att FROM post_att WHERE post_id='".$q["id"]."'");
$post_att = ($at_from_post != "") ? $post_att->num_rows :0;

$p_att = ($q["post_p_att"] == "") ? null : '<img src=".../../../uploads/'.$q["post_p_att"].'">';

$date = date_create($q["date"]);
$d = date_format($date, "D d, h:i");
?>

<div style="width:100%;border-radius:0;margin:10px auto;box-shadow:0px 0px 2px #aaa;" class="user_modal" >
<input type="hidden" value="<?php echo $_SESSION['id']; ?>">
<a onclick="openLink(this);" id="<?php echo $q['id']; ?>" href=".../../post/now.php?token=<?php echo md5($q["user_id"]); ?>&pid=<?php echo $q["id"]; ?>#t=<?php echo md5($q["date"]); ?>" >
<h5><b><?php echo $poster; ?> </b>shared this post</h5>
<hr style="opacity:0.1;" >
<div class="modal_header" >
<div class="modal_user_icon" ><img src=".../../../profile/dp/<?php echo $q["profile_pic"]; ?>" ></div>
<div class="modal_user_info" >
<p><?php echo $q["fn"]." ".$q["ln"]; ?></p>
<p><?php echo $d; ?></p>
<span><?php echo $q["post_desc"]; ?></span>
</div>
</div>
<div class="modal_body" >
<div class="modal_body_parag" ><p><?php echo substr($q["post_cont"],0, 200); ?>...•<span style="color:#aaa;">Read more</span></p></div>
<div class="post_att" ><?php echo $p_att; ?></div>
<div class="post_att_no" ><p>Photos•<?php echo($post_att); ?></p></div>
</div>
<div class="modal_footer" >
<button ><i onclick="style_button(this); return false;" class="fa fa-heart">Likes•<?php echo($upvote); ?></i></button>
<button ><i onclick="style_button(this); return false;" class="fa fa-comment">Comments•<?php echo($comment); ?></i></button>
<button ><i onclick="style_button(this); return false;" class="fa fa-share">Shared•<?php echo("10"); ?><sup>+</sup></i></button>
</div>
</a>
<div class="modal_toggler" onclick="
if(this.nextElementSibling.style.display=='block'){
this.nextElementSibling.style.display='none';
}else{
this.nextElementSibling.style.display='block';
}
"><i class="fa fa-ellipsis-v"></i></div>
<div class="modal_toggler_menu">
<p><a href="server.php?r=pin-post&pid='.$q["id"].'"><i class="fa fa-fire"></i>&nbsp; Report</a></p>
</div>
</div>
<?php
endwhile;	
}
?>


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
<i class="fa fa-chevron-top" ><a href="#top" class="toTop"  ></a></i>
<?php
include "footer.php";
?>