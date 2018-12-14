// In order for this script to work, main.js must be imported before the script call of this source
function register(){
  $("#submit").on("click",function(){
    post_form(this,{url:"process.php",ext:{type:"register"}});
  })
}
GlobalScript({
  "*":function(){},
  "verify-window.php":function(){
    $("#verify_button").on("click",function(){
      post_form(this,{url:'verifier.php',ext:{type:'code'},redirect:'session.php'})
      // console.log(this)
    });
  },
  "verify-window.php?type=change":function(){
    // console.log("test")
    $("#send_change_code").on("click",function(){
      post_data(this,{url:'verifier.php',ext:{type:'changeEmail',level:1},success:function(){snype.nextState()}},false,"Sending, please wait")
      // console.log("lol")
    });
    $("#change_resend_confirmation_code").on("click",function(){
      post_data(this,{url:'verifier.php',ext:{type:'changeEmail',level:1}},false, "Sending please wait")
    });
    $("#change_confirmation_verify").on("click",function(){ // Check if the confirmation code is correct
      post_form(this,{url:'verifier.php',ext:{type:'changeEmail',level:2},success:function(){snype.nextState()}},false)
    });
    $("#change_resend_code").on("click",function(){
      post_form(this,{ext:{'type':'send'},url:'verifier.php'},false,'Sending please wait')
    })

    $("#change_verify").on("click",function(){
      post_form(this,{url:'verifier.php',ext:{type:'code'},redirect:'session.php'})
    })
    $(".next").on("click",function(){
      snype.nextState()
    })
    $(".previous").on("click",function(){
      snype.prevState()
    })
  },
  "login.php":function(){
    $("#submit").on("click",function(){
      post_form(this,{url:"process.php",ext:{type:"login"},redirect:"Session/session.php"})
    })
  }
})
