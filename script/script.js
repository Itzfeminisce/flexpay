

//***** SEND CHAT MESSAGES****
function send_chat(event, btn){
event.preventDefault();
let chat = btn.previousElementSibling;
/*let my_id = chat.previousElementSibling.value*/
let to = btn.value;

if(chat.value==""){
chat.focus();
return false;
}
$.ajax({
url:".../../../server.php",
type:"POST",
dataType:"JSON",
data:{"chat":"save_chat","chat_id":to,"message":chat.value},
success:function(data){ 
chat.value = "";
/*alert(data[0])*/
},
error:function(jxHQR, textStatus){ 
pop_up(window_info, textStatus);
}
});
}


/*function fetch_users_chat_one(session_id){
alert("feching chats...");
$.ajax({
url:"./chat.server.inc.php",
type:"POST",
data:{action:"fetch_chat",from_id:session_id},
success:function(data){
//...What do do with data
alert(data);
/*let chat_parent = document.querySelector("#chat_parent");*/

/*chat_parent.appendChild(i.card);*/

/*},
error:function(jxHQR, textStatus){
alert("An error occured: "+textStatus);
}
});
}
*/

//ADD FRIEND HANDLER 

function add_friend(event, btn){
event.preventDefault();
$.ajax({
url:".../../../server.php",
type:"POST",
data:{"chat":"add","user_id":btn.value},
dataType:"JSON",
success: function(data){
switch(data[0]){
case 0: btn.style.background='rgba(255,0,0,0.5)'; pop_up(window_info, data[2]);
break;
case 1: btn.innerHTML = data[1]; btn.style.background='lightgreen'; pop_up(window_info, data[2]);
break;
case 2: pop_up(window_info, data[2]);
break;
case 3: pop_up(window_info, data[2]);
break;
}
},
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
}
//ACCEPT FRIENDS REQUEST
function accept_friend(event, btn){
event.preventDefault();
$.ajax({
url:".../../../server.php",
type:"POST",
data:{"chat":"accept","user_id":btn.value},
dataType:"JSON",
success: function(data){
switch(data[0]){
case 0: btn.innerHTML = data[1];  btn.style.background='red'; pop_up(window_info, data[2]);
break;
case 1: btn.innerHTML = data[1]; btn.style.background='lightgreen'; pop_up(window_info, data[2]);
break;
case 2: btn.innerHTML = data[1]; pop_up(window_info, data[2]);
break;
case 3: btn.innerHTML = data[1]; pop_up(window_info, data[2]);
break;
}
},
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
}

//REJECT REQUEST
function reject_friend(event, btn){
event.preventDefault();
let container = btn.closest("#my_card");
$.ajax({
url:".../../../server.php",
type:"POST",
data:{"chat":"reject","user_id":btn.value},
dataType:"JSON",
success: function(data){
switch(data[0]){
case 0: btn.style.background='lightgreen'; pop_up(window_info, data[2]);
container.style.display="none";
break;
case 1: btn.innerHTML = data[1]; btn.style.background='red'; pop_up(window_info, data[2]);
break;
case 2: btn.innerHTML = data[1]; pop_up(window_info, data[2]);
break;
case 3: btn.innerHTML = data[1]; pop_up(window_info, data[2]);
break;
}
},
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
}

//***DISMISS SUGGESTED CARDS(FRIENDS)
function dismiss_card(event, btn){
event.preventDefault();
let container = btn.closest("#my_card");
let Ancestor = container.parentElement.firstElementChild.firstElementChild.firstElementChild;
container.style.display="none";
Ancestor.textContent = Ancestor.textContent - 1;
if(parseInt(Ancestor.textContent) == 0) container.parentElement.innerHTML = "<div><h4>No Suggested friends.</h4></div>";
}

//**** Messages HANDLER FOR CHATS ****////
function chat_friend(event, btn){
event.preventDefault();
let id = btn.value;
let to = btn.previousElementSibling;
let modal = document.querySelector("#text_box");
let modal_pic = document.querySelector("#modal_user_pic");
let nextBox = modal.nextSibling;
modal.style.top = "0%";
modal_pic.src = ".../../../img/loader.gif";
$.ajax({
url:".../../../server.php",
data:{"fetch_user_pic":id},
type:"POST",
dataType:"JSON",
success:function(data){
modal_pic.src = ".../../../profile/dp/"+data.pic;
let user_id = document.querySelector("#send_chat_btn");
user_id.value = id;
},
error:function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});

}






//**** SHOW USER HANDLER **/////

function show_user(event, user_id){
event.preventDefault();
this.id = user_id;
let modal =  document.querySelector("#modal");

modal.style.display="block";
modal.innerHTML = "<div class='container'><h4>Please wait...</h4></div>";

$.post(".../../../server.php", {"show_user":this.id},
function(data){
modal.innerHTML = data;
});
}


///**** USER MODAL HANDLER ***////
function modal_handler(event){
let modal = document.querySelector("#modal");
if(event.target==modal){
modal.style.display="none";
}else if(event.target == event.target){
modal.style.display="none";
}
}

//**** CREATE LOADER *****///
function loader(has=true, loaded=true, e, r){
if(!(has&&loaded)){
e.innerHTML = "<b>loading. Please wait...</b>";
r = null;
}else{
e.innerHTML = r;
}
}



//****** UPVOTE AND PAYMENT HANDLER ****////
let window_info = document.querySelector("#window-info");

function pop_up(container, message){
if(container.innerHTML == ""){
container.parentElement.style.display = "block";
container.innerHTML = message;
setTimeout(function(){
container.innerHTML = "";
container.parentElement.style.display = "none";
}, 5000);
}else{
container.parentElement.style.display = "none";
}
}




let Handler = {
upvote : (event, form)=>{
event.preventDefault();
let user_id = $("#liker_id");
let poster_id = $("#poster_id");
let btn = document.querySelector("#upvote_btn");

if(user_id.val() == ''){
pop_up(window_info, "Only logged in users can upvote post");
return false;
}
if(user_id.val() === poster_id.val()){
pop_up(window_info, "You cannot like your own post.");
return false;
}
let $fdata = $(form);
$.ajax({
url:".../../../server.php",
type:"POST",
data:$fdata.serialize(),
success :function(data){
let p_id = $("#post_id").val();
Handler.display_upvotes(p_id);
btn.style.color='red';
btn.style.background='#fff';
pop_up(window_info, "You liked this post.");
},
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
},
display_upvotes : (post_id)=>{
$.ajax({
url:".../../../server.php",
type:"POST",
data:"action=fetch&id="+post_id,
success:function(data){
let btn = $("#upvote_btn");
btn.html(data);
},
error:function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
}
};



//***** COMMENT REPLY SYSTEM **/////
let Comment_handler = {
submit_comment : (event, form)=>{
event.preventDefault();
let fetch_is_post_id = $("#is_post_id").val();
$fdata = $(form);
let post_comment_btn = document.querySelector("#post_comment_btn");
post_comment_btn.innerHTML = "Posting comment...";
$.ajax({
url:".../../../server.php",
type:"post",
data:$fdata.serialize(),
success:function(response){
post_comment_btn.innerHTML = "Post comment";
pop_up(window_info, response);
document.getElementById("comment-content").value = "";
Comment_handler.display_comment(fetch_is_post_id);
},
error:function(error){
alert("Failed to Post comment");
}
});
}, 
display_comment : (is_post_id)=>{
$.ajax({
url:".../../../server.php?get_comment=true&is_post_id="+is_post_id,
type:"GET",
success: function(data){
$("#comment_container").html(data);
}, 
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
}
};


//******* Log out notifier *********
setTimeout(function(){
$("#logout_notifier").css("display","none");
}, 5000);

function style_button(btn){
btn.style.color="red";
}
//******* OPERATION HANDLER********
// CHANGES PROFILE MENU BUTTON BACKGROUND TO RED, post, change password etc...
function operation_handler(evt){
event.preventDefault();
switch(parseInt(evt.parentElement.id)){
case 0: style_button(evt.parentElement); create_form();
break;
case 2: style_button(evt.parentElement); change_password();
break;
case 3: style_button(evt.parentElement); check_referral_id();
break;
case 4: style_button(evt.parentElement); create_post();
/* alert("Create Post is only accessible by Premium users."); return false;*/
break;
case 6: style_button(evt.parentElement); pop_up(window_info, "Trade FlexCoin is only accessible by Premium users."); return false;
break;
}


function create_form(){
pop_up(window_info, "Available for premium users only.");
return false;
}



function create_post(){
var parentDiv = document.querySelector("#actn_cont");
var sub_parent_div = document.createElement("div");
sub_parent_div.classList = "create_post";
var post_form = document.createElement("form");
var output_p = document.createElement("p");
output_p.classList = "output_p";
output_p.style.background="transparent";
post_form.setAttribute("action", "server.php");
post_form.setAttribute("enctype", "multipart/form-data");
var p = document.createElement("p");
p.innerHTML = "Write your well described and law abiding content in the box below.";
p.style.background="transparent";
var title_parag = document.createElement("p");
title_parag.innerHTML = "Title";
var title_parade_inp = document.createElement("input");
title_parade_inp.setAttribute("placeholder", "Please write post tile");
title_parade_inp.setAttribute("type", "text");
title_parade_inp.setAttribute("name", "post_title");
title_parag.appendChild(title_parade_inp);
var desc_p = document.createElement("p");
desc_p.innerHTML = "Description(tag)";
var desc_inp = document.createElement("input");
desc_inp.setAttribute("placeholder", "e.g: politics, news, sponsored etc");
desc_inp.setAttribute("type", "text");
desc_inp.setAttribute("name", "post_desc");
desc_p.appendChild(desc_inp);
var content_p = document.createElement("p");
var content_inp = document.createElement("textarea");
content_p.innerHTML = "Write your content below.";
content_inp.setAttribute("rows","8");
content_inp.setAttribute("cols","30");
content_inp.setAttribute("name","post_content");
content_inp.setAttribute("placeholder","Your content goes here...");
content_p.appendChild(content_inp);
var attachment_p = document.createElement("p");
attachment_p.innerHTML = "Attachment(png, jpeg, jpg, gif)";
var form_attachment = document.createElement("input");
form_attachment.setAttribute("type","file");
form_attachment.setAttribute("name","file[]");
form_attachment.setAttribute("id","file");
form_attachment.setAttribute("multiple","multiple");
attachment_p.appendChild(form_attachment);
var submit_btn = document.createElement("button");
submit_btn.setAttribute("type","submit");
submit_btn.setAttribute("name","post_content");
submit_btn.setAttribute("value","submit");
submit_btn.innerHTML = "Post content";
var top = document.createElement("a");
top.setAttribute("id","top");
sub_parent_div.appendChild(top);
parentDiv.appendChild(sub_parent_div);
sub_parent_div.appendChild(post_form);
post_form.appendChild(output_p);
post_form.appendChild(p);
post_form.appendChild(title_parag);
post_form.appendChild(desc_p);
post_form.appendChild(content_p);
post_form.appendChild(attachment_p);
post_form.appendChild(submit_btn);
var close_btn = document.createElement("button");
close_btn.innerHTML ="close form";
close_btn.style.background="rgba(255,0,0,0.5)";
post_form.appendChild(close_btn);
close_btn.addEventListener("click", function(event){
event.preventDefault();
this.parentElement.style.display="none";
});
post_form.addEventListener("submit", function(event){
event.preventDefault();
/*if(content_inp.value == "" || desc_inp.value == "" || title_parade_inp.value == ""){
return false;
}*/
if(title_parade_inp.value == "" 
|| desc_inp.value == ""
|| content_inp.value == ""){
pop_up(window_info, "Please fill these boxes or close the form.");
return false;
}
submit_btn.innerHTML = "Please wait...";
$.ajax({
url:".../../../server.php",
type:"POST",
dataType:"json",
contentType:false,
cache:false,
processData:false,
data: new FormData(this),
success:function(response){
submit_btn.innerHTML = "Post content";
pop_up(window_info, response.message);
/*submit_btn.setAttribute("disabled", "disabled");*/
submit_btn.style.background = "lightgreen";
close_btn.parentElement.style.display="none";

},
error:function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
});
}

function check_referral_id(){
var parentDiv = document.querySelector("#actn_cont");
var div1 = document.createElement("div");
var p1 = document.createElement("p");
var div = document.createElement("input");
var copy_referral_link = document.createElement("button");
copy_referral_link.innerHTML = "Copy Link";
p1.innerHTML = "<strong>Share:</strong> You can share your referral link on social medias. e.g Facebook, Instagram, Twitter etc to earn more.";
div.classList = "container";
div1.classList = "container";
div.classList = "operation_handler_container";
div.style.border="none";
div.style.textAlign="center";
div.style.width="100%";
div1.appendChild(p1);
parentDiv.append(div1);
parentDiv.append(div);
parentDiv.appendChild(copy_referral_link);
div.style.fontSize = "large";
div.value = "Please wait...";
$.ajax({
url: ".../../../server.php",
type:"get",
data:"referral_link",
success: function(response){
div.value = response;
},
error:function(error){
div.value = error;
}
});
copy_referral_link.addEventListener("click", function(){
div.select();
div.setSelectionRange(0, 9999999);
document.execCommand("copy");
copy_referral_link.innerHTML = "Link copied";
copy_referral_link.style.background = "lightgreen";
});
}

function change_password(){
var parentDiv = document.querySelector("#actn_cont");
var div = document.createElement("div");
div.classList = "container";
div.classList = "operation_handler_container";
parentDiv.append(div);
var form_paragraph = document.createElement("p");
var form_paragraph_2 = document.createElement("p");
form_paragraph_2.style.color = "green";
form_paragraph.innerHTML = "Type your new password.";
var form = document.createElement("form");
form.setAttribute("action", "server.php");
var pwd_input_1 = document.createElement("input");
var pwd_input_2 = document.createElement("input");
var pwd_btn = document.createElement("button");
var del_form = document.createElement("button");
pwd_input_1.setAttribute("type", "password");
pwd_input_1.setAttribute("name", "pwd");
pwd_input_1.setAttribute("placeholder", "Enter your new password");
pwd_input_2.setAttribute("type", "password");
pwd_input_2.setAttribute("name", "con_pwd");
pwd_input_2.setAttribute("placeholder", "Confirm new password");
pwd_btn.setAttribute("type", "submit");
pwd_btn.setAttribute("name", "submit");
pwd_btn.setAttribute("value", "submit");
pwd_btn.innerHTML = "Change password";
del_form.innerHTML = "Close form";
del_form.style.background = "rgba(254,0,0,0.3)";

form.appendChild(form_paragraph_2);
form.append(form_paragraph);
form.appendChild(pwd_input_1);
form.appendChild(pwd_input_2);
form.appendChild(pwd_btn);
form.appendChild(del_form);
div.appendChild(form);

del_form.addEventListener("click", function(event){
event.preventDefault();
this.form.style.display = "none";
});
form.addEventListener('submit', function(event){
event.preventDefault();
var form_data = "chng_pwd=1&pwd1="+pwd_input_1.value+"&pwd2="+pwd_input_2.value;
pwd_btn.innerHTML = "Please wait...";
$.ajax({
url:form.action,
type:"post",
dataType:"json",
data: form_data,
success: function(response){
pwd_btn.innerHTML = "Change password";
pop_up(window_info, response.ok);
if(response["status"] == 1){
var form_timeout = setInterval(function(){
form.style.display = "none";
}, 3000);
}else{
clearTimeout(form_timeout);
}
},
error: function(error){
form_paragraph_2.innerHTML = error;
}
});
});
}
}

//******DASHBOARD WALLET SETTING******

function collapse(btn){
btn.onclick = function(){
if(this.style.overflow == "visible" && this.style.maxHeight == "100%"){
this.style.overflow = "hidden";
this.style.maxHeight = "125px";
}else{
this.style.overflow = "visible";
this.style.maxHeight = "100%";
}
}
btn.onmouseover = function(){
this.style.overflow = "visible";
this.style.maxHeight = "100%";
}
}

//*****PROFILE PICTURE HANDLER*******

function listener(btn){
var upload = btn.nextElementSibling;
var file_img = document.querySelector("#profile_image");
var cover_img = document.querySelector("#cover_image");
var profileModal = document.querySelector("#profileUploadModal");
profileModal.style.display = "block";
var action = "";
var p = document.querySelector("#prof");
var c = document.querySelector("#cov");

profileModal.addEventListener("click", function(){
this.style.display = "none";
});

p.addEventListener("click", function(){
upload.click();
action = "profilePhoto";
});

c.addEventListener("click", function(){
upload.click();
action = "coverPhoto";
});


upload.addEventListener("change", function(e){
var fdata = new FormData(upload.parentElement);
 fdata.append("upload", true);
 fdata.append("action", action);

//var url = upload.parentElement.action;

if(action === "profilePhoto"){
file_img.src = ".../../../img/loader.gif";
}

if(action === "coverPhoto"){
cover_img.src = ".../../../img/loader.gif";
}

$.ajax({
url:".../../../server.php",
type:"POST",
dataType:"json",
contentType:false,
cache:false,
processData:false,
data: fdata,
success:function(response){

if(action === "coverPhoto"){
cover_img.src = response.file;
}

if(action === "profilePhoto"){
file_img.src = response.file;
}

profileModal.style.display = "none";

pop_up(window_info, response.message);
},

error:function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
});
}


var fc_container = document.querySelector("#fc-generated");
var change_notificator = document.querySelector(".change_notificator");
var change_notificator_gif = document.querySelector("#coin");
var url = document.getElementsByTagName("form")[0];
/*alert(url.action);*/
setInterval(function(){
var today = new Date();
var year = today.getFullYear();
var month = today.getMonth() + 1;
var day = today.getDate();
var hr = today.getHours();
var min = today.getMinutes();
var sec = today.getSeconds();

var dateWrapper = document.querySelector("#date");
var totalDate = (new Date()).toString().substr(3, 22);

dateWrapper.innerHTML=totalDate;
switch(hr > 12){
case true: document.querySelector("#greeting").innerHTML = "Evening!";
break;
case false: document.querySelector("#greeting").innerHTML = "Morning!";
break;
}
}, 1000);

//**SEARCH KEYS **//

function checkKeys(event, key){
event.preventDefault();
var output = document.querySelector("#search-box");
if(key.value.length == 0){
output.innerHTML = "Good <span id='greeting' >day!</span> your search results appears here.";
}else{
loader(true, false,  output);
$.ajax({
url:".../../../server.php",
type:"POST",
data:"search-input="+key.value,
success: function(data){
let e = "<div style='background:#aaa;padding:10px;border-radius:5px;'>"+data+"</div>";
loader(true, true, output, e);
/*e += data;*/
/*output.innerHTML = e;*/
},
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
}
}


function input_validation(val){
val.onkeyup = function(){
switch(this.type){
case "text": if(val.value.indexOf("1") != -1){this.style.border="1px solid red";}
break;
case "number": if(val.value.indexOf("a") != -1){this.style.border="1px solid red";}
break;
case "email": if(val.value.indexOf("@") == -1){this.style.border="1px solid red";}
break;
}
}
}


//******LINK LISTENER**********

function openLink(evt){
//*** POST MUST BE IN INDEX PAGE TO GET PAID //
var link_id = evt.id;
post_id = evt.id;
click_id = evt.previousElementSibling.value;
$.post( ".../../../server.php", {"payment_type":"click_payment", "post_id":post_id, "click_id":click_id},
function(data){ 
//...
});
}


//****CHECK IF USER VISITED PAGE BEFPRE PAYMENT ///

function process_payment(page_id, click_id){

let cdt = document.querySelector("#cdrfpr_c");

if(page_id=="undefined" || click_id == ""){
cdt.parentElement.style.display="none";
return false;
}

let cdt_timeout = setInterval(function(){
cdt.innerHTML -= 1;
if(cdt.innerHTML == 0){
clearInterval(cdt_timeout);
$.post(".../../../server.php",
{"payment_type":"process_click_payment", 
"page_id":page_id, 
"click_id":click_id},
function(data){
cdt.innerHTML = "$";
cdt.parentElement.style.backgroundImage="radial-gradient(lightgreen 40%, green)";
setTimeout(function(){cdt.parentElement.style.display="none";}, 3000);
//...
/*pop_up(window_info, data);*/
}
);

}
}, 1000);
}

$(document).ready(function(){   

let statusInputContainer = $('#profileStatusDiv');

showStatus(statusInputContainer);

$("#profileStatus").on("blur", function(){
$.post('server.php', {status:"update", statusVal:$(this).val()}, function(data){
$("#profileStatus").val("");
showStatus(statusInputContainer);
pop_up(window_info, "Status updated!");
});
});

$("#profileStatus").on("focus", function(){
showStatus($(this));
});

function showStatus(c){
c.val("Just a moment...");
c.html("<span style='font-size:10px;'>Loading status...</span>");
$.post('server.php', {status:"show"}, function(data){
c.html(data);
c.val(data);
});
}

//...FLEXCOIN GENERATOR

setInterval(function(){
$("#flex-generated").css({"transition":"1000ms"});
//let coin = 1/1000000 * Math.floor(Math.random() * 10);
$.post(".../../../server.php", {process_fc:true}, function(data){
$("#flex-generated").html(data);
$("#withdrawableCoin").html("NGN "+Math.floor(data * 500.00));
});
}, 2000);
//...

$("a.link").click(function(){
handler.clickPayment();
});

//*****REGISTRATION SCRIPT*******

$("#registration_form").submit(function(event){
event.preventDefault();
let output_container = document.querySelector("#output_containerR");
let $fdata = $(this);
let tc = document.querySelector("#tc");
if(tc.checked){
tc.value = 1;
}else{
tc.value = 0;
}
$.ajax({
url:$fdata.attr("action"),
type:"POST",
dataType:"json",
data:$fdata.serialize(),
success: function(data){
  alert(data)
window.location = "#top";

switch(data.res){
case 1: output_container.innerHTML = data.fb; $("#epin_form").html(data.message);
break;
case 2: output_container.innerHTML = data.message;
break;
case 3: output_container.innerHTML = data.message;
break;
default:
alert(data)
}
}, 
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
});



function verify_payment(){
//event.preventDefault();


//let output = document.querySelector("#epin_output");
/*$.ajax({
url:".../../../server.php",
type:"POST",
data:$(this).serialize(),
success:function(data){
var output = document.querySelector("#epin_output");
output.html(data);
},
error:function(jxHQR, textStatus){
alert("An error occurred. "+textStatus);
}
});
*/
}



//*****LOG IN SCRIPT*******
$("#login_form").submit(function(event){
event.preventDefault();
var $fdata = $(this);
var login_btn = document.querySelector("#login_btn");
var output_container = document.querySelector("#output_containerL");
login_btn.innerHTML = "Wait...";
output_container.innerHTML = "";
$.ajax({
type:"POST",
dataType:"json",
url:$fdata.attr("action"),
data:$fdata.serialize(),
success: function(data){
if(data == 1){
location = "index.php";
login_btn.style.background = "lightgreen";
login_btn.innerHTML = "Sign in";
}else{
output_container.innerHTML = data;
login_btn.style.background = "#eee";
login_btn.innerHTML = "Sign in";
}
}, 
error: function(jxHQR, textStatus){
pop_up(window_info, textStatus);
}
});
});




//*****SUBSCRIBE FOR WEEKLY NEWS SCRIPT*******
$("#sbcr_form").submit(function(event){
event.preventDefault();
var $fdata = $(this);
var output_container = $("#output_containerN");
output_container.html("Just a sec...");
$.ajax({
type:"POST",
url:$fdata.attr("action"),
data:$fdata.serialize(),
success: function(data){
pop_up(window_info, data);
output_container.html("");
}, 
error: function(error){
pop_up(window_info, "Connection problem...");
}
});
});
});