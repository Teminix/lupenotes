<?php
session_start();
include "../php/lib.php";
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
    $conn = new mysqli($HOSTNAME, $DBUSER, $DBPASS, $DBNAME);
    $query = "SELECT * FROM posts WHERE ID=".$_POST["pid"];
    $res = $conn->query($query);
    $row = $res->fetch_assoc();
    if ($row["usr"] == $_SESSION["usr"]){
      $title = addslashes($_POST["title"]);
      $usr = $_SESSION["usr"];
      $content = addslashes($_POST["content"]);
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
    }}
    else {
      echo "failed to change post with id: ".$_POST["id"];
    }
  }
  elseif ($_POST["type"] == "delPost") {
    $id = $_POST["pid"];
    $conn = new mysqli($HOSTNAME,$DBUSER,$DBPASS,$DBNAME);
    $conn->query("DELETE FROM posts WHERE ID='$id'");
  }
  elseif($_POST["type"] == "vote") {// if the informaataion is a vote type
    $value = $_POST["value"];
    $postid = $_POST["pid"];
    $conn = new mysqli($HOSTNAME,$DBUSER,$DBPASS,$DBNAME);
    if (isset($_POST["direction"])) {
      $direction = $_POST["direction"];
    }
    $res = $conn->query("SELECT * FROM posts WHERE ID='$postid'");
    $row = $res->fetch_assoc();
    $rep = $row["reputation"];
    $oldrep = $row["reputation"];
    // echo $rep."/n";
    // echo $oldrep;

    if ($value == "upvote") { // if the post vote/reputation is incremented
      $rep = $rep + 1;
      $conn->query("UPDATE posts SET reputation='$rep' WHERE ID='$postid'");
      echo "Successfully upvoted on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";

    }
    elseif ($value == "downvote") { // if the post vote/reputation is decremented
      $rep = $rep - 1;
      $conn->query("UPDATE posts SET reputation='$rep' WHERE ID='$postid'");
      echo "Successfully downvoted on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";

    }
    elseif ($value == "neutralise") {
      if ($direction == 'up') {
        $rep = $rep + 1;
        $conn->query("UPDATE posts SET reputation='$rep' WHERE ID='$postid'");
        echo "Successfully neutralised the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
      elseif ('down') {
        $rep = $rep - 1;
        $conn->query("UPDATE posts SET reputation='$rep' WHERE ID='$postid'");
        echo "Successfully neutralised the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
    }
    elseif ($value == "jump") {// if a user jumps from upvote to downvote straight away vice versa
      if ($direction == "up") {//if user upvotes after downvoting
        $rep = $rep + 2;
        $conn->query("UPDATE posts SET reputation='$rep' WHERE ID='$postid'");
        echo "Successfully upjumped on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
      elseif ($direction=="down") { // if user downvotes after upvoting
        $rep = $rep - 2;
        $conn->query("UPDATE posts SET reputation='$rep' WHERE ID='$postid'");
        echo "Successfully upjumped on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
    }
  }
}
else {
  header("location:session.php");
}
 ?>
