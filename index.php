<?php
session_start();
$conn = new mysqli('localhost','root','root','project');
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Lupenotes</title>
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/Session/styles.css">
    <script src="scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script src="scripts/js/main.js" charset="utf-8"></script>
    <script src="scripts/js/Globalscript.js" charset="utf-8"></script>
    <style media="screen">
    .cover {
      text-align:center;
      font-size:120px;
      color:white;
    }
      #box {
        border-bottom:white 5px solid;
        display:inline-block;
        padding:40px;
      }
    </style>
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="main.php">REGISTER</a>
      </div>
      <?php
        if(!isset($_SESSION['usr'])){
          echo '<div class="nav-item">
            <a href="login.php">LOGIN</a>
          </div>';
        }
        ?>
      <div class="nav-item">
        <a href="communities.php">LUPES</a>
      </div>
      <div class="nav-item">
        <a href="users.php">USERS</a>
      </div>
      <?php
          if(isset($_SESSION['usr'])){
            $usr= $_SESSION['usr'];
            $res = $conn->query("SELECT * FROM users WHERE usr='$usr'")->fetch_assoc();
            $image = $res['image'];
            echo "<div class='nav-item right'>
              <a href='Session/session.php' class='image'>
                <img src='dps/$image' />
              </a>
            </div>";
          }
       ?>
    </div>
    <center>
      <img src="images/icon-white.png" alt="">
    </center>
    <h1 class="cover" style='margin-top:20px;margin-bottom:20px'>LupeNotes</h1>
    <div>
      <center>
      <div id="box">
        <center>
          Note taking...
        </center>
        <center>
          Now sharable and scalable
        </center>
      </div>
    </center>
    </div>
    <script type="text/javascript">
      let text1 = q('#box').children[0];
      let text2 = q('#box').children[1];
      MultiType([text1,text2]);
    </script>
  </body>
</html>
