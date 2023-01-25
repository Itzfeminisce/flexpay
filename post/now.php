
<?php
require_once ".../../../config.php";
require_once ".../../../header.php";

/*if(!isset($_SESSION["ph"]) || !isset($_SESSION["em"]) || !$loggedin){
echo "<script> location ='.../../../server.php?logout'; </script>";
}
*/

$url = "http://".$_SERVER["HTTP_HOST"]."/post/now.php";


$post_id = $_GET['pid'] ?? "";

?>

</head>
<body>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0&appId=308329219822264&autoLogAppEvents=1"></script>
<div style="margin:0 auto 5px auto;" class="container nav" >
<div class="nav-wrapper" >
<div class="go-back" ><a href="javascript: history.go(-1);" ><i class="fas fa-arrow-left" ></i></a></div>
<?php if($loggedin):?>
<button onclick="window.location='/';" class="message" ><i class="fas fa-university"></i><br>Home</button>
<button onclick="window.location='.../../../users/flexers.php';"class="message" ><i class="fas fa-users"></i><br>Meet</button>
<button onclick="window.location='.../../../profile.php';" class="message" ><i class="fas fa-user"></i><br>Profile</button>
<?php endif; ?>
</div>
</div>
<?php
$sql = $con->query("SELECT * FROM user_biodata JOIN post ON user_biodata.id=post.user_id JOIN post_att ON post_att.post_id=post.id WHERE post.id='".$post_id."'");
if($sql->num_rows > 0){
foreach($sql as $count => $row){
$poster_id = $row["user_id"];
$fn = $row["fn"];
$ph = $row["ph"];
$date = date_create($q["date"]);
$d = date_format($date, "D d, h:i");
$profile_pic = $row["profile_pic"];
$date = $row["date"];
$post_title = $row["post_title"];

$post_desc = $row["post_desc"];
$post_att = $row["post_att"];
$post_cont = $row["post_cont"];
}
}else{
echo '<div class="container"><p>Page not found</p></div>';
exit();
}
?>
<title><?php echo $sitename." | ".$post_title; ?></title>
<a id="like"></a>
<div style="padding:0;" class="s_post container" >
<div class="author_details" >
<div class="author_icon" >
<div class="premium-identifier" >
<img src=".../../../img/done.png" >
</div>

<img onclick="show_user(event, '<?php echo $row["user_id"]; ?>');" src=".../../../profile/dp/<?php echo $row["profile_pic"]; ?>">
</div>
<div class="author_content_details" >
<p><?php echo $row["fn"]; ?></p>
<p style="font-size:small;color:#aaa;" ><?php echo substr($row["post_title"], 0, 20); ?>...</p>
<p><?php echo $d; ?>
<span class="author_tag" ><?php echo $row["post_desc"]; ?></span>
<span style="border:1px solid red;color:red;"  class="author_tag" >Sponsored</span> 
</p>
<form onsubmit="Handler.upvote(event, this);">
<input id="post_id" type="hidden" name="post_id" value="<?php echo $post_id; ?>"  >
<input id="poster_id"  type="hidden" name="poster_id" value="<?php echo $poster_id; ?>"  >
<input id="liker_id" type="hidden" name="liker_id" value="<?php echo $_SESSION["id"]; ?>"  >
<input type="hidden" name="action" value="upvote"  >
<button type="submit" id="upvote_btn" name="submit" class="author_tag" value="submit" ><i class="fa fa-heart"></i>0</button>
</form>
</div>
</div>

<div class="author_content_description" >
<h1><?php echo $row["post_title"]; ?></h1>
<hr style="opacity:0.1;margin:20px 5px;" >
<?php
foreach($sql as $count => $row){
if($row["post_att"] !== ""){?>
<img style="display:block;margin:10px 0;height:100%;min-height:100%;width:100%;" src=".../../../uploads/<?php echo $row["post_att"]; ?>" border="0" width="300px" height="250px">
<?php 
}
}
?>

<div class="content_div" >
<?php
$content = $row["post_cont"];
$content = wordwrap($content, 75);
$content = html_entity_decode($content);  

echo "<pre>";
echo $content;
echo "</pre>"; 
 ?>
</div>
</div>
</div>


<div class="share container" >
<p>Share post on: </p>
<a class="fb-share-button" data-href="<?php echo($_SERVER["HTTP_HOST"]); ?>/post/now.php" data-layout="button_count" data-size="small" target="_blank"  href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER["HTTP_HOST"]; ?>/post/now.php?pid=<?php echo $post_id; ?>&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore" ><div><img src=".../../../img/fb.png" ></div></a>
<a class="twitter-share-button" href="##"><div><img src=".../../../img/ig.jpeg" ></div></a>
<a href="#" ><div><img src=".../../../img/google.png" ></div></a>
<a class="twitter-share-button" rel="nofollow" target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo($_SERVER["HTTP_HOST"]); ?>/post/now.php"><div><img src=".../../../img/twitter.png" ></div></a>
<a href="https://wa.me/+234<?php echo($_SESSION["ph"]); ?>?text=<?php echo wordwrap($row["post_cont"], 70); ?>"><div><img src=".../../../img/still_more.png" ></div></a>
</div>
<a id="comment"  ></a>
<?php
if($loggedin){
?>
<div class="comment container" >
<h4>Be the first to comment.</h4>
<br>
<br>
<form action="?" onsubmit="Comment_handler.submit_comment(event, this);" >
<input id="" name="user_id"  type="hidden" value="<?php echo($_SESSION["id"]); ?>" >
<input id="is_post_id"  name="post_id"  type="hidden" value="<?php echo $post_id; ?>" >
<input id="" name="replying_to"  type="hidden" value="0" >
<textarea rows="7" cols="40" name="comment-content" id="comment-content" placeholder="Join the discussion..."  ></textarea>
<button type="submit" id="post_comment_btn" name="comment" value="post comment" >Post comment</button>
</form>
</div>
<?php
}
?>


<div class="user_comments s_post container" >
<h4>Read what people are saying.</h4>
<br>
<br>
<div id="comment_container" ></div>
</div>


<div class="container" >
<div class="category-wrapper" >
<h1 style="color:red;" >Trending<i class="fa fa-star" ></i>: </h1>
<p><a href="./post/politics/index.html" >Politics</a></p>
<?php
$sql = $con->query("SELECT DISTINCT post_desc FROM post ORDER BY id DESC");
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

<div class="cdtfpr" ><h4 id="cdrfpr_c" >10</h4></div>

<div onclick="modal_handler(event);" id="modal"></div>

<?php
require_once ".../../../footer.php";
?>
<script type="text/javascript">
$(document).ready(function(){
let is_post_id = $("#is_post_id").val();
Handler.display_upvotes(is_post_id);
Comment_handler.display_comment(is_post_id);


process_payment(is_post_id, "<?php echo $_SESSION['id']; ?>");

});
</script>