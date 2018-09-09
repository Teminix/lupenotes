var Modal = {
  testio:"none",
  show:function(modalName) {
    try {
      let modal = getByAttr("modal",modalName)[0];
      // modal.style.display = "block";
      console.log(modal)
    }
    catch(e) {
      console.error("Need to link to script \"main.js\"")
    }


  }
}
