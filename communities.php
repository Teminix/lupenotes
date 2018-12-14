<?php
include "lib.php";
$root = $root_dir;
session_start();
if (isset($_SESSION['usr'])) {
  $usr = $_SESSION['usr'];
  $conn = new mysqli("localhost","root","root","project");
  $res = $conn->query("SELECT * FROM users WHERE usr='$usr'");
  $row = $res->fetch_assoc();
  $img = $row['image'];
}

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Communities</title>

    <script src="scripts/Libraries/jquery.js" charset="utf-8"></script>
    <script src="scripts/js/main.js" charset="utf-8"></script>
    <script src="scripts/js/Globalscript.js" charset="utf-8"></script>
    <script src="scripts/js/snyper.js" charset="utf-8"></script>
    <script src="scripts/js/circle.js" charset="utf-8"></script>
    <script type="text/javascript">
      Snyper.initiate()
    </script>
    <link rel="stylesheet" href="Session/styles.css">
    <link rel="stylesheet" href="/styles/nav.css">
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="main.php">REGISTER</a>
      </div>
      <div class="nav-item right">
        <a href="login.php" class="image"><img src="dps/<?php echo $img; ?>"> </a>
      </div>
    </div>
    <div class="main">
      <?php
      if (isset($_GET['c'])) {
        $comm = strtolower($_GET['c']);
        echo temp_curl("temps/comm-view.php",[c=>$comm,sess_usr=>$usr],$root);
      }
      else {
        echo temp_curl("temps/comm.php",NULL,$root);
      }

       ?>
    </div>
    <script type="text/javascript">
      init_vote();
    </script>
  </body>
</html>
