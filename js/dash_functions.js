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
	 listHandler.call(this, e,makeItem, ["editors[]",'LI', 'hidden'],isValidEmail, ['Please enter a valid email address.'] )
 }
 function shareesHandler(e){
	 listHandler.call(this, e,makeItem, ["shares[]",'LI', 'hidden'],isValidEmail, ['Please enter a valid email address.'] )
 }

 function prefHandler(e){
	if (e.target.tagName === 'INPUT'){ 
 		   let prefName  = e.target.name.replace('prefs[', '').replace(']', '');
 		   let isChecked = e.target.checked ? '1' : '0';
 		   doAJAX(serviceURL+'pref='+prefName+'&'+'isChecked='+isChecked+'&_t='+_STOKEN);
 	}
 }

function manageListHandler(e){
	let isMakeNewList = (e.target.value == '');
	let listSegment = e.target.parentNode.parentNode.parentNode; 
	listSegment.classList.toggle('add-mode', isMakeNewList);///select sibling instead
	if (isMakeNewList){ 
 		insertToInnerHTML('', [theShareesList, theEditorsList],['*'])
 		return;
 	}
	doAJAX(
	     serviceURL+'eml=s,e&glid='+e.target.value+'&_t='+_STOKEN,
	     null, 
	     insertToInnerHTML,
	     'GET',
	 	[ 
	 		[theShareesList, theEditorsList],
	 		['#sharesEmails', '#editorsEmails']
	 	]
	 );
}




prefblock.addEventListener('click', prefHandler);
editors.addEventListener('click', editorHandler);
sharees.addEventListener('click', shareesHandler);
selectListManage.addEventListener('change', manageListHandler);



