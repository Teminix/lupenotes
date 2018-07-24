<?php
session_start();
$id = $_GET["id"];
$conn = new mysqli("localhost","root","root","project");
$res = $conn->query("SELECT * FROM posts WHERE ID=$id");
$row = $res->fetch_assoc();
$usr  = $row["usr"];
$title = $row["title"];
$content = $row["content"];
$rep = $row['reputation'];
$rep_vote = $row['votes'];
eval("\$rep_array = array($rep_vote);");
// $rep_array = arraytostr($rep_array);
if(array_key_exists($_SESSION["usr"],$rep_array)){
  if($rep_array[$_SESSION["usr"]] == "d"){
    $vote_type="down"; // if the user has already downvoted the post then make the div downvoted
  }
  elseif ($rep_array[$_SESSION["usr"]] == "u") {
    $vote_type = "up"; // if the user has already upvoted the post then make the div upvoted
  }}
else {
  $vote_type = "0";
}
 ?>


 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="styles.css">
     <title>Post: <?php echo $id; ?></title>
     <script src="../scripts/js/main.js" charset="utf-8">

     </script>
     <script>

     </script>
   </head>
   <body>
     <div class='post' post-id='<?php echo $id; ?>'>
       <textarea readonly name='title' class='title' rows='1'><?php echo $title; ?></textarea><br><br>
       <textarea class='postContent' name='content' readonly style="margin-left:20px;background-color:rgb(20,20,20)"><?php echo $content; ?></textarea>
       <span class='p'></span>

<?php
      if(isset($_SESSION["usr"])){
      echo
      "<button type='button' purpose='post' name='edit' onclick='makeEdit(this)'>Edit</button>
      <button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>
      <div class='vote-section' voted='$vote_type'>
         <button class='upvote' onclick='vote(this,\"up\")'>
           <img src='../images/up.png' class='button'>
         </button>
         <p class='score'>$rep</p>
         <button class='downvote' onclick='vote(this,\"down\")'>
           <img src='../images/down.png' class='button'>
         </button>
       </div>";}
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
            while ($row=$res->fetch_assoc()) {
              $user = $row["usr"];
              $comm_id = $row["ID"];
              $node = $row["node"];
              $content = $row["content"];
              if ($_SESSION["usr"] == $user) {
                $buttons = "
                <div class='buttons'>
                  <button class='mini_button' onclick='comm_func(this,\"reply\")'>Reply</button>
                  <button class='mini_button' onclick='comm_func(this,\"delete\")'>Delete</button>
                  <button class='mini_button' onclick='comm_func(this,\"edit\")' mode='edit'>Edit</button>
                </div>
                ";
              }
              else {
                $buttons = "
                <div class='buttons'>
                  <button class='mini_button' onclick='comm_func(this.parentElement,\"reply\")'>Reply</button>
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
            ?>
         </div>
         <div class='comment-form' node="main">
           <textarea name="comment-content" type='text' placeholder='Comment...' onkeyup="if(event.keyCode==27){this.blur()}" rows="4" name="commentBox"></textarea>
           <button type="button" name="main-post" onclick="post_comment(this)">POST</button>
           <span class="prompt"></span>
         </div>
       </div>
     </div>
     <script type="text/javascript">
       init_vote();
       var comments = document.getElementsByClassName('comments')[0];
       sort_comment(comments);
     </script>
   </body>
 </html>
