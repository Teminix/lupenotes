
<?php
  $c = strtolower($_GET["c"]);
  $usr = $_GET["sess_usr"];
  $conn = new mysqli("localhost","root","root","project");
  $res = $conn->query("SELECT * FROM communities WHERE LOWER(Name)='$c'");
  $data = $res->fetch_assoc();
  $sess_res = $conn->query("SELECT * FROM users WHERE usr='$usr'");
  $sess_row = $sess_res->fetch_assoc();
  $name = $data["Name"];
  $description = $data["description"];
  $comm_id = $data["ID"];
  $res = $conn->query("SELECT * FROM posts WHERE target=$comm_id ORDER BY ID DESC LIMIT 5");
?>


<br>
<br>
<br>
<h1>
<?php echo $c; ?>
</h1>
<h4><?php echo $description; ?></h4>
<div class="nav" toggleElem>
  <div class="nav-item active" toggle onclick="toggleActiveElem(this)">
    <a onclick="snype.setState('lupe')">Lupe</a>
  </div>
  <div class="nav-item" toggle onclick="redirect('Session/editor.php?target=<?php echo $name; ?>')">
    <a onclick=''>Post</a>
  </div>
</div>
<br><br><br>
<div s-frame="main" s-parent s-default="lupe">

<div s-frame="lupe" s-child>
<div class="container" id="posts">
<?php
if ($res->num_rows == 0) {
  echo "<div class='info'>
    No posts for this lupe
  </div>";
}
else {
while ($row= $res->fetch_assoc()) { // Post
  $title = $row["title"];
  $content = $row["content"];
  $id = $row["ID"];
  $post_usr = $row["usr"];
  if ($usr == $row["usr"]) {
    $edit = "<button type='button' purpose='post' name='edit' onclick='redirect(\"http://projhost:8088/Session/editor.php?editPost&id=$id\")'>Edit</button>
    <button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>";
  }
  else {
    $edit = "";
  }
  $res_usr = $conn->query("SELECT * FROM users WHERE usr='$post_usr'");
  $row_usr = $res_usr->fetch_assoc();
  $user_id = $row_usr['ID'];
  // Voting
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
  //Voting
  $user_img = $row_usr["image"];
  $user_disp = $row_usr['display'];
  echo
  "<div class='post' post-id='$id'>
  <span class='info-wrapper' onclick='window.location.href=\"../users/users.php?u=$post_usr\"'>
    <img src='../dps/$user_img' alt=''>
    <span>$user_disp</span><br>
    <span class='usr'>$post_usr</span>
  </span><br>
    <textarea readonly name='title' class='title' rows='1'>$title</textarea><br><br>
    <textarea class='postContent' name='content' readonly>$content</textarea>
    <span class='p'></span>
    $edit
    <div class='vote-section' voted='$vote_type'>
      <button class='upvote' onclick='vote(this,\"up\")'>
        <img src='../images/up.png' class='button'>
      </button>
      <p class='score'>$rep</p>
      <button class='downvote' onclick='vote(this,\"down\")'>
        <img src='../images/down.png' class='button' >
      </button>
    </div><br>
    <button class='right'><a href='http://projhost:8088/Session/view-post.php?id=$id'>View post</a></button>

  </div>

   "; }
   if ($res->num_rows == 5) {
     echo "<div class='loadmore' offset='5' type='comm/$comm_id' onclick='loadmore(this)'>
       <div class='load'>
         Load more
       </div>
       <div class='image'>

       </div>
     </div>";
   }
    //
}

   ?>

 </div>

 </div>
 <div s-child s-frame="post">
   <?php
   if($sess_row['email_v'] == 0){
     echo '<div class="info">
     You need to <a href="Session/verify-window.php">verify</a> your email in order to post.
     </div>';
   }
   else {
     echo '<div class="post"> <!-- POST FORM/DIV -->
       <form>
         <span class="title">Write a post:</span><br>
         <textarea name="title" class="title" rows="1" placeholder="Title of Post"></textarea><br><br>
         <textarea name="content" rows="8" cols="80" placeholder="Content of the post.."></textarea><br>
         <span class="p" style="font-size:13px"></span>
         <!-- <button type="button" name="submitPost" onclick="post_form(this,{ext:{type:\'createPost\'},url:\'Session/post.php\',redirect:\'http://projhost:8088/communities.php?c=<?php echo $c; ?>\'})">POST</button>
         <button type="button" name="reset" onclick="resetVal([this.parentNode.children[4],this.parentNode.children[8]])">CANCEL</button> -->
         <div>
           <button type="button" class="right" onclick="post_form(this,{ext:{type:\'createPost\',target:'.$comm_id.'},url:\'Session/post.php\'})">POST</button>
           <button type="button" class="right">CANCEL</button>
         </div>

       </form>
     </div>';
   }
   ?>
 </div>
</div>
<script type="text/javascript">
  var snype = new Snyper("main");
</script>
