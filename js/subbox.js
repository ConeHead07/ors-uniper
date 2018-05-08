  function E(id) { return document.getElementById(id); }
  function D(id) { return E(id).style.display; }
  function CNR(id, c1, c2) { E(id).className = E(id).className.replace(c1, c2); }
  function CheckHide(SubId) { if (!E(SubId).mouseisover) CNR(SubId, "SubBoxShow", "SubBoxHide"); }
  function ChD(id) { if (!E(id)) return false; E(id).style.display = (D(id)!="none"?"none":""); }
  function ShowSubBox(Id, SubId) { if (!E(Id) || !E(SubId)) return false; CNR(SubId, "SubBoxHide", "SubBoxShow"); /* Setz SubBoxPosition*/ E(SubId).style.position="absolute"; E(SubId).style.left = PageInfo.getElementLeft(Id); E(SubId).style.top = PageInfo.getElementTop(Id)+PageInfo.getElementHeight(Id)-1; /* Registrier Mousehandler */ E(Id).onmouseout=function() { setTimeout("CheckHide('"+SubId+"')", 1000); }; E(SubId).onmouseout=function()  { this.mouseisover = false; setTimeout("CheckHide('"+this.id+"')", 500); }; E(SubId).onmousemove=function() { this.mouseisover = true; } }
  function subbox_add_cssrules() { var cssdiv = document.createElement("div"); cssdiv.innerHTML = "<img style='display:none;'><"+"style"+">\n.SubBoxHide { display:none; }\n.SubBoxShow { display:; }\n<"+"/"+"style"+">\n"; document.body.appendChild(cssdiv); }
  
  if (window.addEventListener) window.addEventListener("load", subbox_add_cssrules, false);
  else if (window.attachEvent) window.attachEvent("onload", subbox_add_cssrules);