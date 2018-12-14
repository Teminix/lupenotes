<?php
session_start();
include "../php/lib.php";
if(!(isset($_SESSION["usr"]) == "TRUE" || isset($_SESSION["display"]) == "TRUE")) {
  header("location:../main.php");
}
else {
  $usr = $_SESSION["usr"];
  $display = $_SESSION["display"];
}

// Get the total reputation

$conn = new mysqli("localhost","root","root","project");
$res1 = $conn->query("SELECT * FROM posts WHERE usr='$usr'");
$array = array();
while ($row1 = $res1->fetch_assoc()) {
  array_push($array,$row1["reputation"]);
}
$rep_sum = array_sum($array);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="/styles/nav.css">
    <script src="../scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script src="" charset="utf-8"></script>
    <script type="text/javascript" src="../scripts/js/main.js">
    </script>
    <script src="../scripts/js/modal.js" charset="utf-8"></script>
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="../users.php">USERS</a>
      </div>
      <div class="nav-item">
        <a href="../main.php">REGISTER</a>
      </div>
      <div class="nav-item">
        <a href="../communities.php">LUPES</a>
      </div>
    </div>
    <div class="main">
      <div>
      <?php
      $conn = new mysqli("localhost","root","root","project");
      $res = $conn->query("SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'");
      $row = $res->fetch_assoc();
      echo "<img src='../dps/".$row["image"]."' class='dp'>";
       ?>
    <br>

    <!-- INFORMATION AND USER BUTTONS -->
    <table>
      <tr>
        <td>Username:</td>
        <td><?php echo $usr; ?></td>
      </tr>
      <tr>
        <td>
          Display Name:
        </td>
        <td>
          <?php echo $display; ?>
        </td>
      </tr>
    </table>
    <!-- <span>Username: <input type="text" name="usr" value="<?php echo $usr; ?>" readonly> </span> <br>
    <span>Display name: <input type="text" name="display" value="<?php echo $display; ?>" readonly> </span><br> -->
    <span style="font-size:17px;color:blue">Reputation: <?php echo $rep_sum; ?></span>
    <br><br>
    <button type="button" onclick="window.location.href = 'logout.php'">Log Out</button>
    <button type="button" name="display" onclick="window.location.href = 'change-window.php'">Edit Profile</button> <!-- First edit button -->
    <button type="button" name="de-activate" onclick="Modal.show('deact')">Deactivate account</button>
  </div>
  <div class="modal" modal="deact">
    <form>
      <div class="content" style="width:30%">
          You sure you want to deactivate the account? This cannot be undone<br><br><br><br>
          <span class="div"><input type="password" placeholder="Password" name="pwd"></span><br><br>
          <span class="p"></span><br>
          <button type="button" class="cancel" onclick="Modal.hide('deact')">Cancel</button>
          <button type="button" class="verify" onclick="post_form(this,{url:'changes.php',ext:{type:'deactivate'}})">Yes</button>
      </div>
    </form>

  </div>
  <?php
      if ($row['email_v'] == 0) {
        echo '<div class="modal" modal="verify">
          <form>
            <div class="content" style="width:30%">
                Verifying the account is highly recommended<br>
                <span class="tip">A few features may be off-limits otherwise</span>
                <br><br><br>
                <button type="button" class="cancel" onclick="Modal.hide(\'verify\')">Cancel</button>
                <button type="button" name="button" onclick="window.location.href = \'verify-window.php\'">Verify</button>
            </div>
          </form>
        </div>
        <div class="info">
        You need to <a href="verify-window.php">verify</a> your email in order to post
        </div>';
      } else {
        echo '<span class="tooltip-wrapper">
          <button type="button" name="button" class="util" onclick="window.location.href=\'editor.php\'">+</button>
          <center>
          <span class="tooltip">Create post</span>
          </center>
        </span>';
      }
   ?>




  <?php
    $conn = new mysqli("localhost","root","root","project"); // POSTS
    $query = "SELECT * FROM posts WHERE usr='$usr' ORDER BY id DESC";
    $res = $conn->query($query);
    while ($row = $res->fetch_assoc()) {
      $title = $row["title"];
      $content = $row["content"];
      $rep = $row["reputation"];
      $id = $row["ID"];

      $res_usr = $conn->query("SELECT * FROM users WHERE usr='".$_SESSION['usr']."'");
      $row_usr = $res_usr->fetch_assoc();
      $user_id = $row_usr['ID'];
      // echo "PostID = $id; USER_ID = $user_id";
      $downvotes = $conn->query("SELECT * FROM votes WHERE post=$id AND type=0")->num_rows;
      $upvotes = $conn->query("SELECT * FROM votes WHERE post=$id AND type=1")->num_rows;
      $rep = $upvotes-$downvotes;
      $usr_vote = $conn->query("SELECT type FROM votes WHERE post=$id AND usr=$user_id");
      if ($usr_vote->num_rows == 0) {
        $vote_type = 0;
      } else {
        $cast = $usr_vote->fetch_assoc()['type'];
        if ($cast == 0) {
          $vote_type = 'down';
        }
        elseif ($cast == 1) {
          $vote_type = 'up';
        }
      }




      $user_img = $row_usr["image"];
      $user_usr = $_SESSION['usr'];
      $user_disp = $row_usr['display'];
      $target = $row['target'];
      if ($target == "0") {
        $comm = "";
      }
      else {
        $res_comm = $conn->query("SELECT * FROM communities WHERE ID='$target'")->fetch_assoc();
        $comm = $res_comm['Name'];
        $comm = "posted to <a href='../communities.php?c=$comm'>$comm</a> ";
      }
      if ($row_usr['email_v'] == 1) {
        $voting = "<button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>
        <div class='vote-section' voted='$vote_type'>
          <button class='upvote' onclick='vote(this,\"up\")'>
            <img src='../images/up.png' class='button'>
          </button>
          <p class='score'>$rep</p>
          <button class='downvote' onclick='vote(this,\"down\")'>
            <img src='../images/down.png' class='button' >
          </button>
        </div>";
      } else {
        $voting = "<div class=\"info\">
        You need to <a href=\"verify-window.php\">verify</a> your email in order to delete or vote
        </div>";
      }

      echo
      "<div class='post' post-id='$id'>
      <span class='info-wrapper' onclick='window.location.href=\"../users/$post_usr.php\"'>
        <img src='../dps/$user_img' alt=''>
        <span>$user_disp</span><br>
        <span class='usr'>$user_usr $comm</span>
      </span><br>
        <textarea readonly name='title' class='title' rows='1'>$title</textarea><br><br>
        <span class='p'></span>
        <button type='button' purpose='post' name='edit' onclick='redirect(\"editor.php?editPost&id=$id\")'>Edit</button>
        $voting<br>
        <a href='view-post.php?id=$id'> >> View post </a>

      </div>

      ";
    }
   ?>
 </div>



    <script type="text/javascript">
    init_vote();

      var prompts = document.getElementsByClassName("pr"); // Grab the elements that display the prompt message
      Modal.initiate();
      <?php
      if ($row["email_v"] == 0) {
        echo "Modal.show('verify')";
      } ?>

      //concerning deactivating account
      /*deactivate = document.getElementsByName("de-activate")[0];console.log(deactivate); //get the button
      deactivate.onclick = function () { // onclick of the button
        deactivate.blur()
        body = document.body; // get the body element
        deact_elem = constructElem(
          "div",
          '',
          {class:"modal"}
        )// construct the element
        body.appendChild(deact_elem); //append the element
        deact_elem.style.display = "block";
        var p = deact_elem.getElementsByClassName('p')[0];
        document.body.onkeyup = function (event) { //check for the keyups
           if(event.keyCode == "27") {
             deact_elem.parentNode.removeChild(deact_elem)//remove or deconstruct the element
           }
        }*/



    </script>
  </body>
</html>
