<?php

  session_start();

  $HOSTNAME = "localhost";
  $USR = "root";
  $PWD = "root";
  $DBNAME = "project";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if($_POST["type"] == "username"){
      $old_user = $_SESSION["usr"];
      //$old_display = $_SESSION["display"];
      $pass = $_POST["pwd"];
      $new_user = $_POST["usr"];
      $new_display = $_POST["display"];

      $conn = new mysqli('localhost','root','root','project'); // Make a connection
      $query = "SELECT * FROM users WHERE usr='$old_user'"; // Make the query variable
      $res = $conn->query($query); // Result of the query

      $row = $res->fetch_assoc(); // fetch the associated row of the query result and return as array
      $id = $row["ID"];
      /*echo $row["usr"]." is your user<br>";
      echo $row["display"]." is your display<br>";
      echo $row["pwd"]." is your password<br>";*/
      if ($pass == $row["pwd"]) {
        if((strlen($new_user) < 6 || strlen($new_display) < 6) || (strlen($new_user) > 20 || strlen($new_display) > 20)) {
          echo "New Username and display name must be at least 6 characters and at max 20 characters";
        }

        else {
          $new_user_check = $conn->query("SELECT * FROM users WHERE NOT ID='$id' AND usr='$new_user'");
          $new_user_check = $new_user_check->num_rows;
          if ($new_user_check == 1) {
            echo "Another user has the username: ".$new_user;
          }
          if ($new_user_check == 0) {
          $query = "UPDATE users SET usr='$new_user', display='$new_display' WHERE ID=".$row["ID"];
          $res = $conn->query($query);
          $_SESSION["usr"] = $new_user;
          $_SESSION["display"] = $new_display;
          rename("../users/$old_user.php","../users/$new_user.php");
          echo "1";}}
        }
      else {
        echo "Wrong password, try again.";
      }} // for the username change

      elseif ($_POST["type"] == "password") { // for the password change
        //echo "worked";
        //echo $_SESSION["usr"];
        $conn = new mysqli("localhost","root","root","project");
        $query = "SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'";
        $res = $conn->query($query);
        $row = $res->fetch_assoc();
        $pass = $row["pwd"];
        $new_pass = $_POST["np"];
        if (!($_POST["op"] == $pass)) {
          echo "Incorrect password(s), try again";
        }
        else {
            if (strlen($new_pass) < 6 || strlen($new_pass) > 20 ){echo "0";}
            else {
            $conn->query("UPDATE users SET pwd='$new_pass' WHERE ID=".$row["ID"]);
            echo "1";}
        }
      }
      elseif ($_POST["type"] == "profile") {

        $file =  $_FILES["fileupload"];
        $fileName = $file["name"];
        $conn = new mysqli("localhost","root","root","project");
        $query = "SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'";
        $res = $conn->query($query);
        $row = $res->fetch_assoc();
        $id = $row["ID"];
        $dest = "../dps/".$id.".jpg";
        $query = "UPDATE users SET image='$id.jpg' WHERE id='$id'";
        $res = $conn->query($query);

        move_uploaded_file($file["tmp_name"],$dest);
        header("location:session.php");
        //echo "test";
      }
      elseif ($_POST["clear"] == "TRUE") {
        $conn = new mysqli($HOSTNAME,$USR,$PWD,$DBNAME);
        $res = $conn->query("SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'");
        $row = $res->fetch_assoc();
        $id = $row["ID"];
        //echo "User Details:<br> Username: ".$_SESSION["usr"]."<br> Display: ".$_SESSION["display"]."<br> ID: $id";
        $conn->query("UPDATE users SET image='default.jpg' WHERE ID='$id'");
        unlink("../dps/$id.jpg");
      }

      elseif ($_POST['type'] == "deactivate") {
        $conn = new mysqli($HOSTNAME,$USR,$PWD,$DBNAME);
        $usr = $_SESSION['usr'];
        $res = $conn->query("SELECT * FROM users WHERE usr='$usr'");
        $row = $res->fetch_assoc();
        if ($row["pwd"] == $_POST['pwd']) {
          $conn->query("DELETE FROM users WHERE usr='$usr'");
          $conn->query("DELETE FROM posts WHERE usr='$usr'");
          $conn->query("DELETE FROM comments WHERE usr='$usr'");
          unlink("../users/$usr.php");
          session_unset();
          session_destroy();
          echo "0";
        }
        elseif ($_POST['pwd'] == "") {
          echo "Password is required";
        }
        else {
          echo "Incorrect password";
        }


      }

  }
  else {
    header("location:session.php");
  }

 ?>
