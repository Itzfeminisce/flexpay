<?php declare(strict_types=1);

session_start();
require_once "config.php";
require_once "header.php";


$stmt = $con->query("SELECT * FROM user_biodata WHERE id= '".$USER_ID."'");
$data = $stmt->fetch_object();

?>
<title><?php echo "Profile Manager | ".$data->fn." ".$data->ln." | ".$site_name; ?></title>

<style type="text/css">
.container {
text-align:justify;
margin-bottom:10px;
}

.container h1{
border-bottom:1px solid #eee;
margin-bottom:10px;
}

.container h2{
margin-bottom:5px;
}

.field  p {
font-weight:bold;
font-size:15px;
color:#aaa;
}

.field {
position:relative;
margin-bottom:10px;
}

.field span{
font-size:10px;
font-weight:lighter;
color:black;
border-bottom:1px solid black;
text-transform:capitalize;
}

.field button {
position:absolute;
right:0;
bottom:0;
width:auto;
height:auto;
padding:2px;
}

.field input {
position:relative;
left:0;
bottom:0;
max-width:200px;
max-height:50px;
border:2px solid #eee;
border-radius:5px;
margin-top:5px;
padding:10px;
transition:500ms;
}

.field .form {
display:none;
}
.user {
position:relative;
width:100%;
height:auto;
display:flex;
flex-direction:row;
align-items:center;
margin-bottom:20px;
padding:10px 1px;
border-bottom:1px solid #eee;
}

.user .photo{
width:50px;
min-width:50px;
height:50px;
overflow:hidden;
background:#aaa;
border-radius:50%;
margin-right:10px;
}
.user .photo img{
width:100%;
}

.user .action {
position:relative;
display:flex;
flex-flow:row wrap;
align-content:center;
width:100%;
height:50px;
}

.user .action h2{
align-self:flex-start;
}
.action .online{
position:absolute;
right:0;
top:0px;
width:10px;
height:10px;
border-radius:50%;
background:#aaa;
}

.user .action button {
position:absolute;
bottom:0;
right:10px;
width:auto;
height:auto;
font-weight:bolder;
color:black;
padding:5px;
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

<div class="container" >

<h1><i class="fa fa-user" ></i>Personal</h1>
<div class="field" >
<h2>Full Name</h2>
<p id="parent" ><?php echo ucfirst($data->fn)." ".ucfirst($data->ln); ?></p>
<div id="form" class="form" >
<input type="text" id="fullName" name="fullName" value="" >
<button onclick="updateData(this);" value="fullName" ><i class="fa fa-pen" >Update</i></button>
</div>
<button onclick="btn(this);" value="fullName" ><i class="fa fa-pen" >Edit</i></button>
</div>

<br>


<div class="field" >
<h2>Email</h2>
<p id="parent" ><?php echo $data->em; ?></p>
<div id="form" class="form" >
<input type="email" id="email" name="email" value="" >
<button onclick="updateData(this);" value="email" ><i class="fa fa-pen" >Update</i></button>
</div>
<button onclick="btn(this);"><i class="fa fa-pen" >Edit</i></button>
</div>

<br>

<div class="field" >
<h2>Phone Number</h2>
<p id="parent" ><?php echo $data->ph; ?></p>
<div id="form" class="form" >
<input type="number" id="phone" name="phone" value="" >
<button onclick="updateData(this);" value="phone" ><i class="fa fa-pen" >Update</i></button>
</div>
<button onclick="btn(this);"><i class="fa fa-pen" >Edit</i></button>
</div>

<br>

<div class="field" >
<h2>Gender</h2>
<p id="parent" ><?php echo ($data->gender == "")? "Not set": ucfirst($data->gender);?></p>
<div id="form" class="form" >
<input type="text" id="gender" name="gener" value="" >
<button onclick="updateData(this);" value="gender" ><i class="fa fa-pen" >Update</i></button>
</div>
<button onclick="btn(this);"><i class="fa fa-pen" >Edit</i></button>
</div>

<br>

<div class="field" >
<h2>Date of Birth <span>(DD.MM.YYYY)</span></h2>
<p id="parent" ><?php echo ($data->dob == "")? "Not set":$data->dob; ?></p>
<div id="form" class="form" >
<input type="text" id="dob" name="dob" value="" >
<button onclick="updateData(this);" value="dob" ><i class="fa fa-pen" >Update</i></button>
</div>
<button onclick="btn(this);"><i class="fa fa-pen" >Edit</i></button>
</div>

<br>

<!-- Does mot belong here--><a id="accountActivation"  ></a>

<div class="field" >
<h2>Relationship</h2>
<p id="parent" ><?php echo ($data->relationship == "")? "Not set":ucfirst($data->relationship); ?></p>
<div id="form" class="form" >
<input type="text" id="relationship" name="relationship" value="" >
<button onclick="updateData(this);" value="relationship" ><i class="fa fa-pen" >Update</i></button>
</div>
<button onclick="btn(this);"><i class="fa fa-pen" >Edit</i></button>
</div>

</div>


<div class="container" >
<h1><i class="fa fa-user" ></i>Account</h1>
<div class="field" >
<h2>Account status</h2>
<p id="parent" ><?php echo "Not activated"; ?></p>
<div id="form" class="form" >
<input type="text" id="fullName" name="fullName" value="" placeholder="Enter coupon code."  >
<button onclick="updateData(this);" value="fullName" ><i class="fa fa-pen" >Activate</i></button>
</div>
<button onclick="btn(this);" value="fullName" ><i class="fa fa-pen" >Edit</i></button>
</div>
</div>



<div class="container" >

<h1><i class="fa fa-user" ></i>Affiliate</h1>
<div class="field" >
<h2>Referred member(s)</h2>
<p id="parent" ><?php echo 9;?></p>
<div id="form" class="form" >
<input type="text" id="fullName" name="fullName" value="" >
<button onclick="updateData(this);" value="fullName" ><i class="fa fa-pen" >Activate</i></button>
</div>
<!--<button onclick="btn(this);" value="fullName" ><i class="fa fa-pen" >Edit</i></button>-->
</div>

<br>
<div class="field" >
<h2>Confirmed referred member(s)</h2>
<p id="parent" ><?php echo 5;?></p>
<div id="form" class="form" >
<input type="text" id="fullName" name="fullName" value="" >
<button onclick="updateData(this);" value="fullName" ><i class="fa fa-pen" >Activate</i></button>
</div>
<!--<button onclick="btn(this);" value="fullName" ><i class="fa fa-pen" >Edit</i></button>-->
</div>

<br>

<div class="field" >
<h2>Pending referral payment(NGN)</h2>
<p id="parent" ><?php echo 4000;?></p>
<div id="form" class="form" >
<input type="text" id="fullName" name="fullName" value="" >
<button onclick="updateData(this);" value="fullName" ><i class="fa fa-pen" >Activate</i></button>
</div>
<!--<button onclick="btn(this);" value="fullName" ><i class="fa fa-pen" >Edit</i></button>-->
</div>

<br>

<div class="field" >
<h2>Confirmed referral payment(NGN)</h2>
<p id="parent" ><?php echo 5000;?></p>
<div id="form" class="form" >
<input type="text" id="fullName" name="fullName" value="" >
<!--<button onclick="updateData(this);" value="fullName" ><i class="fa fa-pen" >Activate</i></button>-->
</div>
<!--<button onclick="btn(this);" value="fullName" ><i class="fa fa-pen" >Edit</i></button>-->
</div>

<br>
</div>



<?php 
$stmt->free_result;
$con->close; 
?>

<?php
$stmt = $con->query("SELECT user_biodata.id,
					user_biodata.fn,
					user_biodata.ln,
					user_biodata.profile_pic,
					user_biodata.online,
					friends.* 
					FROM user_biodata
					JOIN friends 
					ON user_biodata.id = friends.friend_id
					WHERE friends.my_id = '".$_SESSION['id']."'
					ORDER BY user_biodata.online DESC
					");
?><div class="container" >
<h1><i class="fa fa-users" ></i>Friends <i id="friendCount" style="font-weight:normal;"><?php echo $stmt->num_rows; ?></i></h1>

<?php
if($stmt->num_rows < 1) echo "<div class='container'><h4>You have no friends yet.</h4></div>"; $con->close;
 while($row = $stmt->fetch_object()):
 $online = ((int)$row->online === 0) ? "#aaa" : "lightgreen";
?>
<div class="user" >
<div class="photo" ><img src=".../../../profile/dp/<?php echo ($row->profile_pic == "")? null : $row->profile_pic; ?>" ></div>
<div class="action" >
<h2><?php echo $row->fn." ".$row->ln; ?></h2>
<div style="background:<?php echo $online; ?>;" class="online" ></div>
<input type="hidden" value="<?php echo $row->friend_id; ?>"  >
<button onclick="unFollow(this, '<?php echo $row->fn." ".$row->ln; ?>');" value="unfollow" >Unfollow</button>
</div>
</div>
<?php endwhile; ?>

</div>
<?php
include "footer.php";
?>


<script type="text/javascript">

function btn(openBtn){
let formBox = openBtn.previousElementSibling;
formBox.style.display = "block";
formBox.firstElementChild.focus();
openBtn.style.display = "none";
return openBtn;
}


function updateData(btn){
let formInput = btn.previousElementSibling;
let formContainer = btn.parentElement;
let actionVal = formInput.value;
let action = btn.value;

/*if(actionVal == ""){
formContainer.style.display = "none";
return false;
}

*/
$.post("server.php", {manageProfile:true, type:action, actionVal:actionVal}, function(data){
formContainer.previousElementSibling.textContent = data;
//pop_up(window_info, data);
formInput.value = "";
formContainer.style.display="none";
});
}


function unFollow(btn, user){
let formInput = btn.previousElementSibling;
let ParentDiv = btn.parentElement;
let Ancestor = ParentDiv.parentElement;
let actionVal = formInput.value;
let action = btn.value;
if(!confirm("Do you really want to unfollow "+user+" ?")){
return false;
}
$.post("server.php", {manageProfile:true, type:action, actionVal:actionVal, user:user}, function(data){
Ancestor.innerHTML = data;
let friendCount = document.querySelector("#friendCount");
friendCount.textContent = friendCount.textContent - 1;
let tm_out = setTimeout(function(){
Ancestor.style.display = "none";
}, 2000);
if(parseInt(friendCount.textContent) == 0){
clearTimeout(tm_out);
Ancestor.innerHTML = "<div class='container'><h4>You have no friends.</h4></div>";
}

});
}

</script>