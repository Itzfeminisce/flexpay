
<?php
require_once "config.php";
require_once "header.php";


if(isset($_GET["url"]) && $_GET["cert"] === "fpid") {
$url = $_GET['url'];
$is_referred = "We realize you are being referred by some one. Please enter you details below to continue.";
 }else{
$url = "flexpay"; 
$is_referred = "";
 }
?>
<title><?php echo $site_name." | Create an account now "; ?></title>
</head>
<body>
<a id="top" ></a>
<div style="margin:0 auto;" class="container nav" >
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
</div>

<form id="login_form"  class="container"  action="<?php echo htmlentities(".../../../server.php"); ?>">
<div style="text-align:center;" id="output_containerL" ></div>
<div class="login container">
<div class="go-back" ><a href="javascript: history.go(-1);" ><i class="fas fa-arrow-left" ></i></a></div>
<p>Login: &nbsp;</p>
<input type="hidden" onkeyup="input_validation(this);"name="login" id="fn" value="login" placeholder="Please enter your first name" >
<p><input type="text" id="lg"  name="lg" placeholder="E-mail/Phone" >
<p><input type="password" id="pwd" name="pwd" placeholder="Password">
<p><button id="login_btn"  type="submit" name="submit" value="submit"   >Sign in</button></p>
</div>
</form>

<div id="epin_form" ></div>

<form name="reg_form"  id="registration_form"  class="container" action="<?php echo htmlentities(".../../../server.php"); ?>" >
<div style=""  class="register container" >
<div id="output_containerR"  class="container success" ></div>
<h4 style="text-align:center;color:green;"  ><?php echo $is_referred;?></h4>
<h1>Join FlexPay today and start earning BIG!</h1>
<br>
<hr style="opacity:0.1;">
<br>
<p>First name: <input type="text" onkeyup="input_validation(this);"name="fn" id="fn" value="Femi" placeholder="Please enter your first name" ></p>
<br>
<input type="hidden" onkeyup="input_validation(this);"name="register" id="fn" value="register" placeholder="Please enter your first name" >
<p>Last name: <input type="text" name="ln" id="ln" value="Rotimi" placeholder="Please enter your last name" ></p>
<br>
<p>Email address: <input type="email" name="em" id="em" value="rofesol.ng@gmail.com" placeholder="Please enter your e-mail address" ></p>
<br>
<p>Phone number: <input type="number" name="ph" id="ph" value="07061017993" placeholder="Please enter your phone number" ></p>
<br>
<p>Were you referred? <i onclick="alert('WERE YOU REFERRED? \n\rKindly insert the referral ID given to you in the box below. \n\n \r\nWHAT IS REFERRAL ID? \n\rReferral ID is a unique identifier generated to a specific user of FlexPay who has agreed to be an affiliate with a sole responsibility attached therein. Read more by clicking on our FAQs(Frequently Asked Questions). Welcome');" 
class="fa fa-question-circle" ></i><input type="text" name="rf" id="rf" value="<?php echo($url); ?>" placeholder="Please enter your referral id" ></p>
<br>
<p>Password: <input type="password" name="pwd" id="pwd" value="flex1234" placeholder="Please enter a secure password" ></p>
<br>
<p>Confirm password: <input type="password" name="rpwd" id="rpwd" value="flex1234" placeholder="Please enter your password again" ></p>
</div>
<div class="py register container" >
<h1>Mode of payment</h1>
<br>
<p>Paystack: <input type="radio" onchange="paystack(this.value);" value="paystack" name="paymode"></p>
<br>
<p>E-PIN: <input type="radio" onchange="paystack(this.value); "value="epin" name="paymode" checked></p>
<br>
<hr>
<h2>Total Amount</h2>
<p>#1,600</p>
<hr>
<br>
<p><input id="tc" type="checkbox" name="tc" value="2" checked="checked"> I have read and agreed to the <a href="#" >terms and condition</a> of the platform. </p>
<br>
<script src="https://js.paystack.co/v1/inline.js"></script>
<p><button id="register_btn" type="submit" style="padding:15px;"  name="submit" id="submit" value="submit">Join us today!</button></p>
</div>
</form>






<?php
require_once "footer.php";
?>