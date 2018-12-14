const Modal = {
  show:function(modalName) { // To show the modal
    try {
      let modal = getByAttr("modal",modalName)[0];
      modal.style.display = "block";
      modal.setAttribute("shown","1")
      // console.log(modal)
    }
    catch(e) {
      console.error("Need to link to script \"main.js\"")
    }


  },
  hide:function(modalName) { // To hide the modal
    try{
      let modal = getByAttr("modal",modalName)[0];
      modal.style.display = "none";
      modal.setAttribute("shown","0")
      // console.log(modal)
    }
    catch(e) {
      console.error("Need to link to script \"main.js\"")
    }
  },
  getModal:function (target=null){ // function for getting all the modals or a specific one
    if (target==null) { // If the targer by default is not defined, thus it would return all the different modals in a list
      let all = document.getElementsByTagName("*");
      let list = {}
      for (var i = 0; i < all.length; i++) {
        let elem = all[i];
        if (elem.hasAttribute("modal")) {
          list[elem.getAttribute("modal")] = elem
        }
      }
      return list;
    } else { // In case the target is defined
      let all = document.getElementsByTagName("*")
      for (var i = 0; i < all.length; i++) {
        var elem = all[i]
        if (elem.getAttribute("modal") == target) {
          shown = elem.getAttribute("shown");
          toggle = function() {
            let element = this.elem;
            let allelements = Modal.getModal();
            for (let current in allelements) { // used to toggle all other active modals
              current = allelements[current]
              if (current.getAttribute("shown") == 1) {
                current.setAttribute("shown","1");
                current.style.display = "none"
              }
            }
            if (element.getAttribute("shown") == 0) {
              element.setAttribute("shown","1");
              element.style.display = "block"
            }
            else if (element.getAttribute("shown") == 1) {
              element.setAttribute("shown","0");
              element.style.display = "none"
            }
          }
          return {"elem":elem,"shown":shown,"toggle":toggle}
        }
      }
      return null
    }
  },
  initiate:function() { // To initiate the Modals
      var modals = this.getModal();
      document.body.onkeyup = function(e) {
        var modal = Modal.getActiveModal();
        if (modal != null) {
          if (e.keyCode == 27) {
              Modal.hide(modal.getAttribute("modal"))
          }

        }
      }
      for(let modal in modals) {
        modal = modals[modal];
        modal.setAttribute("shown","0");

      }
      console.log("%c Modals Initiated","font-weight:bold;color:lightblue")
    }
  ,
  toggle:function(name) {
      modal = this.getModal();
      modal = modal[name]
      if (modal == undefined) {
        console.error("Undefined Modal: "+"'"+name+"'")
      }
      else{
        modal = Modal.getModal();
        for (let mod in modal) { // for toggling off all active modals
          mod = modal[mod]
          if (mod.getAttribute("shown") == "1") {
            mod.setAttribute("shown","0")
            mod.style.display = "none"
          }
        }
        modal = modal[name];
        if (modal.getAttribute("shown") == "0") {
          modal.setAttribute("shown","1")
          modal.style.display = "block"
        }
        else if (modal.getAttribute("shown") == "1") {
          modal.setAttribute("shown","0")
          modal.style.display = "none"
        }
      }
    },
    getActiveModal:function() {
      var modals = Modal.getModal();
      for (let modal in modals) {
        modal = modals[modal];
        if (modal.getAttribute("shown") == "1") {
          return modal
        }
      }
      return null
    }
  }
console.log("%c Modals Loaded","font-weight:bold;color:lightgreen")
