<?php
declare(strict_types = 1);
require_once "config.php";

function writeErrors($fileAddress, $errorMsg) {
  $file = fopen($fileAddress, "a");
  fwrite($file, $errorMsg);
  fclose($file);
  //Temporary use
  return "An error has occurred.";
}


//**** Profile Management Handler******//
if (isset($_POST["manageProfile"])) {

  $type = $_POST["type"];
  $val = $_POST["actionVal"];
  $errorFile = "flex_error.txt";

  //echo $type.":".$val; ;

  function getName($name) {
    if (isset($name)) {
      $name = explode(" ", $name);
      $firstName = $name[0];
      $otherNames = $name[1] ." ". $name[2] ?? $name[1] ?? "";
      return ["fn" => $firstName,
        "ln" => $otherNames
      ];
    }
  }



  try {

    $val = htmlentities($val);

    switch ($type) {

      case "fullName" :

        if (empty(trim($val))) {
          echo "No name set";
          exit;
        }
        $fn = trim(getName($val)["fn"]);
        $ln = trim(getName($val)["ln"]);
        $fn = $con->real_escape_string($fn);
        $ln = $con->real_escape_string($ln);
        if (!$con->query("UPDATE user_biodata SET fn='".$fn."', ln='".$ln."' WHERE id='".$_SESSION["id"]."'")) {
          throw new Exception("
-----------PROFILE MANAGEMENT ERRORS--------------\n
			".$con->error."
-------------------------------------------------\n\n
  ");
        } else {
          echo "Name updated";
        }


        break;

      case "email" :

        $email = sanitize($con->real_escape_string($val));

        if (empty(trim($email))) {
          echo "No email set.";
          exit;
        }

        if ($con->query("SELECT em FROM user_biodata WHERE em='".$email."'")->num_rows > 0) {
          echo "You cannot use this Email";
          exit;
        }

        $to = $_SESSION["em"];
        $subject = "Profile Management Notification";

        $body = "Dear ".$_SESSION["fn"]." \n <br>
This is to notify you that your default email was changed on".
        date("M d, Y h:m:sa").". \n
New email:<b>".$email."</b>
If you didnt initiate this action,
Hurry up now and notify us.\n
call/WhatsApp: <a href='tel:07061017993'> Customer support Line</a>
or use the online customer support ticket. Best regards.";

        $headers = "From: no-reply@flexpay.tech \n".
        "To: ".$_SESSION["em"]."\n";

        if (!$con->query("UPDATE user_biodata SET em='".$email."' WHERE id='".$_SESSION["id"]."'")) {
          throw new Exception("
-----------PROFILE MANAGEMENT ERRORS--------------\n
			".$con->error."
-------------------------------------------------\n\n
            ");
        } else {
          echo "Email updated";
        }
        if (!mail($to, $subject, $body, $headers)) {
          throw new Exception("
-----------PROFILE MANAGEMENT ERRORS--------------\n
			Email was not sent
-------------------------------------------------\n\n
            ");
        }

        break;

      case "phone" :

        if (empty(trim($val))) {
          echo "No Number set";
          exit;
        }

        $phone = $con->real_escape_string(trim($val));
        if (strlen($phone) < 10 || strlen($phone) > 12) {
          echo "Invalid Phone number.";
          exit;
        }

        if ($con->query("SELECT ph FROM user_biodata WHERE ph='".$phone."'")->num_rows > 0) {
          echo "You cannot use this Number";
          exit;
        }

        if (!$con->query("UPDATE user_biodata SET ph='".$phone."' WHERE id='".$_SESSION["id"]."'")) {
          throw new Exception("\n Error updating Phone number in Profile Management Setting \n ".$con->error."\n");
        } else {
          echo "Phone number updated";
        }


        break;

      case "gender" :

        $gender = $con->real_escape_string(trim($val));
        $gender = strtolower($gender);
        $availGender = array("male", "female", "others");
        if (!in_array($gender, $availGender)) {
          echo "Gender is not supoorted (specify \"others\" instead).";
          exit;
        }

        if (!$con->query("UPDATE user_biodata SET gender='".$gender."' WHERE id='".$_SESSION["id"]."'")) {
          throw new Exception("\n Error updating gender number in Profile Management Setting \n ".$con->error."\n");
        } else {
          echo "Gender updated";
        }

        break;


      case "dob" :

        $dob = $con->real_escape_string($val);
        $dob = explode(".", $dob);

        $preg = "/[0-9][.]/";

        if (!preg_match($preg, $val)) {
          echo "Not a correct format";
          exit;
        }

        if (($dob[0] < 1) || ($dob[0] > 31)) {
          echo "Enter format[1 < DD < 31]";
          exit;
        }

        if (($dob[1] < 1) || ($dob[1] > 12)) {
          echo "Enter format[MM <> 31]";
          exit;
        }

        if (2015 < $dob[2] || strlen($dob[2]) <> 4) {
          echo "Enter format [YYYY < 2015]";
          exit;
        }

        $dob = implode(".", $dob);

        if (!$con->query("UPDATE user_biodata SET dob='".$dob."' WHERE id='".$_SESSION["id"]."'")) {
          throw new Exception("\n Error updating dob number in Profile Management Setting \n ".$con->error."\n");
        } else {
          echo "DOB updated";
        }

        break;

      case "relationship" :

        if (empty($val)) {
          echo "Not set";
          exit;
        }
        $rel = $con->real_escape_string(trim($val));
        $rel = strtolower($rel);
        $availRel = array(
          "single", "married",
          "complicated", "divorced",
          "searching", "separated",
        );

        if (!in_array($rel, $availRel)) {
          echo "Not supported (Tip: searching|single|complicated)";
          exit;
        }

        if (!$con->query("UPDATE user_biodata SET relationship='".$rel."' WHERE id='".$_SESSION["id"]."'")) {
          throw new Exception("\n Error updating Relationship number in Profile Management Setting \n ".$con->error."\n");
        } else {
          echo "Updated";
        }

        break;

      case "unfollow" :
        $user = $_POST["user"];
        if (empty($val)) {
          exit;
        }
        if (!$con->query("DELETE FROM friends WHERE my_id='".$_SESSION["id"]."' AND friend_id='".$val."' OR friend_id='".$_SESSION["id"]."' AND my_id='".$val."' ")) {
          throw new Exception("\n---ERROR DELETING/UNFOLLOWING FRINED IN PROFILE MANAGMENT---");
          exit;
        } else {
          echo "<h2>You unfollowed ".$user."</h2>";
        }


        break;

    }
  }catch(Exception $e) {
    writeErrors($errorFile, $e->getMessage());
    //echo $e->getMessage();
  }


}


//Update status Handler
if (isset($_POST["status"])) {

  $action = $_POST["status"];

  if ($action === "update") {

    $status = $_POST["statusVal"];

    $status = (empty(trim($status)))? "Hey! I'm new to Flexpay😘": $status;

    $status = $con->real_escape_string($status);

    //$status = (empty(trim($status)) || $status == null) ? "Hey! I'm new to Flexpay😘" : $status;

    $con->query("UPDATE user_biodata SET status='".$status."' WHERE em='".$_SESSION['em']."' OR ph='".$_SESSION["ph"]."'");
  }

  if ($action === "show") {
    $sql = $con->query("SELECT status FROM user_biodata WHERE ph='".$_SESSION['ph']."' OR em='".$_SESSION['em']."'");
    if ($sql->num_rows > 0) {
      $r = $sql->fetch_object();
      $st = ($r->status == "" || $r->status == null) ? "Hey! I'm new to Flexpay😘" : $r->status;
      echo $st;
    } else {
      echo "Error Fetching status";
    }
  }
  $con->close;
}


//***** ADD_CHAT_HANDLER ****////
if (isset($_POST["chat"])) {
  $user_id = $_POST["user_id"];
  $chat_id = $_POST["chat_id"];
  $session_id = $_SESSION["id"];
  $action = $_POST["chat"];
  $message = $_POST["message"];
  $query = null;
  $q = null;
  $response = array();
  if ($session_id != $user_id) {
    if ($action === "add") {
      //ADD FRINEDS
      $query_1 = $con->query("SELECT friend_request.* FROM friend_request
WHERE from_id='".$session_id."'
AND to_id='".$user_id."'
OR from_id='".$user_id."' AND to_id='".$session_id."'");

      $query_2 = $con->query("SELECT friends.* FROM friends
 WHERE my_id='".$session_id."'
 AND friend_id='".$user_id."'
 OR friend_id='".$user_id."' AND my_id='".$session_id."'
 ");
      if (($query_1->num_rows + $query_2->num_rows) > 0) {
        $response[0] = 0;
        $response[1] = "Already sent";
        $response[2] = "You already sent friend request to them. You will be notified once them accept to be your friend.";
      } else {
        $q = $con->query("INSERT INTO friend_request (from_id, to_id, approval)
 VALUES ('".$session_id."','". $user_id."', '0')");
        if ($q) {
          $response[0] = 1;
          $response[1] = "Sent";
          $response[2] = "Your request has been sent.";
        } else {
          $response[0] = 2;
          $response[1] = "Error";
          $response[2] = "Oops, unknown error has occupied. please try again.";
        }
      }
    }
    if ($action === "accept") {
      //ACCEPT FRIENDS REQUEST
      $q = $con->query("SELECT * FROM friends WHERE my_id='".$session_id."' AND friend_id='".$user_id."'");
      if ($q->num_rows > 0) {
        $response[0] = 0;
        $response[1] = "Zzz";
        $response[2] = "You and this person are friends already.";
        $con->query("DELETE FROM `friend_request` WHERE ((`to_id` = '".$session_id."') AND (`from_id` = '".$user_id."'))");
      } else {
        $query = $con->query("
INSERT INTO friends (my_id, friend_id)
 SELECT friend_request.to_id, friend_request.from_id
 FROM friend_request
 WHERE friend_request.to_id='".$session_id."'");

        $query .= $con->query("
INSERT INTO friends (friend_id, my_id)
 SELECT friend_request.to_id, friend_request.from_id
 FROM friend_request
 WHERE friend_request.to_id='".$session_id."'");

        if ($query) {
          $response[0] = 1;
          $response[1] = "Accepted";
          $response[2] = "You have accepted their friend request.";
          $con->query("DELETE FROM `friend_request` WHERE ((`to_id` = '".$session_id."') AND (`from_id` = '".$user_id."'))");
        } else {
          $response[0] = 2;
          $response[1] = "Error";
          $response[2] = "Oops, unknown error has occured. please try again.";
        }
      }
    }
  } else {
    $response[0] = 3;
    $response[1] = "Zzz";
    $response[2] = "Sorry, but you can not add yourself.";
  }


  //REJECT FRIENDS REQUEST.

  if ($action === "reject") {
    $query = $con->query("DELETE FROM `friend_request` WHERE ((`to_id` = '".$session_id."') AND (`from_id` = '".$user_id."'))");
    if ($query) {
      $response[0] = 0;
      $response[1] = "Rejected";
      $response[2] = "You rejected this friendship";
      $con->query("DELETE FROM `friend_request` WHERE ((`to_id` = '".$session_id."') AND (`from_id` = '".$user_id."'))");
    } else {
      $response[0] = 1;
      $response[1] = "Error";
      $response[2] = "Oops, unknown error has occured. please try again.";
    }
  }


  if ($action === "save_chat") {
    $query = $con->query("SELECT chat_tmp_chat FROM tmp_chat
WHERE to_tmp_id='{$chat_id}' AND from_tmp_id='{$session_id}'
OR
from_tmp_id='{$chat_id}' AND to_tmp_id='{$session_id}'
      ");
    if ($query->num_rows > 0) {

      $con->query("UPDATE tmp_chat SET chat_tmp_chat='{$message}', sender_id='{$session_id}'
WHERE to_tmp_id='{$chat_id}' AND from_tmp_id='{$session_id}'
OR
from_tmp_id='{$chat_id}' AND to_tmp_id='{$session_id}'
        ");
      $con->query("INSERT INTO private_chat (from_id, to_id, chat) VALUES ('".$session_id."','".$chat_id."','".$message."')");

    } else {
      $con->query("INSERT INTO tmp_chat (from_tmp_id, to_tmp_id, chat_tmp_chat, sender_id) VALUES ('".$session_id."','".$chat_id."','".$message."', '".$session_id."')");
      $con->query("INSERT INTO private_chat (from_id, to_id, chat) VALUES ('".$session_id."','".$chat_id."','".$message."')");
    }
  }



  echo json_encode($response);
  $con->close();
}

// *** HANDLER USER PIC ******//
if (isset($_POST["fetch_user_pic"])) {
  $user_id_for_pic = $_POST["fetch_user_pic"];
  $query = $con->query("SELECT user_biodata.id, user_biodata.profile_pic FROM user_biodata WHERE user_biodata.id = '".$user_id_for_pic."'");
  while ($r = $query->fetch_assoc()) {
    echo json_encode(array("pic" => $r["profile_pic"]));
  }
  $con->close();
}

//**** SHOW USER ****///

if (isset($_POST["show_user"])) {
  $show_user_with_id = $_POST["show_user"];

  $query = "SELECT * FROM user_biodata WHERE id = '".$show_user_with_id."'";
  $query = $con->query($query);
  while ($r = mysqli_fetch_assoc($query)) {
    $status = ($r["status"] == "")?"Hi! I'm new to Flexpay😍":$r["status"];
    $you = ($r["id"] == $_SESSION["id"])? "You" : $r["fn"];
    $online = ($r["online"])?"<span style='color:green;'>Online</span>":"<span style='color:red;'>Offline</span>";
    echo '<div class="user_modal">
<div onclick="" class="toggler" >
<span></span>
<span></span>
<span></span>
<div class="toggler_menu" >
<p>Report</p>
<p>Upvote</p>
</div>
</div>

<div onclick="modal_handler(event)" class="close_modal" ><h4>&times;</h4></div>
<div class="user_icon" ><img src=".../../../profile/dp/'.$r["profile_pic"].'" ></div>
<h4>'.$r["fn"].' '.$r["ln"].'</h4>
<br>
<p style="color:#aaa;text-align:center;font-size:25px;overflow:hidden;">'.$status.'...</p>
<br>
<form>
<button><i class="fa fa-user-plus" ></i>•Add</button>
<button><i class="fa fa-comment" ></i>•Chat</button>
</form>
</div>
<div class="user_info user_modal" >
<p><i class="fa fa-user" >•</i>Status: '.$online.'</p>
<p><i class="fa fa-map-marker" >•</i>From: <span>Lagos</span></p>
<p><i class="fa fa-users" >•</i>Friends: <span>100+</span></p>
</div>
    ';
  }
  mysqli_free_result($query);

  $query = "SELECT user_biodata.id, user_biodata.fn,
						     user_biodata.ln,
						     user_biodata.online,
							 user_biodata.profile_pic,
							 post.id,
							 post.user_id,
							 post.post_desc,
							 post.post_cont,
							 post.post_p_att,
							 DATE_FORMAT(post.date, '%a, %D %Y•%h:%i:%s') as date
							 FROM user_biodata JOIN
							 post ON user_biodata.id=post.user_id
							 WHERE user_biodata.id=".$show_user_with_id." ORDER BY
							 post.id DESC
						";
  $query = $con->query($query);
  if (mysqli_num_rows($query) > 0) {
    while ($q = mysqli_fetch_assoc($query)) {

      $upvote = $con->query("SELECT upvote FROM post_upvotes WHERE post_id='".$q["id"]."'");
      $upvote = $upvote->num_rows;


      $comment = $con->query("SELECT post_id FROM comments WHERE post_id='".$q["id"]."'");
      $comment = $comment->num_rows;

      $post_att = $con->query("SELECT post_att FROM post_att WHERE post_id='".$q["id"]."'");
      $post_att = $post_att->num_rows;

      $p_attachment = ($q["post_p_att"] == "") ? null : '<img src=".../../../uploads/'.$q["post_p_att"].'" >';
      $response = '
<a href=".../../../post/now.php?pid='.$q["id"].'">
<div class="user_modal" >
<h5>Post by <b>'.$q["fn"].' '.$q["ln"].'•<span style="color:#aaa;">Click me to read more.</span></b></h5>
<hr style="opacity:0.2;" >
<div class="modal_header" >
<div class="modal_user_icon" ><img src=".../../../profile/dp/'.$q["profile_pic"].'" ></div>
<div class="modal_user_info" >
<p>'.$q["fn"].' '.$q["ln"].'</p>
<p>'.$q["date"].'</p>
<span>'.$q["post_desc"].'</span>
</div>
</div>
<div class="modal_body" >
<div class="modal_body_parag" ><p>'.substr($q["post_cont"], 0, 150).'...•<span style="color:#aaa;">Read more</span></p></div>
<div class="post_att" >'.$p_attachment.'</div>
<div class="post_att_no" ><p>Photos•'.$post_att.'</p></div>
</div>
<div style="text-align:center;" class="modal_footer" >
<form>
<input type="hidden" >
<input type="hidden" >
<button ><i onclick="style_button(this); return false;" class="fa fa-heart">Likes•'.$upvote.'</i></button>
<button ><i onclick="style_button(this); return false;" class="fa fa-comment">Comments•'.$comment.'</i></button>
<button ><i onclick="style_button(this); return false;" class="fa fa-share">Shared•10<sup>+</sup></i></button>
</form>
</div>
</div>
</a>

      ';

      echo($response);
    }
  } else {
    echo "<div class='user_modal'><h4>".$you." has no posts yet.</h4></div>";
  }
}


//***UPVOTE AND PAYMENT HANDLER///
if (isset($_POST["action"])) {
  if ($_POST["action"] === "upvote") {

    $post_id = $_POST["post_id"];
    $poster_id = $_POST["poster_id"];
    $who_likes_post = $_POST["liker_id"];
    $pay_for_liking = null;

    $query = "SELECT post_id, who_likes_post_id FROM post_upvotes WHERE post_id='".$post_id."' AND who_likes_post_id='".$who_likes_post."'";
    $query = $con->query($query);
    if (mysqli_num_rows($query) > 0) {
      exit();
    }
    $sql = "INSERT INTO post_upvotes (post_id, poster_id, who_likes_post_id, upvote)
		VALUES  ('".$post_id."','".$poster_id."','".$who_likes_post."','1')";
    $sql = $con->query($sql);



    $sql = $con->query("SELECT activity FROM all_earning WHERE user='".$poster_id."'");
    if ($sql->num_rows > 0) {
      while ($pay = $sql->fetch_assoc()) {
        $pay_for_liking = $pay["activity"] + 3;
        $con->query("UPDATE all_earning SET activity='".$pay_for_liking."' WHERE user='".$poster_id."'");
      }
    }

    $sql->close;
    $con->close;
  }

  if ($_POST["action"] === "fetch") {
    $post_id = $_POST["id"];
    $query = "SELECT id FROM post_upvotes WHERE post_id='".$post_id."'";
    $query = $con->query($query);
    $num_row = mysqli_num_rows($query);
    echo("<i style='color:;' class='fa fa-heart'>•".$num_row."</i>");

  }
  $query->close;
  $con->close;
}



//*** COMMENT HANDLER *****///
if (isset($_POST["comment"]) or isset($_POST["comment-content"])) {

  $comment_content = sanitize($_POST["comment-content"]);
  $comment_content = htmlentities($comment_content);



  $post_id = $_POST["post_id"];

  $user_id = $_POST["user_id"];

  $date = date("d/m•H:i:sa");

  if (empty($comment_content)) {
    echo "Comment field cannot be empty";
  } else {
    $sql = $con->prepare("INSERT INTO comments (post_id, comment, who_comments_id, dat) VALUES (?,?,?,?)");
    $sql->bind_param("isis", $post_id, $comment_content, $user_id, $date);
    $sql->execute();
    if ($sql) {
      echo "Comment posted successfully";
    } else {
      echo "Comment post failed";
    }
  }
  $sql->close;
  $con->close;
}


//***** FETCH COMMENTS *******//
if (isset($_GET["get_comment"])) {
  $is_post_id = $_GET["is_post_id"];
  $sql = "SELECT * FROM user_biodata
JOIN comments
ON comments.who_comments_id=user_biodata.id
JOIN post ON
comments.post_id=post.id
WHERE comments.post_id = '".$is_post_id."'
ORDER BY comments.dat";
  $sql = $con->query($sql);
  if ($sql->num_rows > 0) {
    while ($f = mysqli_fetch_array($sql)) {

      echo '<div class="comment-container" >';
      echo '<input type="hidden" value="'.$f["who_comments_id"].'">';
      echo'<div class="author_details" >';
      echo '<div class="author_icon" >';
      echo '<div class="premium-identifier" >';
      echo '<img src=".../../../img/done.png" ></div><img src=".../../../profile/dp/'.$f["profile_pic"].'" >';
      echo '</div>';
      echo '<div class="author_content_details" >';
      echo '<p>'.$f["fn"]." ". $f["ln"].'</p>';
      echo '<p>'.$f["dat"].'<span class="author_tag" >'.$f["post_desc"].'</span></p>';
      echo '</div>';
      echo '</div>';
      echo '<p>'.html_entity_decode($f["comment"]).'</p>';
      echo '<form>';
      echo '<div class="comments_action_btn" >';
      echo '<button>Reply</button>';
      echo '</div></form>';
      echo '</div>';
    }
  } else {
    echo '<div class="container" >';
    echo '<h1 style="text-align:center;color:grey;font-weight:lighter;">No comments available.</h1>';
    echo '</div>';
  }
  $con->close;
}




//****POST REQUEST HANDLER ***///
if (isset($_REQUEST["r"]) && isset($_GET["pid"])) {
  $r = $_REQUEST["r"];
  if ($r === "del-post") {
    $pid = $_GET["pid"];
    $sql = mysqli_query($con, "DELETE FROM post WHERE id ='".$pid."' ");
    if ($sql) {
      mysqli_query($con, "DELETE FROM post_att WHERE post_id ='".$pid."' ");
      header("Location: index.php?res=d");
    } else {
      echo("post not deleted".mysqli_error($sql));
    }
    mysqli_close($sql);
    mysqli_close($con);
  }
}

if (isset($_REQUEST["r"]) && isset($_GET["pid"])) {
  $r = $_REQUEST["r"];
  if ($r === "report-post") {
    $pid = $_GET["pid"];
    $sql = mysqli_query($con, "UPDATE post SET post_approval='0'");
    if ($sql) {
      header("Location: index.php?res=This post will be reviewed. Thanks.");
    } else {
      echo("your request could not be finished. ".mysqli_error($sql));
    }
  }
  mysqli_close($con);
}


//******SEARCH HANDLER ******//

if (isset($_POST["search-input"]) && !empty($_POST["search-input"])) {
  $q = sanitize($_POST["search-input"]);
  $q = mysqli_real_escape_string($con, $q);
  $q = htmlentities($q);
  $q = strtolower($q);

  $sql = "SELECT * FROM post WHERE post_desc LIKE '%".$q."%' ORDER BY date DESC";
  $sql = mysqli_query($con, $sql);
  if ($sql->num_rows) {
    foreach ($sql as $post) {


      echo '<div style="background:#eee;" class="container" >';
      echo '<div class="toggler_container" >
<div class="toggler" onclick="
if(this.nextElementSibling.style.display==\'block\'){
this.nextElementSibling.style.display=\'none\';
}else{
this.nextElementSibling.style.display=\'block\';
}
      ">



</div>
<div class="toggler_menu">
<p><a href="server.php?r=del-post&pid='.$post["id"].'"><i class="fa fa-trash"></i>&nbsp; Delete post</a></p>
<hr style="opacity:0.1;padding:3px;">
<p><a href="server.php?r=report-post&pid='.$post["id"].'"><i class="fa fa-flag"></i>&nbsp; Flag post</a></p>
<hr style="opacity:0.1;padding:3px;">
<p><a onclick="alert(\'Only Premium users can pin post.\n\r Kindly upgrade your account now..\');return false;" href="server.php?r=pin-post&pid='.$post["id"].'"><i class="fa fa-pin"></i>&nbsp; Pin to top</a></p>
</div>
</div>';
      echo '<div class="tag" ><p style="font-weight:bold;color:#333;font-size:8px;">
&nbsp; &nbsp;
<i class="fa fa-eye">10+</i>
&nbsp;&nbsp;
<i class="fa fa-heart">32</i>
</p></div>';
      echo '<a onclick="openLink(event, this);" id="1" href=".../../post/now.php?pid='.$post["id"].'&t='.$post["date"].$post["id"].'" >';
      echo '<div class="card-wrapper" >';
      echo '<div class="icon" ><img src=".../../../uploads/'.$post["post_p_att"].'" alt="'.$post["title"].'"></div>';
      echo '<div class="content" >';
      echo '<p style="font-size:small;">'.substr(ucfirst($post["post_title"]), 0, 36).'...</p>';
      echo "<br>";
      echo '<input type="hidden" >';
      echo '<p>'.substr(ucfirst(htmlspecialchars_decode($post["post_cont"])), 0, 40).'... <span style="color:grey;font-size:10px;">Read more</span></p>';
      echo '</div>';
      echo '</div>';
      echo '</a>';
      echo '</div>';
    }
  } else {
    echo "<div class='container'><h4>No match found.</h4></div>";
  }
  $con->close;
}


//******** CREATE CONTENT HAMDLER   **********
if (isset($_POST["post_content"])) {
  $valid_ext = array("jpeg", "jpg", "gif", "png");
  $ok = true;
  $feedback = array();
  $counter = 0;

  $title = sanitize($_POST["post_title"]) ?? "";
  $title = $con->real_escape_string($title);
  $desc = sanitize(mysqli_real_escape_string($con, $_POST["post_desc"])) ?? "";
  $desc = strtolower($desc);
  $cont = sanitize($_POST["post_content"]) ?? "";
  $cont = htmlentities($cont);
  /*$cont = htmlspecialchars_decode($cont);*/
  $cont = mysqli_real_escape_string($con, $cont);
  $cont = wordwrap($cont, 70);
  $att = (!empty($_FILES["file"]["name"][0])) ? $_FILES["file"]["name"][0] : null;
  $post_id = 0;




  if (empty($title) || empty($cont) || empty($desc)) {
    $feedback["message"] = "Please fill these boxes with contents.";
    $ok = false;
  }

  if ($_FILES["file"]["size"][0] > 1) {

    for ($i = 0; $i < count($_FILES["file"]["name"]); $i++) {

      if ($_FILES["file"]["error"][$i] > 0) {
        $feedback["message"] = "ERROR: ".$_FILES["file"]["error"];
        $ok = false;
      }

      if ($_FILES["file"]["size"][$i] > 5000000) {
        $feedback["message"] = "File size too large.";
        $ok = false;
      }

      if (!in_array(strtolower(pathinfo($_FILES["file"]["name"][$i], PATHINFO_EXTENSION)), $valid_ext)) {
        $feedback["message"] = "Invalid/No file extension was detected.";
        $ok = false;
      }

    }
  } else {
    $has_file = false;
  }

  if ($ok == true) {


    $sql = "INSERT INTO post (user_id, post_title, post_desc, post_cont, post_approval, post_p_att)
			VALUES (
			'".$_SESSION["id"]."',
			'".$title."',
			'".$desc."',
			'".$cont."',
			 '0' ,
			 '".$att."')";
    $sql = $con->query($sql);
    if ($sql) {
      $post_id = $con->insert_id;

      for ($i = 0; $i < count($_FILES["file"]["name"]); $i++) {
        $counter++;

        $con->query("INSERT INTO post_att(post_id, post_att)
					VALUES (
					'".$post_id."',
					'".$_FILES["file"]["name"][$i]."')");

        move_uploaded_file($_FILES["file"]["tmp_name"][$i], "uploads/".$_FILES["file"]["name"][$i]);
      }
      $feedback["message"] = "Your content is being reviewed. Thanks.";
    } else {
      $feedback["message"] = "<p style='color;red;'>Content failed to upload.</p>";
    }
  }
  echo(json_encode($feedback));
  $sql->close;
  $con->close;
}

//********PROFILE PICTURE HANDLER*********
if (isset($_POST["upload"])) {
  if (isset($_FILES["upload"]) && !empty($_FILES["upload"])) {
    $file_name = $_FILES["upload"]["name"];
    $file_tmp_name = $_FILES["upload"]["tmp_name"];
    $file_size = $_FILES["upload"]["size"];
    $file_new_ = explode(".", $file_name);
    $file_ext = strtolower(end($file_new_));
    $file_new_name = "";
    $address = "";
    $passed = false;
    $ok = true;
    $ext = array("jpg", "jpeg", "png");

    $response = array();

    $action = $_POST["action"];


    if ($file_size > 5000000) {
      $response["message"] = "File too large";
      $ok = false;
    }

    if (!in_array($file_ext, $ext)) {
      $response["message"] = "Invalid extension detected";
      $ok = false;
    }


    if ($ok == true) {

      if ($action === "profilePhoto") {
        $mess = "Profile photo";
        $address = "profile/dp/";
        $file_new_name = "FP_IMG_PROFILE_".date("Ymdhis").rand().".".$file_ext;
        $con->query("UPDATE user_biodata SET profile_pic='".$file_new_name."' WHERE ph='".$_SESSION["ph"]."' AND em='".$_SESSION["em"]."' ");
        if (move_uploaded_file($file_tmp_name, $address.$file_new_name)) {
          $stmt = $con->query("SELECT profile_pic FROM user_biodata WHERE ph='".$_SESSION["ph"]."' AND em='".$_SESSION["em"]."'");
          $data = $stmt->fetch_object();
          $response["file"] = ".../../../".$address.$data->profile_pic;
          $passed = true;
        }
      }

      if ($action === "coverPhoto") {
        $mess = "Profile cover";
        $address = "profile/cover/";
        $file_new_name = "FP_IMG_COVER_".date("Ymdhis").rand().".".$file_ext;
        $con->query("UPDATE user_biodata SET cover_pic='".$file_new_name."' WHERE ph='".$_SESSION["ph"]."' AND em='".$_SESSION["em"]."' ");
        if (move_uploaded_file($file_tmp_name, $address.$file_new_name)) {
          $stmt = $con->query("SELECT cover_pic FROM user_biodata WHERE ph='".$_SESSION["ph"]."' AND em='".$_SESSION["em"]."'");
          $data = $stmt->fetch_object();
          $response["file"] = ".../../../".$address.$data->cover_pic;
          $passed = true;
        }
      }

      if ($passed === true) {

        $response["message"] = $mess." uploaded successfully";

      } else {
        $response["message"] = "File upload failed.".$con->error;
      }
    }
  }
  echo(json_encode($response));
}




//********REFERRAL LINK HANDLER*******
if (isset($_GET["referral_link"])) {
  $sql = "SELECT * FROM user_biodata WHERE em='".$_SESSION["em"]."' AND ph='".$_SESSION["ph"]."' LIMIT 1";
  $sql = mysqli_query($con, $sql);
  if (mysqli_num_rows($sql) == 1) {
    foreach ($sql as $count => $row) {
      $response = "http://".$_SERVER["HTTP_HOST"]."?rdr=fpid&url=".$row["fprid"];
    }
  } else {
    $response = "http://".$_SERVER["HTTP_HOST"]."?rdr=fpid&url=flexpay";
  }
  echo($response);
}


//********CHANGE PASSWORD HANDLER*******
if (isset($_POST["chng_pwd"])) {
  $response = array(
    "ok" => "Password has been changed successfully",
    "status" => 1
  );
  echo(json_encode($response));
}

//*******FLEXCOIN PROCESSOR********
if (isset($_POST["process_fc"])) {

  //$a = (double)$_POST["process_fc"];

  try {
    $a = rand(10, 10000);
    $a = ((1 / $a) / 1000);
    if (!is_float($a)) {
      exit("00.00");
    }

    $query = $con->query("SELECT fc FROM all_earning WHERE user='".$_SESSION['id']."'");

    $row = $query->fetch_object();

    $sum = (double)$row->fc + $a;
    $stmt = $con->query("UPDATE all_earning SET fc=".$sum." WHERE user='".$_SESSION["id"]."' ");
    echo $row->fc;
    throw new Exception("
      \n
------------- FLEX COIN GENERATE ERRORS ------------------- \n
ERROR->".$con->error."\n
FILE->".__FILE__."\n
LINE->".__LINE__."\n
DATE->".date("Y-m-d h:i:m:sa")."\n
-----------------------------------------------------------\n\n"
    );
  }catch (Exception $e) {
    $file = fopen("flex_errors.txt", "w");
    fwrite($file, $e->getMessage());
    fclose($file);
    exit();
  }
}

//*******LINK PAYMENT PROCESSOR*********
if (isset($_POST["payment_type"])) {
  $post_id = $click_id = null;
  $payment_type = $_POST["payment_type"];
  if ($payment_type === "click_payment") {
    $post_id = sanitize($_POST["post_id"]) ?? "";
    $click_id = sanitize($_POST["click_id"]) ?? "";
    if ($post_id == "undefined" || empty($click_id)) {
      exit();
    }
    $sql = "SELECT * FROM clicks WHERE click_id='".$click_id."' AND page_id='".$post_id."'";
    $sql = $con->query($sql);
    if ($sql->num_rows > 0) {
      exit();
    } else {
      $sql = $con->query("INSERT INTO clicks (click_id, page_id) VALUES ('".$click_id."','".$post_id."')");
    }
    $sql->close;
    $con->close;
  }
  //****PROCESS PAYMENT ////
  if ($payment_type === "process_click_payment") {
    $post_id = sanitize($_POST["page_id"]) ?? "";
    $click_id = sanitize($_POST["click_id"]) ?? "";

    $sql = $con->query("SELECT page_verification FROM clicks WHERE click_id='".$click_id."' AND page_id='".$post_id."'");
    if ($sql->num_rows > 0) {
      while ($p = $sql->fetch_assoc()) {
        $pmnt_vfcn = (int)$p["page_verification"];
      }
      if ($pmnt_vfcn == 1) {
        exit();
      } else {
        $sql = $con->query("UPDATE clicks SET page_verification=1 WHERE click_id='".$click_id."' AND page_id='".$post_id."'");
        if ($sql == 1) {
          $sql = $con->query("SELECT user, activity FROM all_earning WHERE user='".$click_id."'");
          if ($sql->num_rows > 0) {
            while ($act = $sql->fetch_assoc()) {
              $act_earning = $act["activity"] + 10;
              $sql = $con->query("UPDATE all_earning SET activity='".$act_earning."' WHERE user='".$click_id."'");
              if ($sql) {
                echo "You just got paid(".$act_earning.") for reading this page.";
                exit();
              }
            }
          }
        }
      }
    }
  }
  $sql->close;
  $con->close;
}


//*******GET USER IP ADDRESS******
function getRealUserIp() {
  switch (true) {
    case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
    case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
    case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
    default : return $_SERVER['REMOTE_ADDR'];
  }
}
$ip = getRealUserIp();
/* echo($ip);*/



//**********REGISTRATION HANDLER****************
if (isset($_POST["register"])) {

  $response = array();
  $err = array();
  $referral_id = null;
  $referee_id = null;
  $ok = true;
  $page = null;

  $fn = sanitize($_POST["fn"]) ?? null;
  $ln = sanitize($_POST["ln"]) ?? null;
  $em = sanitize($_POST["em"]) ?? null;
  $ph = sanitize($_POST["ph"]) ?? null;
  $rf = sanitize($_POST["rf"]) ?? null;
  $pwd = sanitize($_POST["pwd"]) ?? null;
  $rpwd = sanitize($_POST["rpwd"]) ?? null;
  $tc = $_POST["tc"] ?? null;
  $paymode = $_POST["paymode"] ?? null;
  $date = date("Y/m/d H:i:sa");
  $new_pin = "E".rand(100, 5000)."A".date("sih");
  $fprid = null;
  $referral_id = 0;
  $referee_id = 0;
  $pay_for_referring = 0;

  $fn = $con->real_escape_string($fn);
  $ln = $con->real_escape_string($ln);
  $em = $con->real_escape_string($em);
  $ph = $con->real_escape_string($ph);
  $rf = $con->real_escape_string($rf);
  $pwd = $con->real_escape_string($pwd);



  if (empty($fn)) {
    array_push($err, "Please enter First name.");
    $ok = false;
  }
  if (empty($ln)) {
    array_push($err, "Please enter Last name.");
    $ok = false;
  }
  if (empty($em) || filter_var($em, FILTER_VALIDATE_EMAIL) === FALSE) {
    array_push($err, "Please enter a valid email.");
    $ok = false;
  }
  if (empty($ph) || strlen($ph) < 10) {
    array_push($err, "Please enter a valid Phone number.");
    $ok = false;
  }
  if (empty($pwd) || strlen($pwd) <= 5) {
    array_push($err, "Please enter password (More than 5 characters).");
    $ok = false;
  }
  if ($rpwd !== $pwd) {
    array_push($err, "Passwords do not match.");
    $ok = false;
  }

  if ($tc != 1) {
    array_push($err, "Please you must read and agree to our terms and conditions to continue.");
    $ok = false;
  }

  if (empty($paymode)) {
    array_push($err, "Please choose your mode of payment.");
    $ok = false;
  }


  if ($rf != "flexpay" && !empty($rf)) {
    $sql = $con->query("SELECT id FROM user_biodata WHERE fprid='".$rf."'");
    if ($sql->num_rows > 0) {
      while ($r = $sql->fetch_assoc()) {
        $referral_id = $r["id"];
      }
      $ok = true;
    } else {
      array_push($err, "Invalid referral Identification.");
      $ok = false;
    }
  } else {
    $rf = "flexpay";
  }

  if ($ok == true) {
    $sql = $con->query("SELECT em, ph FROM user_biodata WHERE em='".$em."' OR ph='".$ph."'");
    if ($sql->num_rows > 0) {
      array_push($err, "Email/phone already exist. <a style='text-decoration:underline;' href='##'>Reset password?</a>.");
      $ok = false;
    }
  }
  if ($paymode == "epin") {
    $page = '
<div id="epinModal" class="payWithEPin container" >
<span style="font-size:larger;color:white;" onclick="document.querySelector(\'#epinModal\').style.display=\'none\';"><b><i class="fa fa-times"></i></b></span>
<div class="container" >
<img src="img/done.png" >
<h4 id="epin_output" style="text-align:center;"></h4>
<form onsubmit="verify_payment()";>
<h1>You have made it all here.</h1>
<br>
<h4>Input your E-PIN <br><br>
<input type="text" name="epin" id="epin" placeholder="E-PIN" autocomplete="off" ></h4>
<br>
<button type="submit" name="verify_epin" value="verify_epin">Submit</button>
</form>
</div>
<br>
<br>
<p>Please contact <a style="color:lightgreen;" href="tel:07061017993">E-PIN Admin</a> for more information on how to get yours.</p>
<br>
<p>Amount: NGN1,600</p>
<p>BANK: GTBank</p>
<p>ACC NO: 0157357331</p>
<p>ACC NAME: ROTIMI FEMI SOLOMON</p>
<br>
<p>Once payment has been made, send your prove of payment through <a style="color:green;" href="https://wa.me/+234'.$ph.'?text=From: '.$fn.'('.$ph.')...">WhatsApp chat</a> to the E-PIN ADMIN to get it.</p>
<p>FlexPay.</p>
</div>
    ';
  } else {
    $page = "<div style='color:red;' class='container'><h4>Oops! Sorry the service(PAYSTACK) is not available at the moment. Please check back later.</h4></div>";
    $page .= '<div id="epinModal" class="payWithEPin container" >
<span style="font-size:larger;color:white;" onclick="document.querySelector(\'#epinModal\').style.display=\'none\';"><b><i class="fa fa-times"></i></b></span>
<div class="container" >
<img src="img/done.png" >
<h4 id="epin_output" style="text-align:center;"></h4>
<form onsubmit="verify_payment();">
<h1>Oops, You have not completed your payment.</h1>
<br>
<h4>Please enter your E-PIN <br><br><br>
<input type="text" name="epin" id="epin" placeholder="E-PIN" autocomplete="off" ></h4>
<br>
<button type="submit" name="verify_epin" value="verify_epin">Submit</button>
</form>
</div>
<br>
<br>
<p>Please contact <a style="color:lightgreen;" href="tel:07061017993">E-PIN Admin</a> for more information on how to get yours.</p>
<br>
<p>Amount: NGN1,600</p>
<p>BANK: GTBank</p>
<p>ACC NO: 0157357331</p>
<p>ACC NAME: ROTIMI FEMI SOLOMON</p>
<br>
<p>Once payment has been made, send your prove of payment through <a style="color:green;" href="https://wa.me/+2347061017993?text=From: '.$fn.'('.$ph.')...">WhatsApp chat</a> to the E-PIN ADMIN to get it.</p>
<p>FlexPay.</p>
</div>
    ';
  }

  if ($ok == true) {
    echo $fn." ".$ln." ".$em." ".$ph." ".$rf." ".$pwd." ".$rpwd." ".$tc." ".$paymode." ".$date." ".$referral_id;
    exit;
    $fprid = "FP_".date("his-Ymd").rand()."1";
    $pwd = md5($pwd);
    //try {
      $sql = $con->query("INSERT INTO user_biodata(fn, ln, em, ph, rf, pwd, pm, pmc, fprid, date) VALUES (
					'".$fn."',
					 '".$ln."',
					 '".$em."',
					 '".$ph."',
					 '".$rf."',
					 '".$pwd."',
					 '".$paymode."',
					 '0',
					 '".$fprid."',
					 '".$date."'
					 )");
   /* } catch (Exception $e ) {
      die(json_encode($e->getMessage()))
    }*/
    
    if ($sql) {
      $referee_id = $con->insert_id;

      $con->query("INSERT INTO referral(referral_id, referee_id, date) VALUES ('".$referral_id."','".$referee_id."','".$date."')");

      $con->query("INSERT INTO all_earning(user) VALUES ('".$referee_id."')");


      $con->query("INSERT INTO epin(pin) VALUES ('$new_pin')");

      $sql = $con->query("SELECT ref FROM all_earning WHERE user='".$referral_id."'");

      if ($sql->num_rows > 0) {
        while ($pay = $sql->fetch_assoc()) {
          $pay_for_referring = $pay["ref"] + 1000;
          $con->query("UPDATE all_earning SET ref='".$pay_for_referring."' WHERE user='".$referral_id."'");
        }
      }


      $response["res"] = 1;
      $response["message"] = $page;
    } else {
      $response["res"] = 2;
      $response["message"] = "<p style='color:green;'>Sorry! Your registration could not be completed at the moment. Please try again.</p>";
      //$response["message"] = json_encode($sql);
    }
  } else {
    $response["res"] = 3;
    foreach ($err as $er => $err) {
      $response["message"] .= "<p style='color:red;font-size:smaller;'>".$err."</p>";
    }
  }

  echo json_encode($response);
  $sql->close;
  $con->close;
}


//VERIFY EPIN HANDLER
if (isset($_POST["verify_epin"])) {
  $epin = $_POST["epin"];
  $epin = $con->real_escape_string($epin);
  $response = array();
  $new_pin = "E".rand(100, 5000)."A".date("sih");
  $ok = true;

  if (empty($epin)) {
    $response["response"] = "Please enter your e-pin";
    $ok = false;
  }

  if ($ok === true) {
    $query = $con->query("SELECT pin FROM epin WHERE pin='{$epin}'");
    if ($query->num_rows > 0) {
      $con->query("UPDATE user_biodata SET pmc=1 WHERE ph='{$ph}'");
      $con->query("DELETE FROM epin WHERE pin='{$epin}'");
      $con->query("INSERT INTO epin(pin) VALUES ('$new_pin')");
      $response["response"] = "Account verified. Welcome.";
    }

  }
  $con->close;
}


//**********LOGIN SERVER****************

if (isset($_POST["login"])) {
  $response = array();
  $username = sanitize($_POST["lg"]) ?? "";
  $password = sanitize($_POST["pwd"]) ?? "";

  if (empty($username)) {
    $err[] = "Enter Email/phone.<br>";
  }
  if (empty($password)) {
    $err[] = "Enter Password.<br>";
  }

  if ($err == 0) {
    $fetch = $con->query("SELECT * FROM user_biodata WHERE ph='".$username."' OR em='".$username."' AND pwd='".md5($password)."'");
    if ($fetch->num_rows > 0) {
      $con->query("UPDATE user_biodata SET online=1 WHERE ph='".$username."' OR em='".$username."' AND pwd='".md5($password)."'");
      /*header("Location: index.php?u=".$_SESSION["em"]);*/
      /*array_push($response, "1");*/
      while ($row = $fetch->fetch_assoc()) {
        $_SESSION["id"] = $row["id"];
        $_SESSION["em"] = $row["em"];
        $_SESSION["ph"] = $row["ph"];
        $_SESSION["fn"] = $row["fn"];
        $_SESSION["ln"] = $row["ln"];
        $_SESSION["pmc"] = $row["pmc"];
        $_SESSION["date"] = $row["date"];
        //$_SESSION["pic"] = $row["profile_pic"];
        //$_SESSION["cover_pic"] = $row["cover_pic"];
      }
      array_push($response, 1);
    } else {
      array_push($response, "<p style='color:red;'>Invalid username/password.</p>");
    }
  } else {
    foreach ($err as $err => $er) {
      array_push($response, "<p style='color:red;'>".$er."</p>");
    }
  }
  echo json_encode($response);
}

//**********LOGOUT HANDLER****************
if (isset($_GET["logout"])) {
  $em = $_SESSION["em"];
  $ph = $_SESSION["ph"];
  $con->query("UPDATE user_biodata SET online=0 WHERE ph='".$ph."' OR em='".$em."'");
  unset($_SESSION["em"]);
  unset($_SESSION["ph"]);
  session_destroy();
  header("Location: index.php?r=l");
}

//**********SUBSCRIBE FOR WEEKLY NEWS*******

if (isset($_POST["sbcr"])) {
  $em = sanitize($_POST["em"]) ?? "";
  $ok = true;
  if (empty($em) || filter_var($em, FILTER_VALIDATE_EMAIL) == false) {
    $err[] = "Enter valid Email.<br>";
    $ok = false;
  }
  $check = $con->query("SELECT em FROM news WHERE em='".$em."'");
  if ($check->num_rows > 0) {
    $err[] = "You already subscribed to our news letter. Thanks.<br>";
    $ok = false;
  }
  if ($ok == true) {


    $to = $em;
    $subject = "Weekly News subscription notification";

    $message = "
<html>
<head>
<title>Weekly News subscription</title>
<meta charset='utf-8' />
<meta http-equiv='X-UA-Compatible' content='ie=edge'>
<meta name='viewport' content='width:device-width, initial-scale=1.0, user-scalable=no'>
<link href='.../../../styles/index.css' rel='stylesheet' >
<link href='.../../../styles/fa/css/all.css' rel='stylesheet' >
<link href='https://fonts.googleapis.com/css?family=Quicksand|Open+Sans&display=swap' rel='stylesheet'>
</head>
<body>
<div class='user_modal container'>
<h1>Hello,</h1>
<br>
<p>This is to inform you that you have subscribed to our news channel with this email <b>•".$em."• </b></p>
<p>We will always send you weekly news updates and inform you of our latest products.</p>
<br>
    ";
    $sql = "SELECT * FROM post WHERE post_title LIKE '%".$q."%' ORDER BY date DESC";
    $sql = mysqli_query($con, $sql);
    if ($sql->num_rows) {
      foreach ($sql as $post) {


        $message .= '<div style="background:#eee;" class="container" >';
        $message .= '<div class="toggler_container" >
<div class="toggler" onclick="
if(this.nextElementSibling.style.display==\'block\'){
this.nextElementSibling.style.display=\'none\';
}else{
this.nextElementSibling.style.display=\'block\';
}
        ">
</div>
<div class="toggler_meu">
<p><a href="server.php?r=del-post&pid='.$post["id"].'"><i class="fa fa-trash"></i>&nbsp; Delete post</a></p>
<hr style="opacity:0.1;padding:3px;">
<p><a href="server.php?r=report-post&pid='.$post["id"].'"><i class="fa fa-flag"></i>&nbsp; Flag post</a></p>
<hr style="opacity:0.1;padding:3px;">
<p><a onclick="alert(\'Only Premium users can pin post.\n\r Kindly upgrade your account now..\');return false;" href="server.php?r=pin-post&pid='.$post["id"].'"><i class="fa fa-pin"></i>&nbsp; Pin to top</a></p>
</div>
</div>';
        $message .= '<div class="tag" ><p style="font-weight:bold;color:#333;font-size:8px;">
&nbsp; &nbsp;
<i class="fa fa-eye">10+</i>
&nbsp;&nbsp;
<i class="fa fa-heart">32</i>
</p></div>';
        $message .= '<a onclick="openLink(event, this);" id="1" href=".../../post/now.php?pid='.$post["id"].'&t='.$post["date"].$post["id"].'" >';
        $message .= '<div class="card-wrapper" >';
        $message .= '<div class="icon" ><img src=".../../../uploads/'.$post["post_p_att"].'" alt="'.$post["title"].'"></div>';
        $message .= '<div class="content" >';
        $message .= '<p style="font-size:small;">'.substr(ucfirst($post["post_title"]), 0, 36).'...</p>';
        $message .= "<br>";
        $message .= '<input type="hidden" >';
        $message .= '<p>'.substr(ucfirst(htmlspecialchars_decode($post["post_cont"])), 0, 40).'... <span style="color:grey;font-size:10px;">Read more</span></p>';
        $message .= '</div>';
        $message .= '</div>';
        $message .= '</a>';
        $message .= '</div>';
      }
    }
    $message .= "<br>
<hr style='opacity:0.2;'>
<p>If you didn't initiate this action, Please unsubscribe now by clicking <a href='server.php?unsbcr=".$em."'>here</a></p>
<p>Have a nice day.</p>
</div>
</body>
</html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: noreply@flexpay.com' . "\r\n";
    $headers .= 'Reply-to: flexpay.web@gmail.com' . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
      $query = $con->query("INSERT INTO news(em) VALUES ('".$em."')");
      echo("<p style='color:green;'>We will always send you weekly notification.</p>");
    } else {
      echo("<p style='color:#fff;'>Unable to decide your request. Please try again.</p>");
    }
  } else {
    foreach ($err as $err => $er) {
      echo "<p style='font-size:smaller;color:red'>".$er."</p>";
    }
  }
  $con->close;
}

/*
function credit_activity_earning($con, $reserved_coin){
$reserved_coin += 50;
$has_visited = 0;
$sql = "UPDATE `table` SET `coin`='$reserved_coin', `visited`='$has_visited' WHERE `user`='' ";
$sql = $con->query($sql);
return true;
}*/

function sanitize($var) {
  $var = stripslashes($var);
  $var = htmlentities($var);
  $var = trim($var);
  return $var;
}
?>