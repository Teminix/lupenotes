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
$conn = new mysqli($HOSTNAME, $DBUSER, $DBPASS, $DBNAME);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $result = $conn->query("SELECT email_v FROM users WHERE usr='".$_SESSION['usr']."'");
  $row = $result->fetch_assoc();
  if ($row['email_v'] == 1) {
    if ($_POST["type"] == "createPost") {
      $time = time();
      $title = mysqli_real_escape_string($conn, $_POST["title"]);
      $usr = $_SESSION["usr"];
      $content = mysqli_real_escape_string($conn,$_POST["content"]);
      if ($title == "" || $content == "") {
        echo "Title and the content are neccessary to fill up";
      }
      elseif (isset($_POST["target"])) {
        $target = $_POST["target"];
        $timestamp = $conn->query("SELECT * FROM posts WHERE usr='$usr' ORDER BY ID DESC LIMIT 1");
        $id = $timestamp->fetch_assoc()['ID'];
        if (gettype($timestamp) == 'boolean') {
          $timestamp = null;
        } else {
          $timestamp = $timestamp->fetch_assoc()['timestamp'];
        }
        $difference = floor(($time-$timestamp)/60);
        if ($difference < 30) {
          echo "You have already posted. Wait for another ".(30-$difference)." minute(s) before you can post.";

          // echo "Unix: Current: $time, Old: $timestamp; difference in minutes: ".($difference)." Time lefttttttt: ".(30-$difference)." Post ID: $id";
        } else {
          $target = strtolower($target);
          $comm_id = $conn->query("SELECT ID FROM communities WHERE LOWER(Name)='$target'");
          if ($comm_id->num_rows == 0) {
            echo "Invalid community given: $target";
          } else {
            // $target = $comm_id->fetch_assoc()['ID'];
            // $query = "INSERT INTO posts (timestamp,title,target,content,usr) VALUES ($time,'$title','$target','$content','$usr')";
            // $res = $conn->query($query);
            // echo "0";

            echo "Data: ".implode($_POST,',').",$id"." Unix: Current: $time, Old: $timestamp; difference in minutes: ".($difference)." Time lefttttttt: ".(30-$difference)." Post ID: $id";
          }



        }
      }
      else {
        // $query = "INSERT INTO posts (title,content,usr) VALUES ('$title','$content','$usr')";
        // $res = $conn->query($query);
        // echo "0";
        $time = time();
        $timestamp = $conn->query("SELECT timestamp FROM posts WHERE usr='$usr' ORDER BY ID DESC LIMIT 1");
        if (gettype($timestamp) == 'boolean') {
          $timestamp = null;
        } else {
          $timestamp = $timestamp->fetch_assoc()['timestamp'];
        }
        $difference = floor(($time-$timestamp)/60);
        if ($difference < 30) {
          echo "You have already posted. Wait for another ".(30-$difference)." minute(s) before you can post.";
          // echo "Unix: Current: $time, Old: $timestamp; difference in minutes: ".($difference)." Time left: ".(30-$difference);
        } else {
          $query = "INSERT INTO posts (timestamp,title,content,usr) VALUES ($time,'$title','$content','$usr')";
          $res = $conn->query($query);
          echo "0";
          // echo "Content: $content; Title: $title; usr: $usr";
        }

      }
    }
    elseif ($_POST["type"] == "delPost") {
      $id = $_POST["pid"];
      $conn = new mysqli($HOSTNAME,$DBUSER,$DBPASS,$DBNAME);
      $conn->query("DELETE FROM votes WHERE post='$id'");
      $conn->query("DELETE FROM comments WHERE post_id='$id'");
      $conn->query("DELETE FROM posts WHERE ID='$id'");
    }
    elseif($_POST["type"] == "vote") {// if the information is a vote type
      $value = $_POST["value"];
      $postid = $_POST["pid"];
      $usr = $_SESSION['usr'];
      $conn = new mysqli($HOSTNAME,$DBUSER,$DBPASS,$DBNAME);
      $res = $conn->query("SELECT ID FROM users WHERE usr='$usr'"); // Get the ID of the current user
      $row = $res->fetch_assoc();
      $usr = $row['ID']; // Extract the ID
      $res = $conn->query("SELECT * FROM votes WHERE usr='$usr' AND post=$postid"); // get the vote record
      $ID = $res->fetch_assoc()['ID'];
      if ($res->num_rows == 0) { // If there isn't any
        if($value == 0 || $value == 1){
        $conn->query("INSERT INTO votes (post,usr,type) VALUES ($postid,$usr,$value)");
        echo "Casted vote";
      }
        else {
          exit('Wrong vote token');
        }

      } elseif($res->num_rows == 1) { // If there is one record
        // echo "You already have voted";
        $row =  $conn->query("SELECT type FROM  votes WHERE usr=$usr and post=$postid")->fetch_assoc();
        $type = $row['type']; // check the record's vote
        if ($value == "1") { // If the token is an upvote
          // echo "token is upvote; already = $type";
          if ($type == "0") { // If there is not vote casted
            $conn->query("UPDATE votes SET type=1 WHERE ID=$ID");
            echo "Added upvote";
          }
          elseif ($type == '1') { // If there already is a vote casted
            $conn->query("DELETE FROM votes WHERE ID=$ID");
            echo "cleared vote";
          }
        } elseif($value=="0") { // If the token is a downvote
          if ($type == "1") { // If a downvote is not yet  casted
            $conn->query("UPDATE votes SET type=0 WHERE ID=$ID");
            echo "Added downvote";
          }
          elseif ($type == '0') { // If the downvote is already casted
            $conn->query("DELETE FROM votes WHERE ID=$ID");
            echo "Cleared Vote";
          }
        }

      }

    } //
  }
  else {
    echo "You need to <a href='http://projhost:8088/Session/verify-window.php'>verify</a> your email in order to post/vote/delete";
  }
  if ($_POST["type"] == "editPost") {
    $conn = new mysqli($HOSTNAME, $DBUSER, $DBPASS, $DBNAME);
    $pid = $_POST['pid'];
    $query = "SELECT * FROM posts WHERE ID='$pid'";
    $res = $conn->query($query);
    $row = $res->fetch_assoc();
    if ($res->num_rows == 0) {
      echo "Post ID: not found";
    } else {
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
  }


} //
else {
  header("location:session.php");
}
 ?>
