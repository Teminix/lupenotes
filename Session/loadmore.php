<?php

session_start();
$usr = $_SESSION['usr'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $offset = $_POST['offset'];
  $type = $_POST['type'];
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $offset = $_GET['offset'];
  $type = $_GET['type'];
}
  // echo "offset = $offset\n type = $type";
  $conn = new mysqli('localhost','root','root','project');
  $uid = $conn->query("SELECT ID FROM users WHERE usr='$usr'")->fetch_assoc()["ID"];
  $split = explode('/',$type);
  // echo "\nSplit up type: ".json_encode($split)."\n";
  if ($split[0] == "comm") {
    $posts = ['posts'=>[],'loadmore'=>false];
    $id = $split[1];
    $after_offset = $offset + 5;
    $res_count = $res = $conn->query("SELECT COUNT(*) AS count FROM (SELECT * FROM posts WHERE target='$id' ORDER BY ID DESC LIMIT $after_offset,1) AS a")->fetch_assoc()['count'];
    if($res_count >= 1){
      $posts['loadmore'] = true;
    };
    $res = $conn->query("SELECT * FROM posts WHERE target=$id ORDER BY ID DESC LIMIT $offset,5");
    while ($row = $res->fetch_assoc()) { // Row is the post row
      $temp = [];
      $id = $row['ID'];
      $title = $row['title'];
      $content = $row['content'];
      $post_usr = $row['usr'];
      $post_usr_row = $conn->query("SELECT display,image FROM users WHERE usr='$post_usr'")->fetch_assoc();
      $display = $post_usr_row['display'];
      $image = $post_usr_row['image'];
      $usr_vote = $conn->query("SELECT type FROM votes WHERE usr=$uid");
      $upvotes = $conn->query("SELECT type FROM votes WHERE post=$id AND type=1")->num_rows;
      $downvotes = $conn->query("SELECT type FROM votes WHERE post=$id AND type=0")->num_rows;
      $rep = $upvotes-$downvotes;
      if($post_usr == $_SESSION['usr']){
        $edit = "<button type='button' purpose='post' name='edit' onclick='redirect(\"editor.php?editPost&id=$id\")'>Edit</button>
        <button type='button' purpose='post' onclick='deletePost(this)'>Delete</button>";
      }
      else {
        $edit = "";
      }
      if($usr_vote->num_rows == 0){
        $vote_type = 0;
      }
      elseif($usr_vote->num_rows == 1){
        $type = $usr_vote->fetch_assoc()['type'];
        if($type == 1){
          $vote_type = 'up';
        }
        elseif ($type == 0) {
          $vote_type = 'down';
        }
      }
      $temp['id'] =  $id; // The post Id
      $temp['post_usr'] = $post_usr;
      $temp['user_img'] = $image;
      $temp['user_disp'] = $display;
      $temp['title'] = $title;
      $temp['content'] = $content;
      $temp['edit'] = $edit;
      $temp['vote_type'] = $vote_type;
      $temp['rep'] = $rep;
      array_push($posts['posts'],$temp);
    }
    echo json_encode($posts);


  } elseif ($split[0] == "user") {
    // code...
  } elseif ($split[0] == "comment") {

  } else {
    // code...
  }

/*
else {
  echo "<script>
    history.go(-1)
  </script>";
}*/

 ?>
