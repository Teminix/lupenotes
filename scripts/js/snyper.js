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
function type(obj){
	let type = Object.prototype.toString.call(obj)
  type = type.replace("[object ","")
	type = type.replace("]","")
  return type}
function getByAttr(name,value=undefined,parent=undefined){
	if (parent==undefined) { // If no parent is given by default
		var elems = document.getElementsByTagName("*")
		console.log("parent = all")
	} else { // otherwise, use this parent
		var elems = parent.getElementsByTagName("*")
	}
	let list; // declare list variable
	if (value==undefined || value == "*") {
		list={} // If there  is no fixed value expected, return all the possible values and respective elements of those values
		for (elem of elems) { // Go through the elements as elem being the curent one
			if (elem.hasAttribute(name)) {
				// console.log(elem)
				elemValue = elem.getAttribute(name);
				if (list[elemValue] == undefined) { // in case there is no key value pair of the sort, create one as an array
					list[elemValue] = [elem]
				}
				else {
					list[elemValue].push(elem) // But if there already is, then just append the element to the array
				}
			}
		}
	}
	else {
		list = [] // if the value is defined, then Put all the elements in a list with that name value pair
		for (elem of elems) {
			if (elem.getAttribute("name") == value) {
				// console.log(elem)
				list.push(elem)
			}
		}
	}
	return list

}





// Snyper class begins here
class Snyper {
  constructor(name){
    let all_elem = document.getElementsByTagName("*");
    for (var i = 0; i < all_elem.length; i++) {
      let elem = all_elem[i];
      if (elem.hasAttribute("s-frame") && elem.hasAttribute("s-parent")) {
        if (elem.getAttribute("s-frame") == name) {
          this.name = elem.getAttribute("s-frame");
          this.type = "parent";
          this.elem =   elem;
					this.default = elem.getAttribute("s-default")
        }
        // return {name:this.name,type:this.type}
      }
    }
  }


  getChildren() {
    let elems = this.elem.getElementsByTagName("*")
    let list = {}
    for (var i = 0; i < elems.length; i++) {
      let elem = elems[i];
      if (elem.hasAttribute("s-child")) {
        let name = elem.getAttribute("s-frame") // Get the name of the child frame
        if (list[name] == undefined) {
        	list[name] = [elem]
        }
				else {
					list[name].push(elem)
				}

      }
    }
    return list
  }
	listChildren(){
		let children = this.getChildren();
		let list = []
		for (var child in children) {
			list.push(child)
		}
		return list
	}
	get indexMap() {
		let elem = this.elem;
		let attr = "s-indexing"
		if (!elem.hasAttribute(attr)) {
			return null
		}
		else {
			if (elem.getAttribute(attr) == "auto") {
				return this.listChildren()
			}
			else if (elem.getAttribute(attr) == "manual") {
				// return "manual"
				let indexes = getByAttr("s-index","*",elem)
				let orderedIndexes = []
				for (var index in indexes) {
					orderedIndexes.push(indexes[index][0].getAttribute("s-frame"))
				}
				orderedIndexes.unshift(this.default)
				return orderedIndexes

			}
			else {
				return null
			}
		}
	}
  static initiate(){
    // console.log("test")
    let head = document.head;
    let style = document.createElement("style");
    style.innerHTML = `
    [s-child]{
      display:none
    }
    `
    head.appendChild(style);
    afterload(function(){
      let elems = document.getElementsByTagName("*");
      for (var i = 0; i < elems.length; i++) {
        let elem = elems[i];
				// console.log(elem)
				if (elem.hasAttribute("s-child")){
					// console.log(elem)
					if (elem.closest("[s-parent]") != null) {
						// console.log(elem)
						if (elem.closest("[s-parent]").getAttribute("s-default") == elem.getAttribute("s-frame")) {
							// console.log(elem)
							elem.closest("[s-parent]").setAttribute("s-state",elem.getAttribute("s-frame"))
							// console.log(elem)
							elem.style.display = "block"
						}
						else {
							elem.style.display
						}
					}
				}
      }
    })
     // for loop
		 console.log("%c Snyper Initiated","font-weight:bold;color:lightblue")
  }
  getChild(name,index=null){
    let children =  this.getChildren()
    if (index == null) {
    	return children[name]
    }
		else {
			return children[name][index]
		}
  }
  get state() {
    return this.elem.getAttribute("s-state")
  }
	nextState(){

		this.setState(this.indexMap[this.indexMap.indexOf(this.state)+1])
		// this.setState(this.indexMap[1])
	}
	prevState(){
		this.setState(this.indexMap[this.indexMap.indexOf(this.state)-1])
	}
  setState(name) {
    let children = this.getChildren()
		console.log(this.state)
    if (children[name] == undefined) {
      console.error("Specified child not found")
    }
    else {
      let oldState = this.state;
			console.log(`${oldState} ${children} `)
			for (let child of children[oldState]) {
				child.style.display ="none"

			}
			for (let child of children[name]) {
				child.style.display = "block"
			}
      this.elem.setAttribute("s-state",name)
    }
  }

}

function getQueryData(url=window.location.href) {
  let data = {}
  url = url.split("?")[1].split("&")
  for (var i = 0; i < url.length; i++) {
    let query = url[i].split("=")
    data[query[0]] = query[1]
  }
  return data
}
function baseName(str)
{
   var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if(base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
   return base+"."+str.split(".").pop();
}
function GlobalScript(scripts){
	var location = baseName(window.location.href);
	if (scripts[location] != undefined) {
	   afterload(function(){
       scripts[location]()
     })
	}
}
console.log("%c Snyper Loaded","font-weight:bold;color:lightgreen")
