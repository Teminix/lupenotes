// initialisers
//jQuery initialiser
var globals = {
  port:8088}
var root ="http://projhost:"+globals.port+"/";

(function() {
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
    if(scripts[i].getAttribute("src") == root+"scripts/Libraries/jquery.js") {
      isjquery = true
    }
  }
  if (isjquery == false) {
    script = document.createElement("script");
    script.setAttribute("src",root+"scripts/Libraries/jquery.js");
    head.appendChild(script);
  }
}());
// Icon initialiser
(function() {
var head = document.head;
  var link = document.createElement("link");
  link.setAttribute("rel","icon") ;
  link.setAttribute("href",root+"images/icon-dark.png");
  head.appendChild(link)
  let script = document.createElement('script');
  script.src = root+"scripts/js/colors.js";
  head.appendChild(script);
  console.log("Set icon")
})();










// Utility functions
function confirmUsr(string,func,cancel=function(){}){
  let c = confirm(string);
  if(c == true){
    func()
  }
  else{
    cancel()
  }
}
function redirect(url){
  window.location.href=url
}
function collectionToArray(col){
  let array = [];
  for (let elem of col) {
    array.push(elem);
  }
  return array;
}
function merge_array(arr1,arr2){
  for(let item of arr2){
    arr1.push(item);
  }
  return arr1;
}
function resetForm(elem,defaults,event){
  event.preventDefault();
  let c = confirm("Any changes made to the form will be reset to default/original values\n\nContinue?");
  if (c == true) {
    let form = $(elem).closest('form');
    console.log(form);
    for (let key in defaults) {
      // console.log(form.find(`[name=${key}]`));
      form.find(`[name=${key}]`).val(defaults[key]);
    }
  }
  else {
    console.log('test')
  }

}
function q(sel){
  let selected = document.querySelectorAll(sel);
  if (selected.length == 1) {
    return selected[0]
  } else {
    return selected
  }
}
function MultiType(array,time=50){
  Element.prototype.computedStyle=function(){
    return getComputedStyle(this)
  }
  function type(elem,string,time=50,finish=function(){}){
  elem.innerHTML =""
  i = 0
  interval = setInterval(function(){
      elem.innerHTML += string[i];
  	if(i >= string.length-1){
  		console.log('Type completed');
      	clearInterval(interval);
        finish()
      }

  	else {
  		i++}},time)
  }
  let accept = [HTMLCollection,NodeList,Array];
  if(!accept.includes(array.constructor)){
    throw new TypeError(`Invalid Argument given: '${array.constructor.name}'. Accepts: ${accept.map((e)=>{return e.name}).toString()} `)
  }
  array.forEach(function(e){
    let index = array.indexOf(e);
    if(!(e instanceof Element)){
      throw new TypeError(
        `Invalid variable type given: '${e.constructor.name}' at index '${index}'. Accepts: Element`
      )
    }
  })
  let msg = array[0].innerHTML;
  let styleSheet = [];
  for (let item of array) {
    styleSheet.push(item.computedStyle().display);
    item.style.display = "none"
  }
  // console.log(styleSheet[0])
  array[0].style.display = styleSheet[0]
  type(array[0],msg,time,function(){
    if(array.length-1 != 0){
      array.splice(0,1);
      styleSheet.splice(0,1);
      array[0].style.display = styleSheet[0]
      // console.log('Type completed')
      MultiType(array)
    }
  })
}

function clearForm(elem,action=null){
    var form = elem.closest("form");
    console.log(form);
    var inputs = collectionToArray(form.getElementsByTagName('input'));
    var textareas = collectionToArray(form.getElementsByTagName('textarea'));
    inputs = merge_array(inputs,textareas);
    inputs.push('test');
    console.log(inputs);
    let changes = null;
    console.log('Ran clear form');
    for(let input of inputs){
      value = input.value;
      if (value == undefined) {
        value = "";
      }
      if (value.trim() != "") {
        changes = true;
        break
      }
    }
    if (changes == true) {
      if (action != null) {
        let conf = confirm('Any changes made will get destroyed\n\n Proceed?');
        if (conf == true) {
          history.go(-1)
        }
      } else {
        console.log('Changes detected');
        let conf = confirm('It appears that you have made some changes\n leaving or clearing the form would loose the data\n\n Are you sure you want to cancel/leave?')
        if (conf == true) {
            for (let input of inputs) {
              input.value = "";
            }
        }
        else {
          history.go(-1);
         }
      }

      }



}
function baseName(str)
{
   var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if(base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
   return base+"."+str.split(".").pop();
}
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
const l = function(sel){console.log(sel)}
function getAttrs(elem, attributes) { // get the attributes in a dictionary
  dict = {};
  for (var i = 0; i < attributes.length; i++) {
    attribute = attributes[i];
    dict[attribute] = elem.getAttribute(attribute);
  }
  return dict;
}
function dictEmpty (dict) { // check if a dictionary is empty
  let len = 0;
  for (var elem in dict) {
    len++
  }
    if (len==0) {
      return true;
    }
    else {
      return false
    }
}
function getQueryData(url){
  if (url.indexOf("?") != -1) {
    dict = {};
    let queryString = url.split("?")[1];
    for(let statement of queryString.split('&')){
      let key = statement.split('=')[0];
      let value = statement.split('=')[1];
      dict[key] = value;
    }
    return dict
  }
  else {
    return null
  }
}





// global imperative variables begin here
var post_dir = root+"Session/post.php"


// functions begin here





// GENERATION 1 FUNCTIONS START


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
  let divs = parent.querySelectorAll(".inputArea");
  for (let div of divs) {
    let html = div.innerHTML;
    let textarea = document.createElement('textarea');
    textarea.innerHTML = html;
    parent.replaceChild(div,textarea);
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
      if(data=="0") {
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
  confirmUsr("WARNING: \n Are you sure you want to delete?\n\n This cannot be undone.",function(){
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
})
}
function vote(elemnt,vote_type) { // the vote function that needs to be utilised
  var parent = elemnt.parentNode; // get the parent div
  var vote_element = parent.children[1]; // the actual vote counter element
  var post_id = parent.parentNode.getAttribute("post-id");
  if (vote_type == "up") { // if the vote_type argument is "up" for upvote
    if (parent.getAttribute("voted") == "0"){ // if the parent of the element has not been voted upon yet
        elemnt.children[0].setAttribute("src",'../images/upfilled.png') // change the upvote image
        elemnt.style.backgroundColor = "rgb(110, 0, 255)"; //change the upvote background colol to purple
        parent.setAttribute("voted","up")
        vote_element.innerHTML = String(Number(vote_element.innerHTML)+1)
        $.ajax({
          type:"POST",
          url:post_dir,
          data:{type:"vote",value:"1",pid:post_id},
          success:function(data){console.log(data)},
          error: function() {console.log("error in upvoting post id: "+post_id)}
        });
    }
    else if (parent.getAttribute("voted") == "up") { // if upvote has already been a vote been casted
        elemnt.children[0].setAttribute("src","../images/up.png");
        elemnt.style.backgroundColor = "rgb(0,200,200)";
        parent.setAttribute("voted","0");
        vote_element.innerHTML = String(Number(vote_element.innerHTML)-1);
        $.ajax({
          type:"POST",
          url:post_dir,
          data:{type:"vote",value:"1",pid:post_id},
          success:function(data){console.log(data)},
          error: function() {console.log("error in upvoting post id: "+post_id)}
        });
    }
    else if (parent.getAttribute("voted") == "down"){ // if downvote has already been casted
      elemnt.children[0].setAttribute("src","../images/upfilled.png");
      elemnt.style.backgroundColor = "rgb(110, 0, 255)";
      parent.children[2].children[0].setAttribute("src","../images/down.png");
      parent.children[2].style.backgroundColor="rgb(0,200,200)";
      parent.setAttribute("voted","up");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)+2);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"1",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });
    }

  }
  else if (vote_type ==  "down"){
    if (parent.getAttribute("voted") == "0") {
      elemnt.children[0].setAttribute("src","../images/downfilled.png")
      elemnt.style.backgroundColor = "red";
      parent.setAttribute("voted","down");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)-1);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"0",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });
    }
    else if (parent.getAttribute("voted") == "down") {
      elemnt.children[0].setAttribute("src","../images/down.png");
      elemnt.style.backgroundColor = "rgb(0,200,200)"
      parent.setAttribute('voted',"0");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)+1);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"0",pid:post_id},
        success:function(data){console.log(data)},
        error: function() {console.log("error in upvoting post id: "+post_id)}
      });
  }
    else if (parent.getAttribute("voted") == "up") {
      elemnt.children[0].setAttribute("src","../images/downfilled.png"); // set downvote image to filled
      elemnt.style.backgroundColor = "red"; // set the downvote background color red
      parent.children[0].children[0].setAttribute("src","../images/up.png");
      parent.children[0].style.backgroundColor = "rgb(0,200,200)";
      parent.setAttribute("voted","down");
      vote_element.innerHTML = String(Number(vote_element.innerHTML)-2);
      $.ajax({
        type:"POST",
        url:post_dir,
        data:{type:"vote",value:"0",pid:post_id},
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

// GENERATION 1 FUNCTIONS END




// GENERATION 2 FUNCTIONS START
function post_form(elem,args,trim=true,msg=null) {
  var form = elem.closest("form,.form");

  var children = form.getElementsByTagName("*"); // This is actually the descendents and not the children only
  /*for (let key in ext) {
    // dataSet[key] = ext[key];
    console.log(ext)
  }*/
  var ajaxURL = args["url"]
  var prompt = form.getElementsByClassName("p")[0]
  if (args["redirect"] == undefined) {
    args["redirect"] = window.location.href
  }
      if (args["success"] == undefined) {
        ajaxSuccess = function(data) {
          if (data == "0") {
            if (args["redirect"] == window.location.href) {
              window.location.reload(true)
            }
            else {
              console.log('trying redirect')
              window.location.href = args["redirect"]
            }
          }
          else {
            console.log('Error incoming: '+data)
            prompt.innerHTML = data
          }
        }
      }
      if (args["success"] != undefined) {
        ajaxSuccess = function(data){
          if (data == 0) {
            args["success"]()
          }
          else {
            prompt.innerHTML = data
          }
        }
      }
      if (args["error"] == undefined) {
        ajaxError = function() {
          prompt.innerHTML = "Internal Server Error"
        }
      }
      if (args["error"] != undefined) {
        ajaxError = args["error"]
      }
      if (args["ext"] == undefined) {
        dataSet = {}
      }
      if (args["ext"] != undefined) {
        var dataSet = {};
        for (var key in args["ext"]) {
          dataSet[key] = args["ext"][key]
        }
      }

      for (var i = 0; i < children.length; i++) {
        let child = children[i];
        if (child.tagName == 'INPUT' || child.tagName == "TEXTAREA") {
          // console.log(child)
          let name = child.name;
          let value = child.value;
          // console.log(`${name}: ${value}`)
          if (trim == true) {
            name = name.trim()
            value = value.trim()
          }
          dataSet[name] = value
        }

      }
      console.log(dataSet)
      // console.log(prompt)
      // console.log(prompt);
      if (msg != null) {
        prompt.innerHTML = msg
      }
      $.ajax(
        {data:dataSet,
        success:function(data) {
          ajaxSuccess(data)
        }
        ,error:function() {
          ajaxError()
        }
        ,url:ajaxURL
        ,type:"POST"}
      )

  }

function post_data(elem,args,reload=true,msg="Data Sent") {// Used to send in raw data instead of scanning a form
  if (dictEmpty(args)) {
    console.error("post_data() Requires arguments as a dictionary only") // If there are no arguments provided in the args field or argument
  } else { // otherwise
    if (dictEmpty(args["ext"])) { // if the ext field is not defined then we will throw an error
      console.error("In order to send across data, data needs to be put into the ext argument")
    } else {
      // Initialiate all the variables
      if (args["url"] == undefined) {
        console.error("URL needs to be specified")
      } else {
        var newLoc = args["url"]
        if (args["redirect"] == undefined) {
          var direct = window.location.href
        } else {
          var direct = args["redirect"]
        }
        let form = elem.closest("form,.form"); // find the nearest form element that it is contained within
        let prompt = form.getElementsByClassName("p")[0]; // get the prompt element
        let dataSet = args["ext"] // initiate dataset variable
        if (args["success"] == undefined) {
          ajaxSuccess = function (data){
            if (data == 0) {
              if (reload == true && direct != window.location.href) {
                window.location.href = direct
              }
            } else {
              prompt.innerHTML = data
            }

          }
        }
        if (args["success"] != undefined) {
          ajaxSuccess = function(data){
            if (data == 0) {
              args["success"]()
            }
          }
        }
        if (args["error"] == undefined) {
          ajaxError = function (data) {
            prompt.innerHTML = "Sorry, Internal server error";
            console.error('Internal server error');
          }
        }
        if (args["error"] != undefined) {
          ajaxError = args['error']
        }
        var request = {
          type:'POST',
          url:newLoc,
          data:dataSet,
          success:function(data){
            ajaxSuccess(data)
          },
          error:function(data){
            ajaxError(data)
          }
        };
        console.log(request)
        // console.log(prompt)
        prompt.innerHTML = msg
        $.ajax(request)

      }


    }
  }
}




// GENERATION 2 FUNCTIONS END






// commenting part
function expandTxtArea(element) {
    element.setAttribute("rows","5");
}

function post_comment(element) {
    var post_id = document.getElementsByClassName("post")[0].getAttribute("post-id")
    var post_button_parent = element.parentNode;
    var p = document.getElementsByClassName("prompt")[0];
    var node = post_button_parent.getAttribute("node");
    console.log(post_button_parent);
    console.log(node);
    var dict = {"node":node,"post_id":post_id,type:"write"};
    for (i=0;i<post_button_parent.children.length;i++) {
      if (post_button_parent.children[i].tagName == "TEXTAREA"){
      dict[post_button_parent.children[i].name] = post_button_parent.children[i].value.trim();}
    }
    //console.log(dict);
    $.ajax({
      type:"POST",
      url:"comment.php",
      data:dict,
      success:function (data) {
        if (data == "0") {
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
  var comm_id = comment.getAttribute("id");;
  //console.log(comm_id)
  if (func == "delete") {
    parent = comment.parentNode;
    //console.log(parent)
    comment.animate({transform:["scale(1)","scale(0.1)"]},500);
    setTimeout(function(){parent.removeChild(comment)},500);
    childs = comment.getElementsByClassName("comment");
    child_ids = [];
    for (var i = 0; i < childs.length; i++) {
      child_ids.push(Number(childs[i].getAttribute("id")));
    }
    //console.log(child_ids)
    $.ajax({
      type:"POST",
      url:"comment.php",
      data:{type:"delete",id:comm_id,children:child_ids},
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
    console.log(comment);
    var node_id =comment.id; // Get the core comment node or id
    //console.log(node_id);
    var post_button = constructElem("button","Post",{onclick:"post_comment(this)"}); // to get he relative comment
    var comment_form = constructElem("div","",{class:"comment-form",node:node_id}); // to create the form and the node
    comment.appendChild(comment_form);
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
function search(elem,output){
  var frame = $(elem).closest(".searchFrame");
  var searchables = frame.find("[searchable]");
  // console.log(searchables)
  for (let searchElem of searchables) {
    let value = elem.value.toLowerCase();
    let match = searchElem.textContent.toLowerCase().indexOf(value)
    if (match == -1) {
      searchElem.style.display = "none"
      // console.log("not found match")
    }
    else {
      searchElem.style.display = output;
      // console.log("found maatch")
    }
    // console.log(`${searchElem.textContent}: ${match}`)
  }
}
function toggleActiveElem(elem){ // Make more advanced later
  elem = $(elem);
  let frame = elem.closest("[toggleElem]");
  // console.log(frame)
  let currentActive = frame.find(".active");
  if (currentActive != elem) {
    currentActive.removeClass("active")
  }
  elem.addClass("active")
}








// Loaders / Globalscripts
function afterload(func){
      let load = window.onload;
      if (load == null || load==undefined|| typeof load != "function") {
        // console.log("Starting with fresh onload")
        window.onload = function() {
          func()
        }
      }
      else {
        // console.log("Starting with appended onload")
        window.onload = function(){
          load();
          func()
        }
      }
    }
function loadmore(loadmore){
  let parent = loadmore.parentElement;
  console.log([parent,loadmore])
  // console.log(loadmore)
  let type = loadmore.getAttribute('type');
  let offset = Number(loadmore.getAttribute('offset'));
  let request = {"type":type,"offset":offset};
  // console.log(request);
    $.ajax({
      type:"POST",
      url:"Session/loadmore.php",
      data:{"offset":offset,"type":type},
      success:function(response){
        // console.log(response);
        response = JSON.parse(response);
        // console.log(response);
        posts = document.getElementById("posts");
        for(let post of response.posts){
          // place response here
          posts.innerHTML += `<div class='post' post-id='${post.id}'>
          <span class='info-wrapper' onclick='window.location.href="../users/${post.post_usr}.php"'>
            <img src='../dps/${post.user_img}' alt=''>
            <span>${post.user_disp}</span><br>
            <span class='usr'>${post.post_usr}</span>
          </span><br>
            <textarea readonly name='title' class='title' rows='1'>${post.title}</textarea><br><br>
            <textarea class='postContent' name='content' readonly>${post.content}</textarea>
            <span class='p'></span>
            ${post.edit}
            <div class='vote-section' voted='${post.vote_type}'>
              <button class='upvote' onclick='vote(this,"up")'>
                <img src='../images/up.png' class='button'>
              </button>
              <p class='score'>${post.rep}</p>
              <button class='downvote' onclick='vote(this,"down")'>
                <img src='../images/down.png' class='button' >
              </button>
            </div><br>
            <button class='right'><a href='http://projhost:8088/Session/view-post.php?id=${post.id}'>View post</a></button>
          </div>`
        }
        if (response.loadmore == true) {
          loadmore = parent.getElementsByClassName('loadmore')[0];
          offset = loadmore.getAttribute('offset');
          loadmore.setAttribute('offset',offset+5)
          parent.removeChild(loadmore);
          parent.appendChild(loadmore)
        }
        else {
          parent.removeChild(loadmore);
        }
      }
    });




}
function GlobalScript(scripts){
	var location = baseName(window.location.href);
  if (scripts["*"] != undefined){
    scripts["*"]()
  }
	if (scripts[location] != undefined) {
	   afterload(function(){
       scripts[location]()
     })
	}
  console.log("%cGlobalscripts loaded","color:green;font-weight:bold")
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
console.log("%c Main.js Loaded","font-weight:bold;color:lightgreen")
