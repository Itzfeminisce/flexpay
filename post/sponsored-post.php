<?php

session_start();
require_once ".../../../config.php";
require_once ".../../../header.php";

?>
<title><?php echo "Home | ".$site_name; ?></title>
</head>
<body>
<div style="margin:5px auto;" class="container nav" >
<?php
if(isset($_GET["r"])){
echo "<h4 id='logout_notifier' style='color:lightgreen;'>You are logged out.</h4>";
}
?>
<div class="nav-wrapper" >
<p><a href=".../../../index.php" >FlexPay</a></p>
<p><a href="#" >Flexers<sup>NEW</sup></a></p>
<p><a href=".../../../profile.php" >Top Earners(Profile)</a></p>
</div>
<div class="nav-stats" >
<p>Stats:</p>
<?php
$active_members = mysqli_query($con, "SELECT DISTINCT * FROM user_biodata");
$active_members = mysqli_num_rows($active_members);

$total_post = mysqli_query($con, "SELECT DISTINCT * FROM post");
$total_post = mysqli_num_rows($total_post);

?>
<p>Active Members: <br><span>123,253,350</span> users<sup><i style="color:orange;"  class="fas fa-check-circle" ></i></sup></p>
<p>Total Members: <br><span><?php echo($active_members); ?>0</span> users</p>
<p>Total Posts: <br><span><?php echo $total_post; ?>0</span></p>
<p>Date: <br><span id="date" >0</span></p>
</div>
<div class="acc" >
<?php
if($loggedin){ ?>
<p><a href=".../../../profile.php" >Profile</a></p>
<?php } ?>
</div>
</div>

<?php
echo($search_form);
?>


<div class="container" >
<div class="post-tag" ><p>Sponsored post</p><span>1\8\2019</span></div>
<a onclick="" class="link"  id="1" href="../post/now.php" >
<div class="card-wrapper" >
<div class="icon" ><img src=".../../../../img/data-plans.jpeg" ></div>
<div class="content" >
<p>Saraki calls for rehabilitation between members.</p>
<input type="hidden" >
<p>loren ipsum, my cat can meow...(development)</p>
</div>
</div>
</a>
</div>

<div class="container" >
<div class="post-tag" ><p>Sponsored post</p><span>1\8\2019</span></div>
<a class="link" onclick="openLink(event, this);" id="1" href="#" >
<div class="card-wrapper" >
<div class="icon" ><img src=".../../../../img/data-plans.jpeg" ></div>
<div class="content" >
<p>Saraki calls for rehabilitation between members.</p>
<input type="hidden" >
<p>loren ipsum, my cat can meow...</p>
</div>
</div>
</a>
</div>
<div class="container" >
<div class="post-tag" ><p>Sponsored post</p><span>1\8\2019</span></div>
<a class="link"  onclick="openLink(event, this);" id="0" href="#" >
<div class="card-wrapper" >
<div class="icon" ><img src=".../../../../img/data-plans.jpeg" ></div>
<div class="content" >
<p>Saraki calls for rehabilitation between members.</p>
<input type="hidden" >
<p>loren ipsum, my cat can meow...</p>
</div>
</div>
</a>
</div>

<div class="container" >
<div class="post-tag" ><p>Sponsored post</p><span>1\8\2019</span></div>
<a class="link"  onclick="openLink(event, this);" id="1" href="#" >
<div class="card-wrapper" >
<div class="icon" ><img src=".../../../../img/data-plans.jpeg" ></div>
<div class="content" >
<p>Saraki calls for rehabilitation between members.</p>
<input type="hidden" >
<p>loren ipsum, my cat can meow...</p>
</div>
</div>
</a>
</div>

<div class="container" >
<div class="post-tag" ><p>Sponsored post</p><span>1\8\2019</span></div>
<a class="link" onclick="openLink(event, this);" id="0" href="#" >
<div class="card-wrapper" >
<div class="icon" ><img src=".../../../../img/data-plans.jpeg" ></div>
<div class="content" >
<p>Saraki calls for rehabilitation between members.</p>
<input type="hidden" >
<p>loren ipsum, my cat can meow...</p>
</div>
</div>
</a>
</div>

<!--<ul>
<li><a onclick="openLink(event, this);" id="1" href="/today_post" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="0" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="1" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="2" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="1" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="0" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="0" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
<li><a onclick="openLink(event, this);" id="1" href="#" >Today's Post <span>(27/08/2019)</span></a></li>
</ul>
</div>
-->

<div class="container" >
<h1 class="sp" >Sponsored</h1>
<div class="ads" >
<div class="ads-wrapper"><img src="../img/fcc.gif" ><div class="sp" ><p>Ads</p></div></div>
<div class="ads-wrapper"><img src="../img/data-plans.jpeg" ><div class="sp" ><p>Ads</p></div></div>
</div>
<div class="ads" >
<div class="ads-wrapper"><img src="../img/fcc.gif" ><div class="sp" ><p>Ads</p></div></div>
<div class="ads-wrapper"><img src="../img/data-plans.jpeg" ><div class="sp" ><p>Ads</p></div></div>
</div>
</div>

<?php require_once ".../../../footer.php"; ?>