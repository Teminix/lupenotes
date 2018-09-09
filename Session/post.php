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
   echo "0";
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
      echo "0";
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
    $votes = $row["votes"]; // Get the votes data
    eval("\$vote_array = array($votes);"); // translate that data into an array
    // echo $rep."/n";
    // echo $oldrep;

    if ($value == "upvote") { // if the post vote/reputation is incremented
      if (isset($vote_array[$_SESSION['usr']]) && $vote_array[$_SESSION["usr"]] =="u") { // checks if the user has already voted in this direction

        echo "Upvoting failed to execute";
        }
      else {
        $vote_array[$_SESSION["usr"]]='u';
        echo arraytostr($vote_array);
        $votes = arraytostr($vote_array);
        $rep = $rep + 1;
        $conn->query('UPDATE posts SET reputation="'.$rep.'",votes="'.$votes.'" WHERE ID="'.$postid.'"');
        //echo "Successfully upvoted on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep, vote token: $votes";
      }
    }
    elseif ($value == "downvote") { // if the post vote/reputation is decremented
      if (isset($vote_array[$_SESSION['usr']]) && $vote_array[$_SESSION["usr"]] =="d") {echo "Downvoting failed to execute";} // checks if the user has already voted in this direction
      else {
        $vote_array[$_SESSION["usr"]] = 'd';
        echo arraytostr($vote_array);
        $votes = arraytostr($vote_array);
        $rep = $rep - 1;
        $conn->query('UPDATE posts SET reputation="'.$rep.'",votes="'.$votes.'" WHERE ID="'.$postid.'"');
        //echo "Successfully downvoted on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep, vote token:$votes";
      }
    }
    elseif ($value == "neutralise") {
      unset($vote_array[$_SESSION["usr"]]); // remove the user from the list
      $votes = arraytostr($vote_array);
      if ($direction == 'up') {
        $rep = $rep + 1;
        $conn->query('UPDATE posts SET reputation="'.$rep.'",votes="'.$votes.'" WHERE ID="'.$postid.'"');
        echo "Successfully neutralised the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
      elseif ($direction == 'down') {
        $rep = $rep - 1;
        $conn->query('UPDATE posts SET reputation="'.$rep.'",votes="'.$votes.'" WHERE ID="'.$postid.'"');
        echo "Successfully neutralised the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
    }
    elseif ($value == "jump") {// if a user jumps from upvote to downvote straight away vice versa
      if ($direction == "up") {//if user upvotes after downvoting
        $vote_array[$_SESSION["usr"]] = "u";
        $votes = arraytostr($vote_array);
        $rep = $rep + 2;
        $conn->query('UPDATE posts SET reputation="'.$rep.'",votes="'.$votes.'" WHERE ID="'.$postid.'"');
        echo "Successfully upjumped on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep, token: $votes";
      }
      elseif ($direction=="down") { // if user downvotes after upvoting
        $vote_array[$_SESSION["usr"]] = "d";
        $votes = arraytostr($vote_array);
        $rep = $rep - 2;
        $conn->query('UPDATE posts SET reputation="'.$rep.'",votes="'.$votes.'" WHERE ID="'.$postid.'"');
        echo "Successfully upjumped on the post with id: ".$postid."\nold reputation: ".$oldrep.", new reputation:$rep";
      }
    }
  }
}
else {
  header("location:session.php");
}
 ?>
