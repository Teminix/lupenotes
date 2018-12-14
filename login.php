
<?php
session_start();
if(isset($_SESSION["usr"])){
  header("location:Session/session.php");
}
 ?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Login page</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="Session/styles.css">
    <script src="scripts/Libraries/jquery.js" type="text/javascript"></script>
    <script src="scripts/js/main.js" charset="utf-8"></script>
    <script src="scripts/js/Globalscript.js" charset="utf-8"></script>
    <script type="text/javascript">/*
    function EnterLogin() {
        var user = document.getElementsByName('usr')[0].value;
        var pass = document.getElementsByName('pwd')[0].value;
        var subType = $("#submit").val();
        $.ajax({
          type:"POST",
          url:"process.php",
          data: { usr:user,pwd:pass,type:subType },
          success: function (data) {
            if (data == "0") {
              window.location.href = "Session/session.php";
            }
            else {
              $("#status").html(data);
            }
          },
          error: function() {
            $("#status").html("Some error has occured in the login process");
          }
        });//ajax
    }*/
    </script>
    <style media="screen">

    </style>
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="users.php">USERS</a>
      </div>
      <div class="nav-item">
        <a href="main.php">REGISTER</a>
      </div>
    </div>
    <center>

      <form id="form">
        <center>
          Login now!<br><br>
          <input type="text" name="usr" placeholder="Username" required><br>
          <input type="password" name="pwd" placeholder="Password" required><br>
          <div class="p">
          </div> <br><br>
          <button type="button" id="submit" class="hot">Login</button>
          <br><br>
          Don't have an account?<br>
          <a href="/main.php">Go here!</a>
      </center>
      </form>
  </center>
  <script type="text/javascript">
  console.log(window.onload.toString());
  /*afterload(function(){

  })*/
  $('#submit').on('click',function(){
    let elem = $(this)[0];
    post_form(elem,{url:'process.php',ext:{type:'login'},redirect:'Session/session.php'})
  })
  $('#form input').on('keypress',function(e){
    let keycode = e.which||e.keyCode;
    if (keycode == 13) {
      $('#submit').click()
    }
  })
  </script>
  </body>
</html>
<script type="text/javascript">
let active =  document.getElementsByName('usr')[0];
active.focus();
if(document.activeElement != active) {
  setTimeout(function(){active.focus()},5000)
}
console.log(document.activeElement);
</script>
