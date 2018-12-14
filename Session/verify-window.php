<?php
  session_start();
  echo $_GET["code"];
  $usr = $_SESSION["usr"];
  $conn = new mysqli("localhost","root","root","project");
  $res = $conn->query("SELECT email,email_v FROM users WHERE usr='$usr'");
  $row = $res->fetch_assoc();

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Verify email</title>
    <link rel="stylesheet" href="../styles/main.css">
    <link rel="stylesheet" href="styles.css">
    <style media="screen">
      .p {
        color:red;
        font-size:13px;
      }
      div.form {
        width:500px
      }
      div[s-parent]{
        width:auto
      }
    </style>
    <script src="../scripts/js/main.js" charset="utf-8"></script>
    <script src="../scripts/js/snyper.js" charset="utf-8"></script>
    <script src="../scripts/js/Globalscript.js" charset="utf-8"></script>
    <script src="../scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script type="text/javascript">
      Snyper.initiate()
    </script>
  </head>
  <body>
    <center>
          <div s-parent s-frame="main" s-default="first" s-indexing="auto">
            <?php
            $type = $_GET["type"];
            $email = $row["email"];
            if (!isset($type)) {

              echo '
                <div class="form" style="" s-child s-frame="first" >
                Enter email verification code for: <br /><br>
                <input type="text" placeholder="Email" name="email" value="'.$email.'"><br />
                <input type="text" name="code" placeholder="Code..."><br>
                <span class="p"></span><br>
                <button type="button" class="hot" onclick="post_form(this,{ext:{\'type\':\'send\'},url:\'verifier.php\'},true,\'Sending please wait\')">(Re)send Code</button>
                <button type="button" class="hot" id="verify_button">Verify</button>
                </div><script>console.log("test")</script>';


            }
            elseif ($type == "change") {
              if ($row["email_v"] == 0) {
                echo '<div class="form" style="">
                Email not yet verified:<br /><br />
                <a href="verify-window.php">Verify Here</button>
                </div>';
              }
              else {
                echo '
                <div class="form" style="" s-child s-frame="first">
                  Send code to change email address to confirm change in email address:<br />
                  <b>'.$email.'</b><br />
                  <span class="p"></span><br>
                  <button type="button" class="hot" id="send_change_code">Send Code</button>
                  <div>
                  <button type="button" class="hot next">&#8674;</button>
                  </div>

                </div>';
              }
              // echo "<script>console.log('Email_v:".$row["email_v"]."')</script>";
            }
             ?>

             <div class="form" style="" s-child s-frame="second">
               Enter confirmation code sent to <?php echo $email; ?>:<br><br>
               <input type="text" name="code" placeholder="Code"><br />
               <span class="p"></span><br>
               <button type="button" class="hot" id="change_resend_confirmation_code">(Re)Send code</button>
               <div>
                 <button type="button" name="button" class="previous">&#8672;</button>
                 <button type="button" class="hot" id="change_confirmation_verify">&#8674;</button>

               </div>

             </div>
            <div class="form" style="" s-child s-frame="third">
              Enter verification code for:<br><br>
              <input type="text"  name="email" placeholder="New Email"><br>
              <input type="text" name="code" placeholder="Code"><br />
              <span class="p"></span><br>
              <button type="button" class="hot" id="change_resend_code">(Re)Send code</button>
              <button type="button" class="hot" id="change_verify">Verify Changes</button>
              <div>
                <button type="button" class="hot previous" test="null">&#8672;</button>
              </div>
            </div>
          </div>

    </center>
    <script type="text/javascript">
    var snype = new Snyper("main")
    $("#change_resend_code").on("click",function(){
      post_form(this,{ext:{'type':'send'},url:'verifier.php'},false,'Sending please wait')
    })
    </script>
  </body>
</html>
