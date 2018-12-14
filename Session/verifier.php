<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../php/vendor/autoload.php';
require '../php/lib.php';
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["type"] == "send") {
        $session_usr = $_SESSION["usr"];
        $conn = new mysqli("localhost","root","root","project")  ;
        $res = $conn->query("SELECT email,email_v,v_code FROM users WHERE usr='$session_usr'");
        $row = $res->fetch_assoc();
        if ($row["email_v"] == "0") {
          // $email = $row["email"];
          $email = mysqli_real_escape_string($conn,$_POST["email"]);
          // echo "email is ".$email;
          // Mail initialiser
          $code = $row["v_code"];
          $usr = "kabirdesarkar2016@gmail.com";
          $pass = openssl_decrypt("JidqwWYoYuAmq4Ir2JtLMQ==","AES-256-CBC","ichigosan");
          $mail = new PHPMailer(true);
          // $mail->SMTPDebug = 1;
          $mail->isSMTP();                                      // Set mailer to use SMTP
          $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
          $mail->SMTPAuth = true;                               // Enable SMTP authentication
          $mail->Username = $usr;                 // SMTP username
          $mail->Password = $pass;                           // SMTP password
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 587;
          $mail->setFrom($usr,"lupenotes helper");
          $mail->addAddress("$email");
          $mail->isHTML(true);
          $mail->Subject = "Concerning mail verification code";
          $mail->Body = "
          Dear lupenotes client,
          This is the code to verify your email address for lupenotes.com.

          <h3 style='color:red'>If you aren't aware about this, kindly ignore this email</h3>
          Copy the verification code and paste in the vierification tab:<br /> <b style='font-size:20px;'>$code</b>";

          try {
            $mail->send();
            echo "Sent";
            // $conn->query("UPDATE users SET email_v=1 WHERE usr='$session_usr'");
          } catch (\Exception $e) {
            echo "Error while sending code";
          }
        } else {
          echo "Email already verified.";
          // echo $row["email_v"];
        }
    }
    elseif ($_POST["type"] == "code") { // THis is the procedure to verify the code
      $conn = new mysqli("localhost","root","root","project");
      $code = mysqli_real_escape_string($conn,$_POST["code"]);
      $email = mysqli_real_escape_string($conn,$_POST["email"]);
      $session_usr = $_SESSION["usr"];
      $res = $conn->query("SELECT v_code,email_v FROM users WHERE usr='$session_usr'");
      $row = $res->fetch_assoc();
      // print_r($row);
      if ($row["email_v"] == 1) {
        echo "Email already verified";
      }
      else {
        if ($code == $row['v_code']) {
          $conn->query("UPDATE users SET email_v=1,email='$email' WHERE usr='$session_usr'");
          echo "0";
        }
        else {
          echo "Invalid code";
          // echo $code;
        }
      }

    }
    elseif($_POST["type"] == "changeEmail"){
      if ($_POST["level"] == 1) {
        $conn = new mysqli("localhost","root","root","project");
        $session_usr = $_SESSION["usr"];
        $res = $conn->query("SELECT email,v_code FROM users WHERE usr='$session_usr'");
        $row = $res->fetch_assoc();
        $email = $row["email"];
        $code = $row["v_code"];
        $usr = "kabirdesarkar2016@gmail.com";
        $pass = openssl_decrypt("JidqwWYoYuAmq4Ir2JtLMQ==","AES-256-CBC","ichigosan");
        $mail = new PHPMailer(true);
        // $mail->SMTPDebug = 1;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $usr;                 // SMTP username
        $mail->Password = $pass;                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->setFrom($usr,"lupenotes helper");
        $mail->addAddress("$email");
        $mail->isHTML(true);
        $mail->Subject = "Changing lupenotes account linked email confirmation";
        $mail->Body = "
        Dear lupenotes client: $session_usr,
        This is the code to change of email address for account in lupenotes.com.

        <h3 style='color:red'>If you aren't aware about this, kindly ignore this email</h3>
        Copy the verification code and paste in the confirmation tab:<br /> <b style='color:blue;font-size:20px;'>$code</b>";

        try {
          $mail->send();
          echo "0";
          // $conn->query("UPDATE users SET email_v=1 WHERE usr='$session_usr'");
        } catch (\Exception $e) {
          echo "Error while sending code";
        }
      }
      elseif ($_POST["level"] == 2) {
        $received_code  = $_POST["code"];
        $session_usr = $_SESSION["usr"];
        $conn = new mysqli("localhost","root","root","project");
        $res = $conn->query("SELECT v_code FROM users WHERE usr='$session_usr'");
        $row = $res->fetch_assoc();
        $code = $row["v_code"];
        // echo "received: $received_code; actual: $code";
        if ($received_code == $code) {
          $new_code = rand_str(14);
          $conn->query("UPDATE users SET email_v=0,v_code='$new_code' WHERE usr='$session_usr'");
          echo "0";
        }
        else {
          echo "Invalid confirmation code";
        }
      }
    }
    else {
      echo "Tnvalid request type";
    }
 //

  }
  else {
    // echo "oof";
    header("location:../main.php");
  }

  // echo rand_str(14);

 ?>
