<?php
session_start();

$dir = scandir(".");
$dir = array_diff($dir,[".","..","index.php",".DS_Store"]);
$dir_raw = "[";
foreach ($dir as $file) {
  $file_handle = fopen($file,"w");
  $temp_file = file_get_contents("../temps/user-template.txt");
  fwrite($file_handle,$temp_file);
  fclose($file_handle);
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>User list</title>
    <style media="screen">
      /* FONTS BEGIN */

      @font-face {
        font-family:PT-mono;
        src: url("../fonts/pt_mono.ttf");
      }

      /* FONTS END */
      .users {
        margin-right: 25%;
        margin-left: 25%;
        width: auto;
        padding:10px;
        background-color: black
      }
      .user {
        background-color: rgb(51, 51, 51);
        margin:10px;
        width:95%;
        height:auto;
        padding:10px;
        color:white;
        cursor:pointer;
        transition:0.3s;
      }
      .user  img {
        width:100px;
        height: 100px;
        display: inline-block;
      }
      .user div {
        display: inline-block;
        position: absolute;
        margin-left: 10px;
        font-family: PT-mono;
      }
      span.usr {
        font-size: 13px;
        color:gray;
        transition:0.3s;

      }
      span.display {
        font-size: 20px;
        color:white;
        transition:0.3s;
        display: inline-block;
      }
      .user:hover {
        background-color: rgb(110, 0, 255);
        transform: scale(1.1);
      }
      .user:hover span.display {
        transform:scale(1.1)
      }
      .user:hover span.usr {
        color:black;
        font-weight: 400;

      }

      h1 {
        color:white;
        background-color: black;
        font-family: PT-mono;
        font-size: 20px;
        display: block;
        padding: 10px;
        text-align: center;
      }
    </style>
    <script type="text/javascript">
      console.log("Current user session: "+"<?php echo $_SESSION["usr"]; ?>")
    </script>
  </head>
  <body>
    <div class="users">
      <h1>USERS REGISTERED</h1>
    <?php
      $conn = new mysqli("localhost","root","root","project");
      $query = "SELECT * FROM users";
      $res = $conn->query($query);
      while ($row = $res->fetch_assoc()) {
        $usr = $row["usr"];
        $display = $row["display"];
        $image = $row["image"];
        $href= '"'.$usr.'.php"';
        echo "<div class='user' onclick='window.location.href=$href'>
        <img src='../dps/$image'>
        <div><span class='display'>$display</span><br><span class='usr'>$usr</span></div>
        </div>";
      }
     ?>
     </div>
  </body>
</html>
