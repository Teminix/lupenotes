<?php
session_start();
include "../php/lib.php";
if(!(isset($_SESSION["usr"]) == "TRUE" || isset($_SESSION["display"]) == "TRUE")) {
  header("location:../index.php");
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
    <script src="../scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script src="" charset="utf-8"></script>
    <script type="text/javascript" src="http://projhost:8088/scripts/js/main.js">
    </script>
    <script src="../scripts/js/modal.js" charset="utf-8"></script>
  </head>
  <body>
    <?php
    $file_path = __FILE__;
    echo temp('../temps/nav.php',["path"=>$file_path,"test"=>"yes"]);
    ?>
    <div class="main">
      <div>
      <?php
      $conn = new mysqli("localhost","root","root","project");
      $res = $conn->query("SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'");
      $row = $res->fetch_assoc();
      echo "<img src='../dps/".$row["image"]."'>";
       ?>
    <br>

    <!-- INFORMATION AND USER BUTTONS -->
    <span>Username: <input type="text" name="usr" value="<?php echo $usr; ?>" readonly> </span> <br>
    <span>Display name: <input type="text" name="display" value="<?php echo $display; ?>" readonly> </span><br>
    <span style="font-size:17px;color:blue">Reputation: <?php echo $rep_sum; ?></span>
    <br><br>
    <button type="button"><a href="logout.php">Log Out</a></button>
    <button type="button" name="display" onclick="window.location.href = 'change-window.php'">Edit Profile</button> <!-- First edit button -->
    <button type="button" name="de-activate" onclick="Modal.show('deact')">Deactivate account</button>
  </div>
  <div class="modal" modal="deact">
    <form>
      <div class="content" style="width:30%">
          You sure you want to deactivate the account?<br><br><br><br>
          <span class="div"><input type="password" placeholder="Password" name="pwd"></span><br><br>
          <span class="p"></span><br>
          <button type="button" class="cancel" onclick="Modal.hide('deact')">Cancel</button>
          <button type="button" class="verify" onclick="post_form(this,{url:'changes.php',ext:{type:'deactivate'}})">Yes</button>
      </div>
    </form>

  </div>
  <?php
    $conn = new mysqli("localhost","root","root","project"); // POSTS
    $query = "SELECT * FROM posts WHERE usr='$usr' ORDER BY id DESC";
    $res = $conn->query($query);
    while ($row= $res->fetch_assoc()) {
      $title = $row["title"];
      $content = $row["content"];
      $rep = $row["reputation"];
      $id = $row["ID"];
      $rep_vote = $row['votes'];
      eval("\$rep_array = array($rep_vote);");
      // $rep_array = arraytostr($rep_array);
      if(array_key_exists($_SESSION["usr"],$rep_array)){
        if($rep_array[$_SESSION["usr"]] == "d"){
          $vote_type="down"; // if the user has already downvoted the post then make the div downvoted
        }
        elseif ($rep_array[$_SESSION["usr"]] == "u") {
          $vote_type = "up"; // if the user has already upvoted the post then make the div upvoted
        }}
      else {
        $vote_type = "0";
      }
      echo
      "<div class='post' post-id='$id'>
        <textarea readonly name='title' class='title' rows='1'>$title</textarea><br><br>
        <textarea class='postContent' name='content' readonly>$content</textarea>
        <span class='p'></span>
        <button type='button' purpose='post' name='edit' onclick='makeEdit(this)'>Edit</button>
        <button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>
        <div class='vote-section' voted='$vote_type'>
          <button class='upvote' onclick='vote(this,\"up\")'>
            <img src='../images/up.png' class='button'>
          </button>
          <p class='score'>$rep</p>
          <button class='downvote' onclick='vote(this,\"down\")'>
            <img src='../images/down.png' class='button' >
          </button>
        </div><br>
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
