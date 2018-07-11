<?php
session_start();
function getfname($suff="") {
  return basename(__FILE__,$suff);
}

$usr = getfname(".php");
$HOSTNAME = "localhost";
$DBUSER = "root";
$DBPASS = "root";
$DBNAME = "project";
if(isset($_SESSION["usr"])) {
  if ($_SESSION["usr"] == $usr) {
    header("location:../Session/session.php");
  }
}


$conn = new mysqli($HOSTNAME,$DBUSER,$DBPASS,$DBNAME);
$res = $conn->query("SELECT * FROM users WHERE usr='$usr'");
$row = $res->fetch_assoc();
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
     <title><?php echo $usr ?>'s Profile</title>
     <link rel="stylesheet" href="../Session/styles.css">
     <script src="http://projhost:8088/scripts/js/main.js" charset="utf-8"></script>
   </head>
   <body>
     <div class="main">
       <?php
       echo "<img src='../dps/".$row["image"]."'>"
        ?>
     <br>
     <span>Username: <input type="text" name="usr" value="<?php echo $usr; ?>" readonly> </span> <br>
     <span>Display name: <input type="text" name="display" value="<?php echo $row['display']; ?>" readonly> </span><br><br>
   </div>
   <div class="main">
   <?php
      $conn_posts = new mysqli($HOSTNAME,$DBUSER,$DBPASS,"project");
      $query = "SELECT * FROM posts WHERE usr='$usr'";
      $res = $conn_posts->query($query);
      while ($row = $res->fetch_assoc()) {
        $title = $row["title"];
        $content = $row["content"];
        $rep = $row["reputation"];
        $id = $row["ID"];
        echo
        "<div class='post' post-id='$id'>
          <textarea readonly name='title' class='title' rows='1'>$title</textarea><br><br>
          <textarea class='postContent' name='content' readonly>$content</textarea>
          <div class='vote-section' voted='0'>
            <button class='upvote' onclick='vote(this,\"up\")'>
              <img src='../images/up.png' class='button'>
            </button>
            <p class='score'>$rep</p>
            <button class='downvote' onclick='vote(this,\"down\")'>
              <img src='../images/down.png' class='button' >
            </button>
          </div>
        </div>";
      }

    ?>
  </div>
    <!-- Hello-->
   </body>
 </html>
