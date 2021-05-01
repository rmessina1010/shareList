		 
 		 function siblingClassToggle(el,clss,cond){
			 el.classList.toggle(clss,cond);
		 }
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
		 	newSPAN.className ='remove';
		 	if (type === 'hidden'){
		 		let textNode =document.createTextNode(email);
		 		newLI.appendChild(textNode);
		 	}
		 	newLI.appendChild(newINPUT);
		 	newLI.appendChild(newSPAN);
		 	return newLI;
		 }
		 
		 function listHandler(e, createItem, createItemArgs, validate,  validateArgs){
 			 if (this.classList.contains('el-list-shell')){
				 if(e.target.classList.contains('remove-icon')){
				 	e.target.parentNode.parentNode.removeChild(e.target.parentNode);
			 	 }
				 if(e.target.classList.contains('add-icon')){
				 	let theInput = this.querySelector('.toadd');
				 		// validate 
				 		if (!validate || validate.apply(this, [theInput.value].concat(validateArgs ))){ 
					 		let theList = this.querySelector('.thelist');
					 		// make element
					 		let newLI = createItem.apply(this,[theInput.value].concat(createItemArgs));
					 		 theList.appendChild(newLI) ;
				 		}
 				 	theInput.value='';
			 	 }
 			 }
 		 }
 		 
  		 function editorHandler(e){
 	 		 listHandler.call(this, e,makeItem, ["editor[]",'LI', 'hidden'],isValidEmail, ['Please enter a valid email address.'] )
 		 }
 		 function shareesHandler(e ){
 	 		 listHandler.call(this, e,makeItem, ["shared[]",'LI', 'hidden'],isValidEmail, ['Please enter a valid email address.'] )
 		 }
 		 
		editors.addEventListener('click', editorHandler)
		sharees.addEventListener('click', shareesHandler)


 		 function editListHandler(e){
	 		 siblingClassToggle(document.getElementById('listCTR'),'add-mode',e.target.value=='' );
 		 }
 		 selectListEdit.addEventListener('change', editListHandler);
