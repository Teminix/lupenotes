<?php

 ?>






<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Registration page</title>
    <link rel="stylesheet" href="/styles/main.css">
    <script src="scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script type="text/javascript">
      function EnterRegister() {
          var user = document.getElementsByName('usr')[0].value;
          var pass = document.getElementsByName('pwd')[0].value;
          var displayName = document.getElementsByName('display')[0].value;
          var subtype = $("#submit").val();
          $.ajax({
            type:"POST",
            url:"process.php",
            data:{ usr:user,display:displayName,pwd:pass,type:subtype },
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

      });};
    </script>
  </head>
  <body>
    <center>
      <form id="form" method="POST">
        <center>
          Register now!<br><br>
          <input type="text" name="usr" placeholder="Username*" required onkeypress="if (event.keyCode == 13) {EnterRegister()}"><br>
          <input type="text" name="display" placeholder="Dispay name*" required onkeypress="if (event.keyCode == 13) {EnterRegister()}"><br>
          <input type="password" name="pwd" placeholder="Password*" required onkeypress="if (event.keyCode == 13) {EnterRegister()}"><br>
          <div id="status"></div>
           <br><br>
           <button type="button" id="submit" value="register">Register</button>
          <br><br>
          Already have an account?<br>
          <a href="/login.php">Go here!</a>
      </center>
      </form>
  </center>
  <script type="text/javascript">
  $(function () {
    $("#submit").click(function() {EnterRegister()});//$("#submit")
  });//function()
  </script>
  </body>
</html>
