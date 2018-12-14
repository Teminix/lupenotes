<?php
session_start();
include "lib.php";
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Registration page</title>
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/Session/styles.css">
    <script src="scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script src="scripts/js/main.js" charset="utf-8"></script>
    <script src="scripts/js/Globalscript.js" charset="utf-8"></script>
    <script type="text/javascript">
      /*function EnterRegister() {
          var user = document.getElementsByName('usr')[0].value;
          var pass = document.getElementsByName('pwd')[0].value;
          var displayName = document.getElementsByName('display')[0].value;
          var email_addr = document.getElementsByName('email')[0].value;
          var verif_pass = document.getElementsByName('pwd')[1].value;
          var subtype = $("#submit").val();
          $.ajax({
            type:"POST",
            url:"process.php",
            data:{ usr:user,display:displayName,pwd:pass,verif_pwd:verif_pass,email:email_addr,type:subtype },
            success: function(data) {
              if (data == "0") {
                window.location.href = "Session/session.php";
              }
              else {
              $("#status").html(data);}
            },
            error: function() {
              $("#status").html("Some error has occured during registration")
            }

          });
    };*/
    </script>
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="index.php">HOME</a>
      </div>
      <div class="nav-item">
        <a href="users.php">USERS</a>
      </div>
      <div class="nav-item">
        <a href="login.php">LOGIN</a>
      </div>

      <div class="nav-item">
         <a href="communities.php">LUPES</a>
       </div>
    </div>
    <center>
      <form id="form" method="POST">
        <center>
          Register now!<br><br>
          <input type="text" name="usr" placeholder="Username*" required><br>
          <span style="color:black; font-size:10px">* Username is case insensitive</span><br>
          <input type="text" name="display" placeholder="Dispay name*" required><br>
          <input type="text" name="email" placeholder="Email address*" required><br>
          <input type="password" name="pwd" placeholder="Password*" required><br>
          <input type="password" name="verif_pwd" placeholder="Verify Password*" required><br>
          <span class="p"></span>
           <br><br>
           <button type="button" class="hot" id="submit">Register</button>
          <br><br>
          <?php
            if(!isset($_SESSION["usr"])){
              echo 'Already have an account?<br>
              <a href="login.php">Go here!</a>';
            }
           ?>
      </center>
      </form>
  </center>
  <script type="text/javascript">
  $("#submit").on("click",function(){
    post_form(this,{url:"process.php",ext:{type:"register"},redirect:'Session/session.php'});
  });
  $("#submit")[0].onclick = function(){
    post_form(this,{url:"process.php",ext:{type:"register"},redirect:'Session/session.php'});
  }
  document.getElementsByName('usr')[0].focus();
  $("#form").on("keydown",function(e){
    let keycode = e.which || e.keycode;
    if(keycode == 13){
      $("#submit")[0].click();
    }
  })
  </script>
  </body>
</html>
