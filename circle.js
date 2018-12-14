function afterload(func){
      let load = window.onload;
      if (load == null || load==undefined|| typeof load != "function") {
        // console.log("Starting with fresh onload")
        window.onload =function() {
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
const MO = window['MutationObserver'];
MO.prototype.multiObserve = function(list,config){
for(let elem of list){
    this.observe(elem,config);}
  }
  document.head.innerHTML += `
  <style media="screen">
    :root {
      --radius:100px
    }
    .circle {
      text-align:center;
      vertical-align: middle;
      padding:0;
      background-color:#9054c4;
      box-shadow:10px 10px 10px gray;
      display:inline-block;
      border:white solid 5px;
      font-family:arial;
      color:white;
      transition:0.4s;
      overflow:hidden;
      margin:10px;
      cursor:pointer;
      background-size:cover;
    }
    .circle-wrapper {
      display:inline-block;
      position: relative;
      vertical-align: middle;
      overflow:hidden;
      line-height: normal;
    }
    .circle .title,.circle .description {
      transition:0.4s;
    }
    .circle .description {
      margin-top:10px;
      margin-bottom:10px;
      display:none;
      transition:0.4s
    }

    .circle:hover .title {
      font-size:50px;
      background-color:black;
      border-radius:10px;
      padding:10px;
    }
    .circle:hover .description {
      font-size:20px;
      background-color:black;
      display:inline-block;
      padding:5px;
      border-radius:5px;
    }
    .circle-wrapper:hover .title,.circle-wrapper:hover .description {
      transform:scale(0.9)
    }
    body {
      overflow-x: visible;
      min-width: 100%;
      width:auto;
    }
    </style>`
afterload(function(){
  var circles = $(".circle");
  for (var i = 0; i < circles.length; i++) { // circle initialiser
    let circle = circles.eq(i);
    if (circle[0].hasAttribute("bg")) {
      let bg = circle.attr("bg");
      circle.css({"background-image":`url(${bg})`})
    }
    let radius = circle.attr("baseRadius").replace("px","");
    circle.on("mouseover",function(){
      circle.attr("radius",circle.attr("hoverRadius"))
    })
    circle.on("mouseout",function(){
      circle.attr("radius",circle.attr("baseRadius"))
    })
    circle.attr("radius",radius)
    circle.css({
      "height":2*radius,
      "max-height":2*radius,
      "max-width":2*radius,
      "width":2*radius,
      "border-radius":5*radius+"px",
      "line-height":2*radius+"px"
    });
  }

  // Adding a hover effect when the circle wrapper gets hovered upon
  // $(".circle-wrapper").on("mouseout",function(){
  //   $(this).parent().css({"transform":"scale(1)"})})
  // $(".circle-wrapper").on("mouseover",function(){
  //     $(this).parent().css({"transform":"scale(1.7)"})})






  var config = {attributes:true}
  var circleUpdater = new MO(function(mutations){
    for(let mutation of mutations){
      if(mutation.type == "attributes"){
        if (mutation.attributeName == "radius") {
          let target = mutation.target;
          let radius = target.getAttribute("radius").replace("px","");
          $(target).css({
            "height":2*radius,
            "max-height":2*radius,
            "max-width":2*radius,
            "width":2*radius,
            "border-radius":5*radius+"px",
            "line-height":2*radius+"px"
          })
        }
      }
    }
  })
  circleUpdater.multiObserve(circles,config)
})
