<?php
session_start();
if(!(isset($_SESSION["usr"]) == "TRUE" || isset($_SESSION["display"]) == "TRUE")) {
  header("location:../index.php");
}
else {
  $usr = $_SESSION["usr"];
  $display = $_SESSION["display"];
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="styles.css">
    <script src="../scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script type="text/javascript">
      function keyDown(event, keycode, func) {
        if (event.keyCode == keycode){func};
      };
      function changePass() { // Create a function to change the pasword or post the new password
          var old_pass = document.getElementsByName("password")[0].value; // old password
          var new_pass = document.getElementsByName("password")[1].value; // new password
          var prompts = document.getElementsByClassName("pr");
          $.ajax ({ // ajax call the information
            type:"POST",
            url:"changes.php",
            data: {op:old_pass,np:new_pass,type:"password"}, // The type of the call is password
            success: function (data) { // on success
              if (data == "1") { // if the data was valid and the password met conditions:
                modal[1].style.display = "none"; // close modal
                window.location.reload(true); // reload window
              }
              else {
                prompts[1].innerHTML = data; // otherwise inform the userof the error
              }
            },
            error: function () {
              prompts[1].innerHTML = "Some error occured in trying to change your password"; // An error message to be displayed
            }
          });

      }
      function changeUsr() { //function to change the username or display name
        //Get the data:
          var user = document.getElementById("usr").value;
          var displayName = document.getElementById("display").value;
          var pass = document.getElementById("pass").value;
          $.ajax({ // Make an ajax call with the data
            type:"POST",
            url:"changes.php",
            data: { usr:user,display:displayName,pwd:pass,type:"username" },
            success: function (data) {
              if (data == "1") {
                modal[0].style.display="none";
                window.location.reload(true);
              }
              else {
                document.getElementsByClassName('pr')[0].innerHTML = data;
              }
            },
            error: function() {
              document.getElementsByClassName('pr')[0].innerHTML = "Some error occured in the server";
            }
          });

      }
      function resetVal(array) { // define the elements to have their value reset
        for (i=0;i<array.length;i++) {
          array[i].value = "";
        }
      }
      function post(path,element) { // create a posting function for convenience
        var form = element.parentNode;
        var dict = {type:"createPost"};
        for (i=0;i<form.children.length;i++) {
          if (form.children[i].tagName == "TEXTAREA" || form.children[i] =="INPUT") {
            dict[form.children[i].name] = form.children[i].value; // add a key to the dictionary which is the child's name and the dict value as the child's input value
          }
          else if (form.children[i].getAttribute("class") == "p") {
            var prompt = form.children[i];
          }
        }//console.log(dict)
        $.ajax({ // Post the data in an ajax call
          type: "POST",
          url: path,
          data: dict, // the data is  the dictionary we made of the data that needs to be posted
          success: function (data) {
            if (data == "1") {
              console.log("successfully posted");
            }
            else {
              prompt.innerHTML = data;
            }
          },
          error: function() {
            console.log("Some error has ocurred for some enigmatic reason lol"); // Due to any error, log An error
          }
        });
      }
      function makeEdit(elem) { // Make changes to the post
        var parent = elem.parentNode; // Grab the parent Node of the target element
        for (i=0;i<parent.children.length;i++) {
          if (parent.children[i].tagName == "TEXTAREA") {
            elem.parentNode.children[i].removeAttribute("readonly");
          }
        }
        elem.innerHTML = "Save"; // Change the innerHTML to 'Save'
        elem.setAttribute("onclick","makeRead(this)"); // Add a new onclick event
      }
      function makeRead(elem) { // Save changes for the post
        var parent = elem.parentNode;
        var post_id = parent.getAttribute("post-id");
        var dict = {type:"editPost",pid:post_id};
        for (i=0;i<parent.children.length;i++) {
          if (parent.children[i].tagName == "TEXTAREA") {
            dict[parent.children[i].name] = parent.children[i].value;
            parent.children[i].setAttribute("readonly","readonly");
          }
          else if (parent.children[i].getAttribute("class") == "p") {
            var prompt = parent.children[i];
          }
        }
        //console.log(dict);
        elem.innerHTML = "Edit";
        elem.setAttribute("onclick","makeEdit(this)");
        $.ajax({
          type:"POST",
          url:"post.php",
          data:dict,
          success: function (data){
            if(data=="1") {
              console.log("Editing post sucessful of id: "+post_id);
            }
            else {
            prompt.innerHTML = data}
          },
          error: function() {
            prompt.innerHTML = "Some error occured during diting you post";
          }
        });
      }
      function deletePost(elem) {
        var parent = elem.parentNode;
        var id = parent.getAttribute("post-id");
        parent.parentNode.removeChild(parent);
        $.ajax({
          type:"POST",
          url:"post.php",
          data: {type:"delPost",pid:id},
          success: function () {
            console.log("deleted post successfully with id: "+id);
          },
          error: function () {
            console.log("Some error occured in the process of deleting post id: "+id)
          }
        });
      }
    </script>
  </head>
  <body>
    <div class="main">
      <?php
      $conn = new mysqli("localhost","root","root","project");
      $res = $conn->query("SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'");
      $row = $res->fetch_assoc();
      echo "<img src='../dps/".$row["image"]."'>"
       ?>
    <br>
    <span>Username: <input type="text" name="usr" value="<?php echo $usr; ?>" readonly> </span> <br>
    <span>Display name: <input type="text" name="display" value="<?php echo $display; ?>" readonly> </span><br><br>
    <button type="button"><a href="logout.php">Log Out</a></button>
    <button type="button" name="display" class="edit">Edit Profile</button> <!-- First edit button -->
    <button type="button" name="pass" class="edit">Change Password</button> <!-- Second edit button -->
    <button type="button" name="profile">Change profile picture</button>
  </div>
  <div class="main"> <!-- POST FORM/DIV -->
    <form>
      <span class="title">Write a post:</span><br>
      <textarea name="title" class="title" rows="1" placeholder="Title of Post"></textarea><br><br>
      <textarea name="content" rows="8" cols="80" placeholder="Content of the post.."></textarea>
      <span class="p"></span>
      <button type="button" name="submitPost" onclick="post('post.php',this);window.location.reload(true)">POST</button>
      <button type="button" name="reset" onclick="resetVal([this.parentNode.children[4],this.parentNode.children[8]])">CANCEL</button>
    </form>
  </div>
  <?php
    $conn = new mysqli("localhost","root","root","project");
    $query = "SELECT * FROM posts WHERE usr='".$usr."'";
    $res = $conn->query($query);
    while ($row= $res->fetch_assoc()) {
      $title = $row["title"];
      $content = $row["content"];
      $id = $row["ID"];
      echo
      "<div class='main' post-id='$id'>
        <textarea readonly name='title' class='title' rows='1'>$title</textarea><br><br>
        <textarea class='postContent' name='content' readonly>$content</textarea>
        <span class='p'></span>
        <button type='button' purpose='post' name='edit' onclick='makeEdit(this)'>Edit</button>
        <button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>
      </div>

      ";
    }
   ?>
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
          <img id="prev"><br><br>
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



    </script>
  </body>
</html>
