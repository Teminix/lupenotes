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
    <script type="text/javascript" src="http://projhost:8088/scripts/js/main.js">

    </script>
  </head>
  <body>
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
    <button type="button" name="display" class="edit">Edit Profile</button> <!-- First edit button -->
    <button type="button" name="pass" class="edit">Change Password</button> <!-- Second edit button -->
    <button type="button" name="profile">Change profile picture</button>
    <button type="button" name="de-activate">Deactivate account</button>
  </div>
  <div class="post"> <!-- POST FORM/DIV -->
    <form>
      <span class="title">Write a post:</span><br>
      <textarea name="title" class="title" rows="1" placeholder="Title of Post"></textarea><br><br>
      <textarea name="content" rows="8" cols="80" placeholder="Content of the post.."></textarea>
      <span class="p"></span>
      <button type="button" name="submitPost" onclick="post('post.php',this)">POST</button>
      <button type="button" name="reset" onclick="resetVal([this.parentNode.children[4],this.parentNode.children[8]])">CANCEL</button>
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
  <div class="modal"> <!-- MODAL DIVISION 1: FOR CHANGIN USERNAME AND DISPLAY NAME-->
    <div class="content">
      <form>
        <br><br>
        Edit user and Display name:<br><br>
        <span class="div"><input type="text" name="usr" value="" placeholder="Username" id="usr" onkeypress="if(event.keyCode ==13){changeUsr()}"></span><br>
        <span class="div"><input type="text" name="display" value="" placeholder="Display Name" id="display" onkeypress="if(event.keyCode ==13){changeUsr()}"></span><br>
        <span class="pr"></span>
        <br><br>
        Verify Changes with password<br><br>
        <span class="div"><input type="password" name="pwd" placeholder="Password" id="pass" onkeypress="if(event.keyCode ==13){changeUsr()}"></span><br><br>
        <button type="button" class="cancel">Cancel</button> <!-- First cancel button -->
        <button type="button" class="verify">Verify</button> <!-- First verify button-->
    </form>
    </div>
  </div>
  <div class="modal"> <!-- MODAL DIVISION 2: TO CHANGE THE PASSWORD -->
    <div class="content" style="width:30%">
      <form>
        Change Password:<br><br>
        <span class="div"><input type="password" name="password" placeholder="Old password" onkeypress="if(event.keyCode ==13){changePass()}"> </span><br><br>
        <span class="div"><input type="password" name="password" placeholder="New password" onkeypress="if(event.keyCode ==13){changePass()}"> </span><br><br>
        <span class="div"><input type="password" name="password" placeholder="Verify password" onkeypress="if(event.keyCode ==13){changePass()}"> </span><br><br>

        <span class="pr"></span><br><br>
        <button type="button" class="cancel">Cancel</button><!-- Second cancel button-->
        <button type="button" class="verify">Change password</button> <!-- Second verify button-->
    </form>
    </div>
  </div>
  <div class="modal"> <!-- MODAL DIVISION 3: TO BE ABLE TO CHANGE THE PROFILE PICTURE-->
    <div class="content" style="width:35%">
      <form action="changes.php" method="POST" enctype="multipart/form-data">
        Change profile picture:<br><br>
          <input type="file" accept="image/*" name="fileupload"><br><br>
          <img id="prev" src='preview.png'><br><br>
          <span class="pr"></span>
          <button type="button" name="clear">Reset Picture</button><br>
          <button type="button" class="cancel">Cancel</button>
          <button type="submit" name="type" value="profile">Update Profile Pic</button>
    </form>
    </div>
  </div>

    <script type="text/javascript">


      var prompts = document.getElementsByClassName("pr"); // Grab the elements that display the prompt message
      var modal =document.getElementsByClassName('modal'); // Grab the modal elements/conmtainers
      var modalContent = document.getElementsByClassName('content')[0]; // Grab the content of the Modal, this however is not neccessary
      var edit_button = document.getElementsByClassName('edit'); // Grab the edit button classes
      var cancel_button = document.getElementsByClassName('cancel'); // Grab the difference cancel button classes
      var verify_button = document.getElementsByClassName('verify'); // Grab the verify button classes
      var update_button = document.getElementsByName('profile'); // Grab the update button classes
      var img_input = document.getElementsByName("fileupload"); // Grab the image input for the profile picture modal
      var clear_img = document.getElementsByName("clear"); // grab the button to clear the profile picture
      edit_button[0].onclick = function () { // The onclick even for the Username and display name modal form
        modal[0].style.display = "block"; // Show the modal to the user
        // Get the data that is needed
        document.getElementsByName('usr')[1].value = document.getElementsByName('usr')[0].value;
        document.getElementsByName('display')[2].value = document.getElementsByName('display')[0].value;
      };
      edit_button[1].onclick = function () { //Edit button for the password change modal onclick event
        modal[1].style.display = "block"; // Show the modal content
      }
      cancel_button[0].onclick = function () {// The cancel button for the Username and Display name part
        modal[0].style.display = "none";
        prompts[0].innerHTML = ""; // Set the prompt to be empty
      };
      cancel_button[1].onclick = function () { // The second cancel button or the button where the users change their password
        modal[1].style.display = "none"; //
        prompts[1].innerHTML = "";
      };
      cancel_button[2].onclick = function() {
        modal[2].style.display = "none";
      };
      verify_button[1].onclick = function () {changePass()};
      verify_button[0].onclick = function() {changeUsr()};
      update_button[0].onclick = function () {
        modal[2].style.display = "block";
      }
      img_input[0].onchange = function() {
        const reader = new FileReader();
        reader.readAsDataURL(img_input[0].files[0])
        reader.onload = function () {
          img = document.getElementById("prev");
          img.setAttribute("src",reader.result);
        }
      }
      clear_img[0].onclick = function () {
        $.ajax({
          type:"POST",
          url:"changes.php",
          data: {clear:"TRUE"},
          success: function (data) {
            //prompts[2].innerHTML = data;
            window.location.reload(true);
          },
          error: function () {
            prompts[2].innerHTML = "Some error has occured";
          }
        });
      }
      //concerning deactivating account
      deactivate = document.getElementsByName("de-activate")[0];console.log(deactivate); //get the button
      deactivate.onclick = function () { // onclick of the button
        deactivate.blur()
        body = document.body; // get the body element
        deact_elem = constructElem(
          "div",
          '<div class="content" style="width:30%">\
\
              You sure you want to deactivate the account?<br><br><br><br>\
              <span class="div"><input type="password" placeholder="Password" id="pwd"></span><br><br>\
              <span class="p"></span><br>\
              <button type="button" class="cancel">Cancel</button><!-- Second cancel button-->\
              <button type="button" class="verify">Yes</button> <!-- Second verify button-->\
\
          </div>',
          {class:"modal"}
        )// construct the element
        body.appendChild(deact_elem); //append the element
        deact_elem.style.display = "block";
        var p = deact_elem.getElementsByClassName('p')[0];
        document.body.onkeyup = function (event) { //check for the keyups
           if(event.keyCode == "27") {
             deact_elem.parentNode.removeChild(deact_elem)//remove or deconstruct the element
           }
        }
        cancel_button[3].onclick = function () {
          deact_elem.parentNode.removeChild(deact_elem)
        }// the deactivate button verify button
        verify_button[2].onclick =function () {
          var password = document.getElementById("pwd").value;
          $.ajax({
            type:"POST",
            url:"changes.php",
            data:{type:"deactivate",pwd:password},
            success:function(data) {
              if (data == "0") {
                window.location.href = "../index.php";
              }
              else {
                p.innerHTML = data
              }
            },
            error:function(){console.log("An error occured during deactivation")}
          })
        }
      }

      init_vote();


    </script>
  </body>
</html>
