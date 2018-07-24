
var head = document.head;
var scripts = [];
for (i=0;i<head.children.length;i++) {
  if (head.children[i].tagName == "SCRIPT") {
    scripts.push(head.children[i]);
  }
}
//console.log(scripts);
var isjquery = false;
for (var i = 0; i < scripts.length; i++) {
  if(scripts[i].getAttribute("src") == "http://projhost:8088/scripts/Libraries/jquery.js") {
    isjquery = true
  }
}
if (isjquery == false) {
  script = document.createElement("script");
  script.setAttribute("src","http://projhost:8088/scripts/Libraries/jquery.js");
  head.appendChild(script);
}

// Utility functions
function keyDown(event, keycode, func) {
  if (event.keyCode == keycode){func};
};
function delElement(element,time) {
  var parent = element.parentNode;
  element.animate({
    transform:["scale(1)","scale(0.9)"]
  },time);
   parent.removeChild(element);
}
function getByAttr(attr,value){
  elems = document.getElementsByTagName("*");
  list = [];
  for(i=0;i<elems.length;i++) {
    if(elems[i].getAttribute(attr) == value) {
      list.push(elems[i])
    }
  }
  return list;
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
function appendInOrder(array,parent) {
  for (var i = 0; i < array.length; i++) {
    parent.appendChild(array[i])
    console.log(array[i])
  }
}







// global imperative variables begin here
var post_dir = "http://projhost:8088/Session/post.php"


// functions begin here




function changePass() { // Create a function to change the pasword or post the new password
    var old_pass = document.getElementsByName("password")[0].value; // old password
    var new_pass = document.getElementsByName("password")[1].value; // new password
    var ver_pass = document.getElementsByName("password")[2].value;
    var prompts = document.getElementsByClassName("pr");
    $.ajax ({ // ajax call the information
      type:"POST",
      url:"changes.php",
      data: {op:old_pass,np:new_pass,vp:ver_pass,type:"password"}, // The type of the call is password
      success: function (data) { // on success
        if (data == "1") { // if the data was valid and the password met conditions:
          modal[1].style.display = "none"; // close modal
          window.location.reload(true); // reload window
        }
        else {
          prompts[1].innerHTML = data; // otherwise inform the userof the error
        }
      },
      error: function () {
        prompts[1].innerHTML = "Some error occured in trying to change your password"; // An error message to be displayed
      }
    });

}
function changeUsr() { //function to change the username or display name
  //Get the data:
    var user = document.getElementById("usr").value;
    var displayName = document.getElementById("display").value;
    var pass = document.getElementById("pass").value;
    $.ajax({ // Make an ajax call with the data
      type:"POST",
      url:"changes.php",
      data: { usr:user,display:displayName,pwd:pass,type:"username" },
      success: function (data) {
        if (data == "1") {
          modal[0].style.display="none";
          window.location.reload(true);
        }
        else {
          document.getElementsByClassName('pr')[0].innerHTML = data;
        }
      },
      error: function() {
        document.getElementsByClassName('pr')[0].innerHTML = "Some error occured in the server";
      }
    });

}
function resetVal(array) { // define the elements to have their value reset
  for (i=0;i<array.length;i++) {
    array[i].value = "";
  }
}
function post(path,element) { // create a posting function for convenience
  var form = element.parentNode;
  var dict = {type:"createPost"};
  for (i=0;i<form.children.length;i++) {
    if (form.children[i].tagName == "TEXTAREA" || form.children[i] =="INPUT") {
      dict[form.children[i].name] = form.children[i].value.trim(); // add a key to the dictionary which is the child's name and the dict value as the child's input value
    }
    else if (form.children[i].getAttribute("class") == "p") {
      var prompt = form.children[i];
    }
  }//console.log(dict)
  $.ajax({ // Post the data in an ajax call
    type: "POST",
    url: path,
    data: dict, // the data is  the dictionary we made of the data that needs to be posted
    success: function (data) {
      if (data == "1") {
        console.log("successfully posted");
        window.location.reload(true)
      }
      else {
        prompt.innerHTML = data;
      }
    },
    error: function() {
      console.log("Some error has ocurred for some enigmatic reason lol"); // Due to any error, log An error
    }
  });
}
function makeEdit(elem) { // Make changes to the post
  var parent = elem.parentNode; // Grab the parent Node of the target element
  for (i=0;i<parent.children.length;i++) {
    if (parent.children[i].tagName == "TEXTAREA") {
      elem.parentNode.children[i].removeAttribute("readonly");
    }
  }
  elem.innerHTML = "Save"; // Change the innerHTML to 'Save'
  elem.setAttribute("onclick","makeRead(this)"); // Add a new onclick event
}
function makeRead(elem) { // Save changes for the post
  var parent = elem.parentNode;
  var post_id = parent.getAttribute("post-id");
  var dict = {type:"editPost",pid:post_id};
  for (i=0;i<parent.children.length;i++) {
    if (parent.children[i].tagName == "TEXTAREA") {
      dict[parent.children[i].name] = parent.children[i].value;
      parent.children[i].setAttribute("readonly","readonly");
    }
    else if (parent.children[i].getAttribute("class") == "p") {
      var prompt = parent.children[i];
    }
  }
  //console.log(dict);
  elem.innerHTML = "Edit";
  elem.setAttribute("onclick","makeEdit(this)");
  $.ajax({
    type:"POST",
    url:post_dir,
    data:dict,
    success: function (data){
      if(data=="1") {
        console.log("Editing post sucessful of id: "+post_id);
      }
      else {
      prompt.innerHTML = data}
    },
    error: function() {
      prompt.innerHTML = "Some error occured during diting you post";
    }
  });
}
function deletePost(elem) { // to delete posts
  var parent = elem.parentNode;
  var id = parent.getAttribute("post-id");
  parent.parentNode.removeChild(parent);
  $.ajax({
    type:"POST",
    url:post_dir,
    data: {type:"delPost",pid:id},
    success: function () {
      console.log("deleted post successfully with id: "+id);
    },
    error: function () {
      console.log("Some error occured in the process of deleting post id: "+id)
    }
  });
}
function vote(elemnt,vote_type) { // the vote function that needs to be utilised
  var parent = elemnt.parentNode; // get the parent div
  var vote_element = parent.children[1]; // the actual vote counter element
  var post_id = parent.parentNode.getAttribute("post-id");
  if (vote_type == "up") { // if the vote_type argument is "up" for upvote
    if (parent.getAttribute("voted") == "0"){ // if the parent of the element has not been voted upon yet
        elemnt.children[0].setAttribute("src",'../images/upfilled.png') // change the upvote image
        elemnt.style = "background-color:rgb(110, 0, 255)"; //change the upvote background colol to purple
        parent.setAttribute("voted","up")
        vote_element.innerHTML = String(Number(vote_element.innerHTML)+1)
        $.ajax({
          type:"POST",
          url:post_dir,
          data:{type:"vote",value:"upvote",pid:post_id},
          success:function(data){console.log(data)},
          error: function() {console.log("error in upvoting post id: "+post_id)}
        });
    }
    else if (parent.getAttribute("voted") == "up") { // if upvote has already been a vote been casted
        elemnt.children[0].setAttribute("src","../images/up.png");
        elemnt.setAttribute("style","background-color:rgb(45,45,45)");
        parent.setAttribute("voted","0");
        vote_element.innerHTML = String(Number(vote_element.innerHTML)-1);
        $.ajax({
          type:"POST",
          url:post_dir,
          data:{type:"vote",value:"neutralise",direction:"down",pid:post_id},
          success:function(data){console.log(data)},
          error: function() {console.log("error in upvoting post id: "+post_id)}
        });
    }
    else if (parent.getAttribute("voted") == "down"){ // if downvote has already been casted
      elemnt.children[0].setAttribute("src","../images/upfilled.png");
      elemnt.style = "background-color:rgb(110, 0, 255)";
      parent.children[2].children[0].setAttribute("src","../images/down.png");
      parent.children[2].style="background-color:black";
      parent.setAttribute("voted","up");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)+2);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"jump",direction:"up",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });
    }

  }
  else if (vote_type ==  "down"){
    if (parent.getAttribute("voted") == "0") {
      elemnt.children[0].setAttribute("src","../images/downfilled.png")
      elemnt.style = "background-color:red";
      parent.setAttribute("voted","down");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)-1);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"downvote",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });
    }
    else if (parent.getAttribute("voted") == "down") {
      elemnt.children[0].setAttribute("src","../images/down.png");
      elemnt.style = "background-color: rgb(45,45,45)";
      parent.setAttribute('voted',"0");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)+1);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"neutralise",direction:"up",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });
  }
    else if (parent.getAttribute("voted") == "up") {
      elemnt.children[0].setAttribute("src","../images/downfilled.png"); // set downvote image to filled
      elemnt.style = "background-color:red"; // set the downvote background color red
      parent.children[0].children[0].setAttribute("src","../images/up.png");
      parent.children[0].style = "background-color:rgb(45,45,45)";
      parent.setAttribute("voted","down");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)-2);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"jump",direction:"down",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });

        }
    }

}

function init_vote() {
  var divs = document.getElementsByClassName('vote-section');
  for (var i = 0; i < divs.length; i++) {
    var button1 = divs[i].children[0];
    var img = button1.children[0];
    var button2 = divs[i].children[2];
    var img2 = button2.children[0];
    if (divs[i].getAttribute("voted") == "up") {
      button1.setAttribute("style","background-color:#6e00ff")
      img.setAttribute("src","../images/upfilled.png");

    }
    else if (divs[i].getAttribute("voted") == "down") {
      button2.setAttribute("style","background-color:#ff0000")
      img2.setAttribute("src","../images/downfilled.png");

    }
  }
}




// commenting part
function expandTxtArea(element) {
    element.setAttribute("rows","5");
}

function post_comment(element) {
    var post_id = document.getElementsByClassName("post")[0].getAttribute("post-id")
    var parent = element.parentNode;
    var p = document.getElementsByClassName("prompt")[0];
    var node = parent.getAttribute("node");
    var dict = {"node":node,"post_id":post_id,type:"write"};
    for (i=0;i<parent.children.length;i++) {
      if (parent.children[i].tagName == "TEXTAREA"){
      dict[parent.children[i].name] = parent.children[i].value.trim();}
    }
    //console.log(dict);
    $.ajax({
      type:"POST",
      url:"comment.php",
      data:dict,
      success:function (data) {
        if (data == "1") {
          window.location.reload(true);
        }
        else {
          p.innerHTML = data;
        }
      },
      error:function () {
        p.innerHTML = "Some error has occured";
      }
    })
}
function sort_comment(elem) {
  var children = elem.children;
  for(i=0;i<children.length;i++) {
    child = children[i];
    node = child.getAttribute("node");
    if (!(node == "main")) {
      var parent = document.getElementById(node);
      parent.appendChild(child);
      i -= 1;
    }
  }
}
function comm_func(button,func) { // function for the comment buttons
  comment = button.parentElement.parentElement; // get the comments parent element
  //console.log(comment)
  core_comment = button.parentElement;
  var comm_id = comment.getAttribute("id");;
  //console.log(comm_id)
  if (func == "delete") {
    parent = comment.parentNode;
    //console.log(parent)
    comment.animate({transform:["scale(1)","scale(0.1)"]},500);
    setTimeout(function(){parent.removeChild(comment)},500);
    $.ajax({
      type:"POST",
      url:"comment.php",
      data:{type:"delete",id:comm_id},
      success:function(data) {
        console.log(data)
      },
      error:function() {
        console.log("Some error occured in the deletion process of deleting comment with id: "+comm_id)
      }
    })
  }
  else if (func == 'reply') { // if the command is a reply
    var textarea = constructElem("textarea","",{style:"width:100%;font-size:15px",rows:"4",comment:"comment",placeholder:"Reply to commment",name:"comment-content"});
    var cancel_butt = constructElem("button","Cancel",{onclick:"delElement(this.parentNode,0)"});
    var br = constructElem("br","",{});
    var span = constructElem("span","",{class:"prompt"});
    br1 = constructElem("br","",{});
    var post_button = constructElem("button","Post",{onclick:"post_comment(this)"}); // to get he relative comment
    var comment_form = constructElem("div","",{class:"comment-form",node:core_comment.id}); // to create the form and the node
    core_comment.appendChild(comment_form);
    appendInOrder([textarea,br,span,br1,post_button,cancel_butt],comment_form);
  }
  else if (func =='edit') {
    var breaktag = constructElem('br','',{});
    if (button.getAttribute("mode") == "edit") { // if the button is ready to edit
      var cont = comment.getElementsByClassName("content-wrapper")[0]; // get the content element
      textarea = constructElem("textarea",cont.innerHTML.trim(),{style:"font-size:15px;",class:"content-wrapper"});
      prompt = constructElem("span","",{class:"p"})
      button.innerText = "Save";
      button.setAttribute("mode","save")//toggle the mode
      cont.parentNode.replaceChild(textarea,cont);
      insertAfter(prompt,textarea)
      insertAfter(breaktag,textarea);
    }
    else if (button.getAttribute("mode") =="save") {// if the button is ready to save
      var textarea = comment.getElementsByClassName("content-wrapper")[0]; // get the textarea element
      var prompt = comment.getElementsByClassName('p')[0];
      var textdata =textarea.value.trim() //the data of the textareaa or the innerHTML/value
      var dict = {type:"edit",id:comm_id,content:textdata};
      cont = constructElem("span",textdata,{style:"font-size:15px;",class:"content-wrapper"});// construct the content element
      $.ajax({
        type:"POST",
        url:'comment.php',
        data:dict,
        success: function (data) {
          if (data == "0") {
            button.innerText = "Edit";
            button.setAttribute("mode","save");//toggle the mode
            textarea.parentNode.removeChild(prompt)
            textarea.parentNode.removeChild(comment.getElementsByTagName("br")[2])
            textarea.parentNode.replaceChild(cont,textarea);
          }
          else {
            prompt.innerHTML = data
          }
        },
        error: function () {
          console.log("Some error has occured in editing comment with id: "+comm_id)
        }
      })

    }
  }

}






























//construction functions

function constructElem(name,html,data){
  elem = document.createElement(name);
  for (var key in data) {
    elem.setAttribute(key,data[key])
  };
  elem.innerHTML = html;
  return elem;
}
