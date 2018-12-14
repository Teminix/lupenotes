<?php

require "php/lib.php";
session_start();
$prompt = "prompt"; //Set the prompt variable
$rescharset = array("form" =>concat_array(strtoarray("{}[]:;|\\<>?,./!@#$%^&*()"),["'",'"']));


if(!$_SERVER['REQUEST_METHOD']=="POST") { //if method is not a post
  header("location:main.php");
}
if ($_SERVER['REQUEST_METHOD'] == "POST") { // if method is post
  $type = $_POST["type"]; // declare submission type var as $type
  if ($type == "register") {// if type = register
    $conn = new mysqli("localhost","root","root","project"); // use OOP style to establish connection;
    $usr = mysqli_real_escape_string($conn,$_POST["usr"]); // receive variable 'usr'
    $usr = strtolower($usr); // make it case insensitive
    // $email  = mysqli_real_escape_string($conn,$_POST["email"]);
    $display = mysqli_real_escape_string($conn,$_POST["display"]); // receive variable 'display'
    $pwd  = mysqli_real_escape_string($conn,$_POST["pwd"]);//  receive variable 'pwd'
    $verif_pwd = mysqli_real_escape_string($conn,$_POST["verif_pwd"]);
    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    if (!($pwd == $verif_pwd)) { // if both the passwords are not the same
      echo "The password must be verified (correctly) before registering";
    }
    elseif (strlen($usr) < 6 || strlen($display) < 6 || strlen($pwd) < 6) { // if Character expectation less then:
      echo "Username, display and password must be at least 6 characters";
    }
    elseif (strlen($usr) > 20 || strlen($display) > 20 || strlen($pwd) > 20) {
      echo "Username, display and password cannot exceed more than 20 characters";
      }
    elseif (rescharstr($usr,$rescharset["form"]) == "TRUE" || rescharstr($display,$rescharset["form"]) == "TRUE") {
      echo "You are not allowed to use characters: '".arraytostr($rescharset["form"],"', '")." for the username or display name";
    }
    else { // otherwise:

      $query = "SELECT * FROM users WHERE usr='$usr'";
      $res = $conn->query($query); // Query the $query variable
      $check = $res->num_rows; // Calculate number of received rows as $check
      if ($check == 1) {//if user exists
        echo "User with username ".$usr." already exists. If you want to login, <a href='/login.php'>Go here</a>";
      }
      if ($check == 0) {//if not
        if (verify_email($email)) {
          // echo "Elligible email address";
          $code = rand_str(14);
          $query = "INSERT INTO users (usr,email,v_code,display,pwd) VALUES ('$usr','$email','$code','$display','$pwd')"; // insert user query
          $res = $conn->query($query); // execut $query and store in $res just in case
          $_SESSION["usr"] = $usr; // Set usr Session variable
          $_SESSION["display"] = $display; // Set display name session variable
          echo "0";
        } else {
          echo "Invalid email address";
        }
      }
    }

  }
  if ($type == "login") {// if type = login
    $conn = new mysqli("localhost","root","root","project"); //Connect to the database 'project'
    $usr = mysqli_real_escape_string($conn,$_POST["usr"]);
    $usr = strtolower($usr);
    $pwd = mysqli_real_escape_string($conn,$_POST["pwd"]);
    $query = "SELECT * FROM users WHERE usr='$usr' AND pwd='$pwd'";// Here is the query
    $res = $conn->query($query);// Execute query in var "res"
    $check = $res->num_rows;// check for rows
    $row = $res->fetch_assoc();// get the row
    if ($check == 0) {// if username or password does not spit any matches
      echo "Invalid username or password";
    }
    if ($check == 1) { // if username and password does match
      $_SESSION["usr"] = $row["usr"];
      $_SESSION["display"] = $row["display"];
      echo "0";
    }
  }
}


 ?>
