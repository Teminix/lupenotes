<?php
session_start();
function escape_sql_string($string){
  $dict= array("'"=>"\'",'"'=>'\"',"\n"=>"","\r"=>"","\\"=>"","%"=>"\%","_"=>"\_");
  foreach ($dict as $key) {
    // code...
  }
}

$HOSTNAME = "localhost";
$DBUSER = "root";
$DBPASS = "root";
$DBNAME = "project";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if ($_POST["type"] == "createPost") {
   $title = $_POST["title"];
   $usr = $_SESSION["usr"];
   $content = $_POST["content"];
   if ($title == "" || $content == "") {
     echo "Title and the content are neccessary to fill up";
   }
   else {
   $content = $_POST["content"];
   $conn = new mysqli($HOSTNAME, $DBUSER, $DBPASS, $DBNAME);
   $title = addslashes($title);
   $content = addslashes($content);
   $query = "INSERT INTO posts (title,content,usr) VALUES ('$title','$content','$usr')";
   $res = $conn->query($query);
   echo "1";
    }
  }
  elseif ($_POST["type"] == "editPost") {
    $title = $_POST["title"];
    $usr = $_SESSION["usr"];
    $content = $_POST["content"];
    if ($title == "" || $content == "") {
      echo "Title and the content cannot be left blank";
    }
    else {
    $id = $_POST["pid"];
    $conn = new mysqli($HOSTNAME, $DBUSER, $DBPASS, $DBNAME);
    $content = $_POST["content"];
    $query = "UPDATE posts SET content='$content', title='$title' WHERE ID='$id'";
    $res = $conn->query($query);
    echo "1";
     }
  }
  elseif ($_POST["type"] == "delPost") {
    $id = $_POST["pid"];
    $conn = new mysqli($HOSTNAME,$DBUSER,$DBPASS,$DBNAME);
    $conn->query("DELETE FROM posts WHERE ID='$id'");
  }
}
else {
  header("location:session.php");
}
 ?>
