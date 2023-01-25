<?php declare(strict_types=1);

session_start();
require_once ".../../../config.php";
require_once ".../../../header.php";
$from = $_REQUEST["i"] ?? null;
$to = $_SESSION["id"] ?? null;
?>
<title><?php echo "Messages | ".$site_name; ?></title>
<style type="text/css">
.text-box {
position:fixed;
bottom:0;
left:0;
width:100%;
height:100%;
max-width:700px;
margin:auto;
background:#eee;
z-index:;
transition:100ms ease-in-out;
text-align:center;
}
</style>
</head>
<body>
<?php
$query = $con->query("SELECT user_biodata.fn, user_biodata.ln, user_biodata.profile_pic FROM user_biodata WHERE user_biodata.id='{$from}'");
if($query->num_rows > 0){
$f = $query->fetch_assoc();
$pic = $f["profile_pic"];
$full_name = $f["fn"]." ".$f["ln"];
}
?>

<div style="margin:0 auto;padding:0;z-index:1;" class="container nav" >
<div class="nav-wrapper m" >
<button onclick="window.history.go(-1);" class="message" ><i class="fas fa-chevron-left"></i><br>Back</button>

<div class="name" ><h4><?php echo $full_name; ?><br><span style="font-weight:normal;font-size:10px;" >Tate is typing...</span></h4>
</div>
<div class="user_img" ><img src=".../../../uploads/<?php echo $pic; ?>" ></div>
</div>
</div>





<div id="text_box" class="text-box" >
<div class="assembly chat-parent" >
<br><br>


<!--
Text messages goes here...
-->

<!--<div class="holder" >

<div class="text recipient"><p>This is a message from receiver</p>
</div>
</div>
-->

<!--<div style="margin-bottom:;"  class="holder for_user" >
<div class="text user" ><p>This is a message from user.</p>
</div>
</div>
-->
<?php
$query = $con->query("SELECT * 
FROM private_chat 
WHERE to_id='{$from}' AND from_id='{$from}'
OR
to_id='{$to}' AND from_id='{$to}'
");
if($query->num_rows > 0){

while($r = $query->fetch_assoc()){
$to_me = $r["to_id"];
$from_ = $r["from_id"];
$message = $r["chat"];
$margin_bottom = 1;
$field = "";
if($from !== $to_me){
$field = '
<div class="holder" >
<div class="text recipient"><p>'.$message.'</p>
</div>
</div>
';
}else{
$field = '
<div style="margin-bottom:;"  class="holder for_user" >
<div class="text user" ><p>'.$message.'</p>
</div>
</div>
';
}
}
echo $field;
}


/*echo $field;*/
?>

<br><br><br><br>
<br><br><br><br>
</div>


<div class="box container" >
<div class="myform" >
<textarea id="chat" name="chat" rows="2" cols="40"  placeholder="Send a polite message" ></textarea>
<button type="button" onclick="send_chat(event, this);" value="<?php echo $to; ?>"><i class="fa fa-paper-plane" ></i></button>
</div>
</div>
</div>

<script src=".../../../script/jquery.js"></script>
<script src=".../../../script/script.js"></script>
</body>

</html>