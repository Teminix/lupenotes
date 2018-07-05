<?php
session_start();
function strcheck($str1,$str2) {
  if (strpos($str2,$str1) !== FALSE) {
    return TRUE;
  }
  if (strpos($str2,$str1) === FALSE) {
    return TRUE;
  }
}
function checkSQLinj($string) {
  $reschars = "!@#$%^&*(){}[]|\\:;".'""'."''"."<>,.?/";
  $strlen = strlen($reschars);
  foreach ($reschars as $reschar) {
    if (strcheck($string,$reschar) == TRUE) {
      return TRUE;
      }
    if (strcheck($string,$reschar) == FALSE) {
      return FALSE;
      }
    }
  }
  /*
  if (stripos($string,".") !== FALSE
    ||stripos($string,",") !== FALSE
    ||stripos($string,";") !== FALSE
    ||stripos($string,"/") !== FALSE
    ||stripos($string,"\\") !== FALSE
    ||stripos($string,"-") !== FALSE
    ||stripos($string,"'") !== FALSE
    ||stripos($string,'"') !== FALSE
    ||stripos($string,"(") !== FALSE
    ||stripos($string,")") !== FALSE
    ||stripos($string,"[") !== FALSE
    ||stripos($string,"]") !== FALSE
    ||stripos($string,"{") !== FALSE
    ||stripos($string,"}") !== FALSE
    ||stripos($string,"|") !== FALSE
    ||stripos($string,"<") !== FALSE
    ||stripos($string,">") !== FALSE
    ||stripos($string,":") !== FALSE
    ||stripos($string,"%") !== FALSE
    ||stripos($string,"$") !== FALSE
    ) {
      return "TRUE";
    }
    else {
      return "FALSE";
    }*/
}

function stringToArray($s)
{
    $r = array();
    for($i=0; $i<strlen($s); $i++)
         $r[$i] = $s[$i];
    return $r;
}


  if(!$_SERVER['REQUEST_METHOD']=="POST") {
    header("location:index.php");
  }
    if ($_POST['sub'] == "Register") { //If form is a register type
      $usr = $_POST['usr'];
      $pwd = $_POST['pass'];
      $display = $_POST['display'];
    if (checkSQLinj($usr)=="TRUE" || checkSQLinj($display)=="TRUE" || checkSQLinj($pwd)=="TRUE") {
        echo "you cannot use special characters in your inputs";
        exit;
      }
    if (strlen($usr) < 6 ||strlen($pwd) < 6 ||strlen($display) < 6) {
      echo "usr pwd and dislay name must have at least 6 characters";
      exit;
    }
      $conn = new mysqli("localhost","root","root","log"); // use OOP style to establish connection;
      $query = "SELECT * FROM user WHERE usr='$usr'";
      $res = $conn->query($query); // Query $query variable
      $check = $res->num_rows; // Calculate number of received rows as $check
      $row = $res->fetch_assoc(); // Fetch assoc row
      //echo $check;
      if ($check > 0) { //if there is more than one row found
        echo "Error: There is already a user registered named : <b style='color:red'>'".$usr."'</b> with ID: ".$row['id'];
      }
      if ($check == 0) { // if no registered users, then insert and register successfully
        $sql = "INSERT INTO user (usr,pass, display) VALUES ('$usr','$pwd','$display')";
        $exec = $conn->query($sql);
        echo "User with username: <b>". $usr . "</b> And Display name <i>".$display."</i> Has successfully been registered onto the server!";
      }


    }
    if ($_POST['sub'] == "Login") { //If form is a login type
      $conn = new mysqli("localhost","root","root","log");
      $usr = $_POST['usr'];
      $pass = $_POST['pass'];
      if (checkSQLinj($usr)=="TRUE" || checkSQLinj($pass) == "TRUE") {
          echo "you cannot use special characters in your inputs";
          exit;
        }
      $query = "SELECT * FROM user WHERE usr='$usr' AND pass='$pass'";
      $sql = $conn->query($query);
      //echo $sql->num_rows;
      $row = $sql->fetch_assoc(); // get row
      if ($sql->num_rows == 0) { // no entries found
        echo "Invalid username or password";
      }
      if ($sql->num_rows==1) { // entry found
        $_SESSION['id'] = $row['id'];
        $_SESSION['user'] = $row['usr'];
        $_SESSION['display'] = $row['display'];
        header("location:display.php");
      }
    }
   ?>
