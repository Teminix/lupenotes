
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
    <script src="scripts/Libraries/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
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
    }
    </script>
    <style media="screen">

    </style>
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="users/">USERS</a>
      </div>
      <div class="nav-item">
        <a href="index.php">REGISTER</a>
      </div>
    </div>
    <center>

      <form id="form">
        <center>
          Login now!<br><br>
          <input type="text" name="usr" placeholder="Username" required onkeypress="if (event.keyCode == 13) {EnterLogin()}"><br>
          <input type="password" name="pwd" placeholder="Password" required onkeypress="if (event.keyCode == 13) {EnterLogin()}"><br>
          <div id="status">
          </div> <br><br>
          <button type="button" id="submit" value="login">Login</button>
          <br><br>
          Don't have an account?<br>
          <a href="/index.php">Go here!</a>
      </center>
      </form>
  </center>
  <script type="text/javascript">
    $(function(){

        $("#submit").click(function () {EnterLogin()});//submit click event
    });//ready function
  </script>
  </body>
</html>
