<?php
session_start();
$conn = new mysqli('localhost','root','root','project');
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>
    <?php
        if (isset($_GET["u"])) {
          echo "User: ".$_GET["u"];
        }
        else {
          echo "Users";
        }
     ?></title>
     <style media="screen">
      h1.major {
        color:white;
        font-size:70px
      }
      input.search {
        display:block;
        width:100%;
        background-color:rgba(20,20,20,0.5);
        padding-left:20px;
        border-radius:40px;
      }

      input.search::placeholder {
        color:gray;
      }
     </style>
     <script src="scripts/js/main.js" charset="utf-8"></script>
     <script src="scripts/js/circle.js" charset="utf-8"></script>
     <link rel="stylesheet" href="../styles/main.css">
     <link rel="stylesheet" href="Session/styles.css">
  </head>
  <body>
    <div class="nav">
      <div class="nav-item">
        <a href="main.php">REGISTER</a>
      </div>
      <div class="nav-item">
        <a href="communities.php">LUPES</a>
      </div>
      <?php if (isset($_SESSION['usr'])) {
        $sess_usr = $_SESSION['usr'];
        $image = $conn->query("SELECT image FROM users WHERE usr='$sess_usr'")->fetch_assoc()['image'];
        echo "<div class='nav-item right'>
          <a href='Session/session.php' class='image'>
          <img src='dps/$image'>
          </a>
        </div>";
      } ?>

    </div>
      <?php
        if (isset($_GET["u"])) {
          $get_usr = $_GET['u'];
          echo '
          <div class="main">

          ';
          $res = $conn->query("SELECT * FROM users WHERE usr='$get_usr'");
          $row = $res->fetch_assoc();
          $usr = $row['usr'];
          $display = $row['display'];
          echo "<img src='../dps/".$row["image"]."' class='dp'>";
           echo "
        <br>

        <!-- INFORMATION AND USER BUTTONS -->
        <table>
          <tr>
            <td>Username:</td>
            <td>$usr</td>
          </tr>
          <tr>
            <td>
              Display Name:
            </td>
            <td>
              $display
            </td>
          </tr>
        </table><br /><br />
        POSTS by user:



 ";
 $query = "SELECT * FROM posts WHERE usr='$get_usr' ORDER BY ID DESC";
 $res = $conn->query($query);
 if ($res->num_rows >= 1) {
   while ($row = $res->fetch_assoc()) {
     $title = $row["title"];
     $content = $row["content"];
     $rep = $row["reputation"];
     $id = $row["ID"];

     $res_usr = $conn->query("SELECT * FROM users WHERE usr='$get_usr'");
     $row_usr = $res_usr->fetch_assoc();
     $user_id = $row_usr['ID'];
     // echo "PostID = $id; USER_ID = $user_id";
     $downvotes = $conn->query("SELECT * FROM votes WHERE post=$id AND type=0")->num_rows;
     $upvotes = $conn->query("SELECT * FROM votes WHERE post=$id AND type=1")->num_rows;
     $rep = $upvotes-$downvotes;
     $usr_vote = $conn->query("SELECT type FROM votes WHERE post=$id AND usr=$user_id");
     if ($usr_vote->num_rows == 0) {
       $vote_type = 0;
     } else {
       $cast = $usr_vote->fetch_assoc()['type'];
       if ($cast == 0) {
         $vote_type = 'down';
       }
       elseif ($cast == 1) {
         $vote_type = 'up';
       }
     }
     $user_img = $row_usr["image"];
     $user_usr = $_SESSION['usr'];
     $user_disp = $row_usr['display'];
     $target = $row['target'];
     if ($target == "0") {
       $comm = "";
     }
     else {
       $res_comm = $conn->query("SELECT * FROM communities WHERE ID='$target'")->fetch_assoc();
       $comm = $res_comm['Name'];
       $comm = "posted to <a href='../communities.php?c=$comm'>$comm</a> ";
     }
     if ($row_usr['email_v'] == 1) {
       $voting = "
       <div class='vote-section' voted='$vote_type'>
         <button class='upvote' onclick='vote(this,\"up\")'>
           <img src='../images/up.png' class='button'>
         </button>
         <p class='score'>$rep</p>
         <button class='downvote' onclick='vote(this,\"down\")'>
           <img src='../images/down.png' class='button' >
         </button>
       </div>";
     } else {
       $voting = "<div class=\"info\">
       You need to <a href=\"verify-window.php\">verify</a> your email in order to delete or vote
       </div>";
     }

     echo
     "<div class='post' post-id='$id'>
     <span class='info-wrapper' onclick='window.location.href=\"../users/$post_usr.php\"'>
       <img src='../dps/$user_img' class='dp'>
       <span>$user_disp</span><br>
       <span class='usr'>$user_usr $comm</span>
     </span><br>
       <textarea readonly name='title' class='title' rows='1'>$title</textarea><br><br>
       <textarea class='postContent' name='content' readonly>$content</textarea>
       <span class='p'></span>

       $voting<br>
       <button onclick=\"redirect('Session/view-post.php?id=$id')\" class='right'>View post</button>
     </div>

     ";
   }
 } else {
   echo '<div class="info">
     No posts to display
   </div>';
 }


        echo "
        </div>";
        }
        else {
          echo '<center><h1 class="major">USERS</h1></center><input type="text" class="search" placeholder="Search" name="" value="">';
          $query = "SELECT * FROM users WHERE usr <> '".$_SESSION['usr']."'";
          $res = $conn->query($query);
          while($row = $res->fetch_assoc()){

          $image = $row['image'];
          $usr = $row['usr'];
          $display = $row['display'];
          echo "<div class='circle' baseradius='100px' hoverradius='200px' bg='dps/$image' searchable onclick='redirect(\"users.php?u=$usr\")'>
            <div class='circle-wrapper'>
              <div class='title'>
              $display
              </div>
              <div class='description'>
              $usr
              </div>
            </div>
          </div>";}
        }
       ?>
       <script type="text/javascript">
         init_vote()
       </script>
  </body>
</html>
