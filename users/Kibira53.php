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
   </body>
 </html>
