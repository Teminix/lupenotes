<?php
session_start();
$id = $_GET["id"];
$conn = new mysqli("localhost","root","root","project");
$res = $conn->query("SELECT * FROM posts WHERE ID=$id");
$row = $res->fetch_assoc();
$usr  = $row["usr"];
$title = $row["title"];
$content = $row["content"];
$row_usr = $conn->query("SELECT ID,display,image FROM users WHERE usr='$usr'")->fetch_assoc();
$post_disp = $row_usr['display'];
$user_id = $row_usr['ID'];
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
 ?>


 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="styles.css">
     <link rel="stylesheet" href="../styles/nav.css">
     <title>Post: <?php echo $id; ?></title>
     <script src="../scripts/js/main.js" charset="utf-8">
     </script>
     <script>
     </script>
   </head>
   <body>
     <div class="nav">
       <div class="nav-item">
         <a href="../main.php">REGISTER</a>
       </div>
       <div class="nav-item">
         <a href="../communities.php">LUPES</a>
       </div>
     </div>
     <div class='post' post-id='<?php echo $id; ?>'>
       <div readonly name='title' class='title' rows='1'><?php echo "$title <c blue>=> $post_disp</c>"; ?></div><br><br>
       <div class='postContent' name='content' style="margin-left:20px;"><?php echo $content; ?></div>
       <span class='p'></span>

<?php
      if(isset($_SESSION["usr"])){
        if ($usr == $_SESSION['usr']) {
          $edit_delete="<button type='button' purpose='post' name='edit' onclick='redirect(\"editor.php?editPost&id=$id\")'>Edit</button>
          <button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>";
        }
      echo
      "
      $edit_delete
      <div class='vote-section' voted='$vote_type'>
         <button class='upvote' onclick='vote(this,\"up\")'>
           <img src='../images/up.png' class='button'>
         </button>
         <p class='score'>$rep</p>
         <button class='downvote' onclick='vote(this,\"down\")'>
           <img src='../images/down.png' class='button'>
         </button>
       </div>";
     }
 ?><br><br>
       <br>
       <div class="header">
         Comments
       </div>
       <div class='comment-section'>
         <div class='comments'>
           <!-- <div class="comment">
             <span class="info-wrapper">
               <img src="../dps/default.jpg" alt="">
               <span>KibiraIsGod</span><br>
               <span class="usr">Kibira19</span>
             </span>
             This is a comment
           </div> -->
           <?php
            $res = $conn->query("SELECT * FROM comments WHERE post_id=$id");
            if ($res->num_rows == 0) {
              echo "<div class='info'>
                This post does not contain any comments
              </div>";
            } else {
              while ($row=$res->fetch_assoc()) {
                $user = $row["usr"];
                $comm_id = $row["ID"];
                $node = $row["node"];
                $content = $row["content"];
                if ($_SESSION["usr"] == $user) {
                  $buttons = "
                  <div class='buttons' node='$node'>
                    <button class='mini_button' onclick='comm_func(this,\"reply\")'>Reply</button>
                    <button class='mini_button' onclick='comm_func(this,\"delete\")'>Delete</button>
                    <button class='mini_button' onclick='comm_func(this,\"edit\")' mode='edit'>Edit</button>
                  </div>
                  ";
                }
                else {
                  $buttons = "
                  <div class='buttons'>
                    <button class='mini_button' onclick='comm_func(this,\"reply\")'>Reply</button>
                  </div>
                  ";
                }
                $res1 = $conn->query("SELECT * FROM users WHERE usr='$user'");
                $row1 = $res1->fetch_assoc();
                $user_disp = $row1["display"];
                $user_img = $row1["image"];
                // echo "<div class='comment'>$res1</div>"
                echo "<div class='comment' id='$comm_id' node='$node'>
                  <span class='info-wrapper' onclick='window.location.href=\"../users/$user.php\"'>
                    <img src='../dps/$user_img' alt=''>
                    <span>$user_disp</span><br>
                    <span class='usr'>$user</span>
                  </span><br>
                  <span class='content-wrapper'>
                  $content
                  </span>
                  $buttons
                </div>";
              }
            }

            ?>
         </div>
         <?php
         $usr_res = $conn->query("SELECT * FROM users WHERE usr='".$_SESSION["usr"]."'");
         $usr_row = $usr_res->fetch_assoc();
         if ($usr_row['email_v'] == 0) {
           echo '<div class="info">
           You need to <a href="verify-window.php">verify</a> your email in order to comment
           </div>';
         }
         else {
           echo '<div class="comment-form" node="main">
             <textarea name="comment-content" type=\'text\' placeholder="Comment..." onkeyup="if(event.keyCode==27){this.blur()}" rows="4" name="commentBox"></textarea>
             <button type="button" name="main-post" onclick="post_comment(this)" class="right">POST</button>
             <span class="prompt"></span>
           </div>';
         }
         ?>

       </div>
     </div>
     <script type="text/javascript">
       init_vote();
       var comments = document.getElementsByClassName('comments')[0];
       sort_comment(comments);
     </script>
   </body>
 </html>
