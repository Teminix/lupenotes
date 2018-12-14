<?php
session_start();
  if (!isset($_SESSION["usr"])) {
    header('location:../main.php');
  }
  $conn = new mysqli("localhost","root","root","project");
  $usr = $_SESSION['usr'];
  $res = $conn->query("SELECT image,email_v FROM users WHERE usr='$usr'");
  $row = $res->fetch_assoc();
  $image = $row["image"];
  if($row["email_v"] == 0){
    header('location:session.php');
  }
  $condition = isset($_GET['editPost']);
  if ($condition) {
    $id = $_GET['id'];
    $usr = $_SESSION['usr'];
    $res = $conn->query("SELECT * FROM posts WHERE ID=$id AND usr='$usr'");
    if ($res->num_rows == 1) {
      $row = $res->fetch_assoc();
      $title = $row['title'];
      $content = $row['content'];
      $top = 'Edit your post:';
      /*

      $resp = "<span class='title'>Edit your post:</span><br>
      <textarea name='title' class='title' rows='1' placeholder='Title of Post'>$title</textarea><br><br>
      <textarea name='content' rows='8' cols='80' placeholder='Content of the post..' style='resize:vertical'>$content</textarea><br>
      <span class='p' style='font-size:13px'></span>
      <button type='button' name='editPost' id='post' onclick='post_form(this,{ext:{type:\"editPost\",pid:\"$id\"},url:\"post.php\",redirect:\"http://projhost:8088/Session/view_post.php?id=$id\"})'>SAVE</button>
      <button onclick='resetForm(this,defaults,event)'>Reset Changes</button>
      <button type='button' name='reset' onclick='clearForm(this,\"\")'>CANCEL</button>";*/
    } elseif($res->num_rows == 0) {
      $resp = '<h1>Invalid Post</h1>';
    }

  }
  else {
    if (isset($_GET['target'])) {
      $title = "";
      $target = $_GET['target'];
      $content = '';
      $top = "Write a note to $target: ";

    } else {
      $title = "";
      $content = '';
      $target = '';
      $top = 'Write a note: ';
    }


     /*$resp = '<span class="title">Write a post:</span><br>
    <textarea name="title" class="title" rows="1" placeholder="Title of Post"></textarea><br><br>
    <textarea name="content" rows="8" cols="80" placeholder="Content of the post.." style="resize:vertical"></textarea><br>
    <span class="p" style="font-size:13px"></span>
    <button type="button" name="submitPost" id="post" onclick="post_form(this,{ext:{type:\'createPost\'},url:\'post.php\',redirect:\'http://projhost:8088/Session/session.php\'})">POST</button>
    <button type="button" name="reset" onclick="clearForm(this)">CANCEL</button>';*/
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
     <script type="text/javascript">
     <?php
     if($condition){
       echo "var defaults = {
         title:'$title',
         content:'$content'
       }";
     };
      ?>
     </script>
     <link rel="stylesheet" href="styles.css">
     <style media="screen">
     h1 {
       margin-left:40px;
     }
       .form {
         padding:20px
       }

.input, .richtextcontainer {
  height:80%
}
#main {
  background-color:lightgray;
  color:black;
  padding:20px;
  border:0;
  height:90%;
  width:90%;
  resize: vertical;
}
.set {
  border-left:4px black solid;
  padding: 10px 15px;
  display:inline-block
} .richtextcontainer
img {
  padding:0;
  margin:0;
} .richtextcontainer
button img {
  height:16px;
  width:16px
} .richtextcontainer
button {
  border:0;
  border-radius:0;
  font-size:20px;
  border-radius:6px;
  min-width:25px;
  width:auto;
  cursor:pointer;
  transition:0.5s
} .richtextcontainer
button:focus {
  outline:0;
} .richtextcontainer
button:hover {
  background-color:yellow;
} .richtextcontainer
.active {
    background-color:rgba(200,200,200,0.75)
} .richtextcontainer
input {
	width:25px;
	border:0;
	transition:0.5s;
	border-bottom:3px white solid;
	border-radius:0px;
} .richtextcontainer
input:focus {
outline:0;
	border-bottom:3px blue solid
}

     </style>
     <script type="text/javascript">
       function getFramecontent(frame){
         return frame.contentWindow.document.body.innerHTML
       }
       function postData(){
         let content = {"type":"createPost","title":$('#title')[0].value,"content":getFramecontent($('#main')[0])<?php if($target != ''){echo ',"target":"'.$target.'"';} ?>};
         console.log(content)
         let p= $('.p')[0]
         $.ajax({
           type:"POST",
           url:"post.php",
           data:content,
           success:function(data){
             if(data == "0"){
               // l("successful")
               window.onbeforeunload = function(){};
               window.unload = function(){};
               redirect('session.php')
             }
             else {
               p.innerText = data
             }
           },
           error:function(){
             p.innerHTML = "Internal Server Error"
           }
         })
       }
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
       <div class="nav-item right">
         <a href="session.php" class="image">
           <img src="../dps/<?php echo $image; ?>">
         </a>
       </div>
     </div>
     <div class="post"> <!-- POST FORM/DIV -->
       <h1><?php echo $top; ?></h1>
       <div class="form">
         <textarea name='title' class='title' rows='1' id="title" placeholder='Title of Post' onkeydown="if(event.keyCode == 9){frame.body.focus()};console.log('test')"><?php echo $title; ?></textarea><br><br>
         <div class="richtextcontainer">
      <div class="buttonset">
        <div class="set">
        <button id="bold"><b>B</b></button>
        <button id="italic"><i>I</i></button>
        <button id="underline"><u>U</u></button>
        </div>
        <div class="set" toggle>
          <button id="left-align">
            <img src="../images/left-align.svg" alt="">
          </button>
          <button id="center-align">
            <img src="../images/center-align.svg" alt="">
          </button>
          <button id="right-align">
            <img src="../images/right-align.svg" alt="">
          </button>
          <button id="center-justify-align">
            <img src="../images/center-justify.svg" alt="">
          </button>
        </div>
				<div class="set">
					<button id="ul">
						<img src="../images/bulleted-list.png" alt="">
					</button>
					<button id="ol">
						<img src="../images/ordered_list.png" alt="">
					</button>
				</div>
      </div>
      <div class="input">
        <iframe frameborder="0" id="main" srcdoc="<?php echo $content; ?>"></iframe>
      </div>
    </div>
         <span class='p' style='font-size:13px'></span>
         <!-- <button type='button' name='editPost' id='post' onclick='post_form(this,{ext:{type:\"editPost\",pid:\"$id\"},url:\"post.php\",redirect:\"http://projhost:8088/Session/view_post.php?id=$id\"})'>SAVE</button> -->
         <button type="button" name="button" onclick='postData()'>SAVE</button>
         <button onclick='resetForm(this,defaults,event)'>Reset Changes</button>
         <button type='button' name='reset' onclick='redirect("session.php")'>CANCEL</button>
       </div>
     </div>
     <script type="text/javascript">
     document.getElementsByName('title')[0].focus();
     </script>
     <script type="text/javascript">
     HTMLElement.prototype.computedStyle = function(){
     	return getComputedStyle(this)
     }
     HTMLElement.prototype.removeClass = function(e){
     	this.classList.remove(e);
     }
     HTMLElement.prototype.addClass = function(e){
     	this.classList.add(e);
     }
     HTMLElement.prototype.hasClass = function(e){
     	return this.classList.contains(e);
     }
     HTMLElement.prototype.toggleClass = function(e){
     	if(this.hasClass(e)){
     		this.removeClass(e)
     	}
     	else {
     		this.addClass(e)
     	}
     }
     let frame = $('#main')[0].contentWindow;
     frame.HTMLElement.prototype.computedStyle = HTMLElement.prototype.computedStyle

     frame = frame.document;

     frame.designMode = 'on'

     $('#bold').on('click',function(){
     	frame.execCommand('bold');
     	frame.body.focus();
     })
     $('#italic').on('click',function(){
     	frame.execCommand('italic');
     	frame.body.focus();
     })
     $('#underline').on('click',function(){
     	frame.execCommand('underline');
     	frame.body.focus();
     })
     $('#left-align').on('click',function(){
     	frame.execCommand('justifyLeft');
     	frame.body.focus();
     })
     $('#center-align').on('click',function(){
     	frame.execCommand('justifyCenter');
     	frame.body.focus();
     })
     $('#right-align').on('click',function(){
     	frame.execCommand('justifyRight');
     	frame.body.focus();
     })
     $('#center-justify-align').on('click',function(){
     	frame.execCommand('justifyFull');
     	frame.body.focus();
     })
     frame.onkeydown = function(e){

     }
     frame.onkeydown = function(e){


     	 let elem = frame.getSelection().getRangeAt(0).startContainer.parentNode
     	 if(e.keyCode == 17){
     		l(frame.body.innerHTML);
     		l(elem)
     	 }
     	if(e.keyCode == 9){
     		if(e.shiftKey){
          e.preventDefault();
     				frame.execCommand('outdent')
     		}
     		else{
     			if(elem.tagName == "UL" || elem.tagName == "OL") {
     /* 				elem.parentNode.removeChild(elem); */
            e.preventDefault();
     				frame.execCommand('indent')

     			}
     		}
     		// setTimeout(function(){
     		// let child = elem.querySelector('span[style="white-space:pre"]');
     		// child.parentNode.removeChild(child)
     		// },1)

     	}



     }
     $('#ul').on('click',function(){
     	frame.execCommand('insertUnorderedList');
     })
     $('#ol').on('click',function(){
     	frame.execCommand('insertOrderedList');
     })
     /* l($('.set:not([toggle]) button')) */
     /* console.log($('.set:not([toggle])')) */
     window.onbeforeunload = function(){return ''}
     window.unload = window.onbeforeunload
     </script>
   </body>
 </html>
