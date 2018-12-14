<?php
session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["usr"])) {
      // echo "successfully sent comment lol";
      if ($_POST["type"] == "write") {
        $time = time();
        $usr = $_SESSION["usr"];
        $conn = new mysqli("localhost","root","root","project");
        $timestamp = $conn->query("SELECT timestamp FROM comments WHERE usr='$usr' ORDER BY ID DESC LIMIT 1");
        if (gettype($timestamp) == 'boolean') {
          // echo "null";
          $timestamp = null;
        } else {
          // echo "Not null";
          $timestamp = $timestamp->fetch_assoc()['timestamp'];
        }
        $difference = floor(($time-$timestamp)/60);
        if($difference < 5){
          echo "You have already commented earlier, wait for another ".(5-$difference)." minute(s)";
        }
        else{
          $content = mysqli_real_escape_string($conn,$_POST["comment-content"]);
          $node = $_POST["node"];
          $postid = $_POST["post_id"];
          if ($content == "") {
            echo "You need to fill in some content of the comment!";
          }
          else {

            $res = $conn->query("INSERT INTO comments (timestamp,usr,content,post_id,node) VALUES ($time,'$usr','$content','$postid','$node')");
            echo "0";
            // echo "Old time: $timestamp; This time: $time, difference: $difference";
          }
        }

      }
      elseif ($_POST["type"] == "edit") {
        $conn = new mysqli("localhost","root","root","project");
        $id = mysqli_real_escape_string($conn,$_POST['id']);
        $content = mysqli_real_escape_string($conn,$_POST['content']);
        $res = $conn->query("SELECT * FROM comments WHERE ID=$id");
        $row = $res->fetch_assoc();
        if ($row["usr"] == $_SESSION['usr']) {
          if ($content == "") {
            echo "You need to enter something in the comment box";
          }
          else {
            $conn->query("UPDATE comments SET content='$content' WHERE ID=$id");
            echo "0";
          }
        }
        else {
          echo "Invalid comment owner";
        }

      }
      elseif ($_POST["type"] == "delete") {
        $children = $_POST['children'];
        $conn = new mysqli("localhost","root","root","project");
        $id = mysqli_real_escape_string($conn,$_POST['id']);
        $res = $conn->query("SELECT * FROM comments WHERE ID=$id");
        $row = $res->fetch_assoc();
        if ($_SESSION["usr"] == $row["usr"]) {
          $conn->query("DELETE FROM comments WHERE ID=$id");
          echo "Deleted comment id: $id";
          foreach ($children as $value) {
            $conn->query("DELETE FROM comments WHERE ID=$value");
            echo "Deleted child with id: $value";
          }

        }
        else {
          echo "Invalid comment owner";
        }
      }

    }
    else {
      echo "You are not logged in. In order to comment, you have to login or register";
    }
  }
  else {
    header("location:../main.php");
  }
 ?>
