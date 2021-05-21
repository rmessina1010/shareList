	function isValidEmail(email, message){
		  if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))){
			  if(message){ alert(message);}
			  return false;
		  }
		  return true;
	}
	 
	 function makeItem(email , nome, tag, type ){
	 	let newLI = document.createElement(tag);
	 	let newINPUT = document.createElement('INPUT');
	 	newINPUT.name=nome;
	 	newINPUT.value=email;
	 	newINPUT.type=type ;
	 	let newSPAN = document.createElement('SPAN');
	 	newSPAN.className ='remove-icon';
	 	if (type === 'hidden'){
	 		let textNode =document.createTextNode(email);
	 		newLI.appendChild(textNode);
	 	}
	 	newLI.appendChild(newINPUT);
	 	newLI.appendChild(newSPAN);
	 	return newLI;
	 }

	function insertToInnerHTML(HTML,targets,sources){
		var shell = document.createElement('div');
		shell.innerHTML = HTML ;
		var rep ='';
		for (let i =0, l=targets.length; i < l; i++){
	  		if (sources[i] !== undefined){
	 	  		if (!sources[i] || sources[i] == '*' ) { rep = shell.innerHTML ;}
	 	  		else{ 
		 	  		let el =shell.querySelector(sources[i]) ;
		 	  		rep = el ? el.innerHTML : '';
		 	  	}
		   	} 
		   	targets[i].innerHTML = rep; 
	  	}
	 }

	 function doAJAX(url, data, handler, method, handlerArgs, ctyp, doFail){
	 	if (typeof handler === 'string' ) {
			if  (window[handler] === undefined) { return false;}
			handler = window[handler];
		}
	  	if (window.XMLHttpRequest){ var req=new XMLHttpRequest();}
	 	else if(window.ActiveXObject){ var req= new ActiveXObject("Microsoft.XMLHTTP")}
	 	else {return false }
	 	 if	(  method === undefined  || (method && (typeof method === typeof 'string' && method.replace(/^\s+|\s+$/gm,''.toUpperCase()) !== 'POST' )) || (method && (typeof method !== typeof 'string'))){method="GET";}
	 	else{ method='POST'; } 
	 	url = ( typeof data === 'string' && method === "GET") ? url+"?"+data : url;
	 	var dat = (method === "GET" ||   data === undefined) ? null : data;
	 	req.open(method, url, true);
		ctyp= (typeof ctyp !== typeof 'string') ? ctyp : "application/x-www-form-urlencoded";
	 	if (method == "POST") {req.setRequestHeader("Content-type",ctyp);}
	 	req.send(dat); 
 		req.onreadystatechange = function(){
			if(req.readyState == 4 ){
				if (  handlerArgs !== undefined) {
					  var arry= [req.responseText];
					  if (typeof handlerArgs  === typeof []){ arry=arry.concat(handlerArgs);}
					  else{ arry.push(handlerArgs)}
				}
				if(req.status == 200){ 
					if (!!(handler && handler.constructor  &&  handler.apply)){ 
						handler.apply(this, arry);
					}  
				}
				else if(doFail){ doFail.apply(this, arry); }
			}
		}
	}
