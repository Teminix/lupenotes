<?php
session_start();
  if (!isset($_SESSION["usr"])) {
    header('location:../index.php');
  }
 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Post editor</title>
     <script src="../scripts/js/main.js" charset="utf-8"></script>
     <script src="../scripts/js/modal.js" charset="utf-8"></script>
     <script src="../scripts/Libraries/jquery.js" charset="utf-8"></script>
     <link rel="stylesheet" href="styles.css">
     <style media="screen">
       form {
         padding:20px
       }
     </style>
   </head>
   <body>
     <div class="post"> <!-- POST FORM/DIV -->
       <form>
         <span class="title">Write a post:</span><br>
         <textarea name="title" class="title" rows="1" placeholder="Title of Post"></textarea><br><br>
         <textarea name="content" rows="8" cols="80" placeholder="Content of the post.."></textarea><br>
         <span class="p" style="font-size:13px"></span>
         <button type="button" name="submitPost" id="post" onclick="post_form(this,{ext:{type:'createPost'},url:'post.php'})">POST</button>
         <button type="button" name="reset" onclick="resetVal([this.parentNode.children[4],this.parentNode.children[8]])">CANCEL</button>
       </form>
     </div>
     <script type="text/javascript">
     </script>
   </body>
 </html>
