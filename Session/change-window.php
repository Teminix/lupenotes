<?php
session_start();
$DBSERVER = "localhost";
$DBUSER = "root";
$DBPASS = "root";
$DBNAME = "project";

if (!isset($_SESSION["usr"])) {
  header("location:../index.php");
}
$usr = $_SESSION["usr"];
$conn = new mysqli($DBSERVER,$DBUSER,$DBPASS,$DBNAME);
$res = $conn->query("SELECT * FROM users WHERE usr='$usr'");
$row = $res->fetch_assoc();
$display = $row["display"];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit profile</title>
    <script src="../scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script src="../scripts/js/modal.js" charset="utf-8"></script>
    <script src="../scripts/js/main.js" charset="utf-8"></script>
    <link rel="stylesheet" href="styles.css">
    <style media="screen">
      form {
        margin-left: 40px
      }
      .highlighted:hover {
        color:lightblue
      }
      .img {
        background-color:black;
        margin:0;
        text-align:center;
        display:block;
        font-size: 20px;
        transition:0.1s;
        opacity:0
      }
      div.img_container {
        cursor:pointer
      }
      div:hover > .img {
        background-color:var(--sexy-purple);
        opacity:1;
      }
      img {
        margin-bottom:0;
      }
    </style>
    <link rel="stylesheet" href="../styles/colors.css">
    <script type="text/javascript">

      var defaulters = {
        usr:'<?php echo $usr; ?>',
        display:'<?php echo $display; ?>'
      };
      function reset(elem) {
        var form = elem.closest("form");
        var inputs = form.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
          let input = inputs[i];
          if (input.hasAttribute("default")) {
            input.value = input.getAttribute("default");
          }
          else {
            input.value = ""
          }
        }
      }
      function post(elem,info={},location) {
        var form = elem.closest("form")
        var inputs = form.getElementsByTagName("input")
        var prompt = form.getElementsByClassName("p")[0]
        for (var i = 0; i < inputs.length; i++) {
          let input = inputs[i]
          let name = input.name;
          info[name] = input.value
        }
        // console.log(info)
        $.ajax({
          url:location,
          type:"POST",
          data:info,
          success: function(response) {
            if(response == "1") {
              window.location.reload(true);
            }
            else{
              prompt.innerHTML = response
            }
          },
          error:function() {
            console.error("INTERNAL SERVER ERROR")
            prompt.innerHTML = "Internal server error"
          }
        })
      }
    </script>
  </head>
  <body>
    <div class="main">
      <div>
        <h1>Edit profile:</h1>
        <h4>Change profile picture</h4>
        <form>
          <div class="img_container" style="display:inline-block" onclick="Modal.toggle('dp')">
              <img src="../dps/<?php echo $row["image"]; ?>"><!--
              --><h6 class="img">Change</h6>
          </div>
        </form>
        <h4>Edit Username or Display name:</h4>
        <form>
          <table>
            <tr>
              <td>Username:</td>
              <td><input type="text" name="usr" value="<?php echo $usr; ?>" default="<?php echo $usr; ?>"><br></td>
            </tr>
            <tr>
              <td>Display name:</td>
              <td><input type="text" name="display" value="<?php echo $display ?>" default="<?php echo $display; ?>"></td>
            </tr>
            <tr>
              <td>Verify with password:</td>
              <td><input placeholder="Password" type="password" name="pwd"><br></td>
            </tr>
          </table>
          <span class="p"></span>
          <div>
            <button type="button" class="highlighted" name="button" style="float:right;background-color:var(--sexy-purple)" onclick="post(this,{type:'username'},'changes.php')">Save</button>
            <button type="button" name="button" style="float:right;" onclick="reset(this)">Cancel</button>
          </div>



        </form>
        <br><br>
        <h4>Change Password:</h4>
        <form>
          <table>
            <tr>
              <td>Old password:</td>
              <td> <input type="password" id="old_pwd" placeholder="Old password"> </td>
            </tr>
            <tr>
              <td>New password:</td>
              <td> <input type="password" id="new_pwd" placeholder="New password"> </td>
            </tr>
            <tr>
              <td>Verify password:</td>
              <td> <input type="password" id="verif_pwd" placeholder="Verfify password"> </td>
            </tr>
          </table>
          <span class="p"></span>
          <div>
            <button type="button" class="highlighted" name="button" style="float:right;background-color:var(--sexy-purple)" id="password">Save</button>
            <button type="button" name="button" style="float:right;" onclick="reset(this)">Cancel</button>
          </div>

        </form>

      </div>
    </div>
    <div class="modal" modal="dp"><!-- MODAL DIVISION 3: TO BE ABLE TO CHANGE THE PROFILE PICTURE-->
      <div class="content" style="width:35%">
        <form action="changes.php" method="POST" enctype="multipart/form-data">
          Change profile picture:<br><br>
            <input type="file" accept="image/*" name="fileupload"><br><br>
            <img id="prev" src='preview.png'><br><br>
            <span class="pr"></span>
            <button type="button" name="clear">Reset Picture</button><br>
            <button type="button" class="cancel" onclick="Modal.hide('dp')">Cancel</button>
            <button type="submit" name="type" value="profile">Update Profile Pic</button>
          </form>
        </div>
      </div>
    <script type="text/javascript">
      Modal.initiate()
      var file_upload = document.getElementsByName("fileupload");
      file_upload[0].onchange = function() {
        const reader = new FileReader(); // create a new filereader instance
        reader.readAsDataURL(file_upload[0].files[0]) // Read the image as url in the image input files
        reader.onload = function () { // as soon as the reader has finished loading up
          img = document.getElementById("prev"); // get the element that is supposed to preview
          img.setAttribute("src",reader.result);// set the src ofthe image as the new url that has been recieved
        }
      }
      var new_pwd = document.getElementById('new_pwd');
      // console.log(new_pwd.closest("form"))
      var prompt = $("#password").closest("form").children(".p")
      $("#password").click(function() {
        var old_pass = $("#old_pwd").val()
        var new_pass = $("#new_pwd").val()
        var verify_pass = $("#verif_pwd").val()
        if ($.trim(old_pass) == "") {
          prompt.html("Old password field is empty")
        }
        else if (new_pass == verify_pass) {
          if (new_pass.length < 6 || new_pass.length > 20) {
            prompt.html("New password must be at least 6 and at max 20 characters")
          }
          else {
            var dataset = {op:old_pass,np:new_pass,type:"password"};
            $.ajax({
              url:"changes.php",
              type:'POST',
              data:dataset,
              success: function (response) {
                if (response == "1") {
                  window.location.reload(true)
                }
                else if(response == "0") {
                  prompt.html("Inelligible password.")
                }
                else {
                  prompt.html(response)
                }
              },
              error: function() {
                prompt.html("Some error occured")
              }
            })
          }
        }
        else {
          prompt.html("The new password and verify password must be the same.")
        }


      })
    </script>
  </body>
</html>
