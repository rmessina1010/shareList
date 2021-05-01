	const serviceURL ='http://rmdesign.byethost32.com/sharelist/_services.php?';
	const _STOKEN = document.getElementById('t').value;


	function setPrefQS(logout){ /// set preferences
		if (logout === undefined) { logout = false;}
		var acc=document.getElementById('accordion').checked ? 1:0;
		var all=document.getElementById('all').checked ? 1:0; 
		var auto=document.getElementById('auto').checked ? 1:0; 
		var str = new Array();
		str[0]= ((acc 	!=   accBuff)  || logout) ? '&acc='+acc 	: '';
		str[1]= ((all 	!=   viewBuff) || logout) ? '&view='+all 	: '';
		str[2]= ((auto 	!=   autoBuff) || logout) ? '&auto='+auto 	: '';
		return str.join('');
	}
	
	
	function  itemAdjuster(el){//  togles read only on Qty. Input.
		Qt=el.parentNode.parentNode.parentNode.querySelector('input[name="qt[]"]');
		needFul=el.parentNode.querySelector('input[name="theNeed[]"]');
		needFul.value = el.checked ? 1 :0;
		Qt.readOnly=!el.checked ; 
		if (Qt.value===''){Qt.value=1;}
	}
	
	function  deleteMe(el, lvl,  itemClass, storeDel){
		deleteMe.selectorString =  deleteMe.selectorString || 'input[name="itemID[]"]';
	    itemClass = itemClass || 'item';
 		if (lvl){ el= getParentNode(lvl, el);}
 		if( el.className== itemClass){
			if (addToDeleteList) {addToDeleteList(el.querySelector(deleteMe.selectorString).value,storeDel);}
		}
		else{
			delList=el.querySelectorAll(deleteMe.selectorString);
			for (var i=0, l=delList.length; i<l; i++){
				if (addToDeleteList) { addToDeleteList(delList[i].value, storeDel ); }
			}
		}
		ul=el.parentNode;
		ul.removeChild(el);
	}
	
	function addToDeleteList(dataBit, storeDel){
  		if ( storeDel === undefined ){ storeDel = 'toDelete';  }
 		if ( typeof storeDel ==='string'){ storeDel = document.getElementById(storeDel);}
 		if ( !isInput(storeDel) || dataBit  == '?' ||   dataBit == 'NULL' ){ return; }
		var glue= storeDel.value == '' ? '':',';
		if(storeDel ) {storeDel.value=storeDel.value+glue+dataBit;}
   	}
   	
	function toggleClass(obtain,clss,lvl){ //( element || node list or query string ) , class to togle, [target ancetser level... in case of element])
		if (typeof obtain === 'string'){ elList = document.querySelectorAll(obtain);}
		else if (obtain instanceof NodeList){ elList = obtain;}
		else if ( isHTMLelement(obtain)){
				var elList = new Array();
				if (lvl && lvl>0){ elList[0] =getParentNode(lvl, obtain);}
				else{ elList[0] =obtain; }
		}
		else {return;}
		var needle = ' '+clss+' ';
		var haystack, el;
		for (var i=0,l=elList.length; i<l; i++){
 		    haystack = ' '+elList[i].className+' ';
			if (haystack.indexOf(needle) > -1){
				haystack=haystack.replace( needle, ' '); 
				haystack=haystack.replace(' ','  '); 
	 			elList[i].className = haystack;
			}
			else { elList[i].className=elList[i].className+' '+clss;}
		}
	}
	function checkToggle(el,depth,selector){ // ('button' element,  how deep nested is 'button' ,selector for  other 'buttons' affected)
		depth = (depth === undefined) ? 3 : depth;  //deafault 3  decentants up
		selector = selector || false;
		checkToggle.off = checkToggle.off ||  'Check All';   // checkToggle.off , checkToggle.on , checkToggle.targetElQryStr are user definabale  via object primitive
		checkToggle.on = checkToggle.on || 'Uncheck All';
		checkToggle.targetElQryStr= checkToggle.targetElQryStr ||  '.itembox';
		if (el.toggle ===  undefined  ){ el.toggle = true}
		if (el.toggle){ el.innerHTML=checkToggle.on}
		else{ el.innerHTML= checkToggle.off}
		// set all input.theNeed value  to el.toggle, and run foo for Q();
		var theUL = getParentNode(depth, el);
 		var theItems= theUL.querySelectorAll(checkToggle.targetElQryStr);
  
		for (var i=0, l=theItems.length; i<l; i++ ){ 
			theItems[i].checked=el.toggle;
			nextSibling = theItems[i].nextSibling;
			while(nextSibling && nextSibling.nodeType != 1  ) {
			    nextSibling = nextSibling.nextSibling
			}
			nextSibling.value = el.toggle ? 1 : 0 ; 
		}
		if (selector){
			var theButtons = theUL.querySelectorAll( selector );
  			for ( i=0, l=theButtons.length; i<l; i++ ){
					theButtons[i].toggle=(el.innerHTML !== checkToggle.on );
					theButtons[i].innerHTML=el.innerHTML
			}		     
		}
 		el.toggle=!el.toggle;
	}

 		function prepMaster(id, removeMaster){
  			prepMaster.els =  prepMaster.els || Array();
			var el= document.getElementById(id).cloneNode(true);
			el.id='';
			if (removeMaster === undefined || removeMaster ){ document.getElementById(id).parentNode.removeChild(document.getElementById(id));}
			prepMaster.els[id]=el;
  		}
  		
		function addAfter(el,master){
		 		master =  (typeof master == 'string') ? master : 'masterLI';
		 		var theUL = el;
		 		if( theUL.tagName !=='UL'){
			 		theUL=(el.nextSibling && el.nextSibling.tagName === 'UL') ? el.nextSibling : el.nextSibling.nextSibling;
 		 		}
				theUL.appendChild(prepMaster.els[master].cloneNode(true));
		}
		function addBetween(el,master){
		 		master =  (typeof master == 'string') ? master : 'masterLI';
		 		var theUL = el;
		 		if( theUL.tagName !=='UL'){
			 		theUL=(el.nextSibling && el.nextSibling.tagName === 'UL') ? el.nextSibling : el.nextSibling.nextSibling;
 		 		}
				theUL.insertBefore(prepMaster.els[master].cloneNode(true),theUL.firstChild);
		}
		
		function addBefore(el,master){
				master =  (typeof master == 'string') ? master : 'masterCat';
				var theUL = el;
		 		if( theUL.tagName !=='UL'){
					theUL= (el.previousSibling &&  el.previousSibling.tagName === 'UL') ? el.previousSibling : el.previousSibling.previousSibling;
				}
   				theUL.appendChild(prepMaster.els[master].cloneNode(true));
		}
		
		function slide(el,grpDpth,lvlChk){ 
			 slide.slideBuffer= slide.slideBuffer || null;
			 if (grpDpth === undefined) { grpDpth = 1;}
			 theGroup = getParentNode(grpDpth, el);
 			 lvlChk = (  typeof lvlChk === 'string') ?   lvlChk : theGroup.className;
			 if (slide.slideBuffer){ 
				 var goingToIndx=Array.prototype.indexOf.call(theGroup.parentNode.childNodes, theGroup);
				 var comingFromIndx=Array.prototype.indexOf.call(slide.slideBuffer.parentNode.childNodes, slide.slideBuffer);
	 			 if( theGroup.className.indexOf(lvlChk)>-1 && slide.slideBuffer.className.indexOf(lvlChk)>-1 ){
		 			 chekForClass=' '+slide.slideBuffer.className+' ';
		 			 if(chekForClass.indexOf(' highlite ')> -1){ slide.slideBuffer.className=chekForClass.replace(' highlite ', '');}  
		 			 if (comingFromIndx > goingToIndx) {theGroup.parentNode.insertBefore(slide.slideBuffer, theGroup);}
		 			 else{ theGroup.parentNode.insertBefore(slide.slideBuffer, theGroup.nextSibling);}
					 slide.slideBuffer=false;
				 }
			 }
			 else{ slide.slideBuffer = theGroup;  theGroup.className=theGroup.className+' highlite';}
		}
		
		function getParentNode(lvl, obj){
			 for ( var i=0; i < lvl; i++ ){ obj = obj.parentNode;}
			 return obj;
		}
		function isHTMLelement(obj){ return (typeof obj !== 'undefined'  &&  obj.nodeType  === Node.ELEMENT_NODE) ;}
		function isInput(obj){ return (obj.nodeName === "INPUT") ;}


	 function AJAXhandler(url, data, method,ctyp){
	 		// Create a request object or fail 
		  	if (window.XMLHttpRequest){ var req=new XMLHttpRequest();}
		 	else if(window.ActiveXObject){ var req= new ActiveXObject("Microsoft.XMLHTTP")}
		 	else {return false }
		 	//prep data for sending  , depeneding  on which method ( POST || GET)  
		 	if	(typeof method == 'undefined'){method="GET";}
			method=method.toUpperCase()
		 	if 	(method != "POST"){ method="GET"; }
		 	if(url[url.length -1] =='?') {  url = url.substr(0,url.length-1)}
 		 	url = ( typeof data =='string' && method=="GET") ? url+"?"+data : url;
		 	var dat = (method === "GET" || typeof data === 'undefined') ? null : data;
 		 	req.open(method, url, true);
			ctyp= (typeof ctyp!='undefined') ? ctyp : "application/x-www-form-urlencoded";
		 	if (method == "POST") {req.setRequestHeader("Content-type",ctyp);}
		 	req.send(dat); 
		 	return req;		   
		 }	

		
		function AUView(obj){ 
 
				var AU_inList = document.getElementById('inList').value;
				var theData ='';
 				if (obj.type == 'checkbox' ) { 
 					var need=  obj.checked ?   '0' :'1';
 					theData='isNeeded=' + need; 
 				}else{ 
	 				theData= (theData ? '&' : '')+'qty=' + obj.value;  
	 			}
				theData+='&inList='+AU_inList+'&theName='+obj.name+'&AUV=1&_t='+_STOKEN;
  				var req=AJAXhandler(serviceURL, theData);
		  		req.onreadystatechange = function(){
		 			if(req.readyState == 4 && req.status == 200 ){
		 		   		if (req.responseText.indexOf('error') > -1) {alert  (req.responseText);}
		 			}
		 		}
		 }
		 
	function confirmSub(m){
		if (!m) { m ="This action cannot be undone. Proceed anyway?";} //default message
 		return confirm(m);
 	}
 	
///////********/////


function toggleClass2(obtain,clss,alt){ //( element || node list or query string ) , class to togle )
		var elList;
		if (typeof obtain === 'string'){ elList = document.querySelectorAll(obtain);}
		else if (obtain instanceof NodeList){ elList = obtain;}
		else if ( isHTMLelement(obtain)){ elList = new Array(obtain); }
		else {return;}
		var needle = ' '+clss+' ';
		var haystack, el;
		for (var i=0,l=elList.length; i<l; i++){
 		    haystack = ' '+elList[i].className+' ';
			if (haystack.indexOf(needle) > -1){
				haystack=haystack.replace( needle, ' '); 
				haystack=haystack.replace(' ','  '); 
	 			elList[i].className = haystack;
			}
			else { elList[i].className=elList[i].className+' '+clss;}
		}
}
	
function getAncestor(el, lev){
  	if (lev < 0) { return false;}
 	while (lev > 0){
	 	el=el.parentNode;
	 	lev--;
 	}
 	return el;
}

function obtainElements(root,selector, all){
  	var el = root.querySelectorAll(selector);
  	if (!el) { return; }
  	return all ? el : el[0];
}

function accordionHandler(button,lvl,sel,clss,bclass){
 	var root = getAncestor(button,lvl);
	var target = obtainElements(root,sel,false);
	toggleClass2(target,clss);
	var oldClass = (button.className.indexOf('fa-angle-double-up') > -1) ? 'fa-angle-double-up' : 'fa-angle-double-down' ;
	var newClass = (oldClass == 'fa-angle-double-up') ?   'fa-angle-double-down'  : 'fa-angle-double-up';
    button.className=button.className.replace(oldClass, newClass);
}