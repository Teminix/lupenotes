<?php

// REMEMBER TO RECEIVE VARIABLE CALLED "PATH"
$file = basename($path);
$file = explode("?",$file)[0];
$dir =basename($dir);
//return $file;

//initialiser
if (isset($_SESSION['usr'])) {
  $usr = $_SESSION['usr'];
  $conn = new mysqli("localhost","root","root","project");
  $res = $conn->query("SELECT * FROM users WHERE usr='$usr'");
  $row = $res->fetch_assoc();
  $img = $row["image"];
  $display = $row['display'];
  $display_div = "
  <div class='nav-item right'>
    <div>$display</div>
  </div>
  ";
  $img_div = "
  <div class='nav-item right'>
    <a href='http://projhost:8088/Session/session.php' style='padding-bottom:10px;padding-top:10px;'><img src='../dps/$img'></a>
  </div>
  ";
}
if ($file == "session.php") {
return "
<div class='nav'>
  <div class='nav-item'>
    <a href='http://projhost:8088/users.php.php'>USERS</a>
  </div>
  <div class='nav-item'>
    <a href='http://projhost:8088/main.php'>REGISTER</a>
  </div>

</div>
";}
elseif ($file == "index") {
  // code...
}
elseif ($dir == "users") {
  if (isset($_SESSION["usr"])) {
    return "<div class='nav'>
      $img_div
      $display_div
      <div class='nav-item'>
        <a href='http://projhost:8088/main.php/'>REGISTER</a>
      </div>
    </div>";
  }
  else {
    return "<div class='nav'>
      $img_div
      $display_div
      <div class='nav-item'>
        <a href='http://projhost:8088/main.php/'>REGISTER</a>
      </div>
    </div>";
  }

}
elseif ($dir == "Session") {
  if ($file=="view-post.php") {

  }
}
elseif ($file == "/" || $file == "main.php") {
  if (isset($_SESSION['usr'])) {
    return "<div class='nav-item'>
      <a href='http://projhost:8088/users.php/'>USERS</a>
    </div>
    $img_div
    $display_div
    ";
  }
  else {
    return "<div class='nav-item'>
      <a href='http://projhost:8088/main.php'>REGISTER</a>
    </div>
    <div class='nav-item'>
      <a href='http://projhost:8088/login.php'>LOGIN</a>
    </div>";

  }
}
 ?>
