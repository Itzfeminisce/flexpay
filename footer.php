<?php
$query = "SELECT * FROM user_biodata ORDER BY online DESC";
$query = $con->query($query);
if($query->num_rows > 0):

?>
<div class="container" >
<p><b>Other users</b></p>
<div class="online-users" >
<?php
while($r = $query->fetch_assoc()):
if($r["id"] == $_SESSION["id"]) continue;
$online = ($r["online"] == 0) ? "Offline" : "Online";
if(empty($r["profile_pic"])) continue;
?>
<a href=".../../../profile.php?ref_redr=1">
<div onclick="show_user(event, '<?php echo $r['id']; ?>');" class="users" >
<div class="pre_iden" ><img src=".../../../img/done.png"></div>
<div class="online_user_icons" ><img src=".../../../profile/dp/<?php echo $r['profile_pic']; ?>" alt="<?php echo $r['profile_pic']; ?>" ></div>
<p><?php echo $r["fn"]; ?></p>
<p><?php echo $online; ?></p>
</div>
</a>
<?php
endwhile;
?>
</div>
</div>
<?php
endif;
?>

<div class="info container" >
<h4 id="window-info" ></h4>
</div>


<div class="footer container" >
<div class="left" >
<h1>About</h1>
<p><a href="#" >About us</a></p>
<p><a href="#" >What is FlexCoin.</a></p>

<h1>Technicals</h1>
<p><a href="#" >Contact us</a></p>
<p><a href="#" >Site Map</a></p>

<h1>Usage</h1>
<p><a href=".../../../policy/privacy-policy.html" >Refund Policy</a></p>
<p><a href=".../../../policy/privacy-policy.html" >Privacy Policy</a></p>
<p><a href=".../../../policy/privacy-policy.html#tc" >Terms of use</a></p>
<p><a href="#" >Report spam</a></p>
</div>
<div class="right" >
<form action=".../../../server.php" id="sbcr_form"  >
<div id="output_containerN" ></div>
<p>Subscribe to our weekly newsletter.</p>
<input type="hidden" name="sbcr">
<input type="email" name="em" placeholder="Enter active email">
<button type="submit" name="submit" value="submit"  >Subscribe</button>
</form>
<br>
<br>
<div style="border-left:none;background:transparent;"  class="right" >
<h1>Social media</h1>
<p><a href="#" >Facebook</a></p>
<p><a href="#" >Twitter</a></p>
<p><a href="#" >Instagram</a></p>
<p><a href="#" >Snapchat</a></p>

<h1>Our developers</h1>
<p><a href="#" ></a></p>
<p><a href="#" >Say Hi!</a></p>
<p><a href="#" >Submit complaint</a></p>
</div>
</div>
</div>
<div class="footer container" >
<p>2017-<?php echo date("Y");?><sup>&trade;</sup> All Rights Reserved | FlexPay Teams</p>
</div>
<script src=".../../../script/jquery.js"></script>
<script src=".../../../script/script.js"></script>
</body>

</html>