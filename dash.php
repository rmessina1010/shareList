<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
 
	if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){
		$SQL  = false;
 		$data[':editors']	= ','.implode(',', $_POST['editors']).',';
 		$data[':shares']		= ','.implode(',', $_POST['shares']).',';
   		$glistname ='';
  		if ($_POST['listNewName']){
	  		$glistname = ' `GLName` = :listname, ';
  		}
 		/// UPDATE LIST
		if ($_POST['subbed'] == 'Manage_Update'){
  			$data[':glid'] = $_POST['manageList'];
 			$SQL = 'UPDATE `GLists` SET '.$glistname.' `GLEditors` = :editors, `GLSubs` = :shares WHERE `GLID` = :glid';
		}
		// ADD LIST
		if ($_POST['subbed'] == 'Manage_Add'  &&  $_POST['listNewName']){
 			$data[':owner']		= $_SESSION["LISTlogged"]['UserID'];
 			$SQL = 'INSERT INTO `GLists` (`GLOwner`, `GLName`, `GLEditors`, `GLSubs`) VALUES (:owner, :listname, :editors, :shares)';
		}
		// DELETE LIST
		if ($_POST['subbed'] == 'Manage_Delete' ){
  			$SQL = 'DELETE FROM `GLists` WHERE  `GLID` = ? )';
  			$data = array($_POST['manageList']);
		}
		if ($SQL){ doQ($SQL, $data); }
		include ('includes/submit_redirs.php');
	}

	$listSQL = "SELECT * FROM `GLists` WHERE (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail) OR (`GLSubs` LIKE :theemail)) ORDER BY `GLName` ASC"; //SQL selects lists based on ownerID and ownerEmail
	$lists = new RMCDO(false, $listSQL, array( ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));

  ?><html>
	<head>
		<title>ShareList Dashborard: <? echo $_SESSION["LISTlogged"]['username']; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<style type="text/css" media="screen">
			.section {
				
 				border: 1px solid #555;
 				margin-bottom: 1em;
 			}
			form { margin:0;
				padding: 0;
 			}
			.section h6 { 
				padding: 1em;
 				background: #555;
 			 }
	 		nav+.section { margin-top:1em;}
	 			 
			.el-list-shell {
				padding: .5em;
				border: 1px solid #555;
				border-radius: .25em;
			}
			.el-list-shell > ul{
				list-style: none;
				padding: 0;
				margin: 0;
				list-style: none; 			
				display: inline-block;
	
			}
			
			.el-list-shell > ul>   li ,.add-shell{
	 			padding: .3em;
	 			display: inline-block;
				border: 1px solid #8aadee;
				border-radius: .3em;
				margin: .4em   ;
				font-family: sans-serif;
				font-size: 75%;
	 		}
			.add-shell input {
	 			margin-right: .6em ;
	 			border: none;
	 		}
	 		
	 		.remove-icon {
		 		display:inline-block; 
	
	 		}
	 		.remove-icon:before, .add-icon:before{
		 		display:inline-block;
	  	 		font-family: sans-serif;
	 	 		background:silver;
	 	 		margin: 2px;
		 		text-align: center;
		 		padding: .1em .6em ;
		 		border-radius:50%;
		 		margin-left:.4em;
	  		}
	  		.remove-icon:before{
		  		content:'x';
	
	  		}
	  		.add-icon:before{
		  		content:'+';
	
	  		}
	  		.add-mode .add-view, .edit-view {
		  		display:block;  
		  		margin: 0 ;

	  		} 
	  		.add-view, .add-mode .edit-view{
		  		display:none; 
	  		} 
  		</style>
	</head>
	<body>
		
		<div class="container">
		<? include ('includes/top_nav.php'); ?>
  			<form  id="listCTR" method="POST">
	  			<div class="section">
	 	 			<h6 > View List:</h6>
					<div class="row no-gutters">
		 				<div class="col-sm-9 p-2 "> 
			 				<select class="form-control" name="inList" id="inList">
				 			<?  echo build_opts('{{:GLName:}}', $lists->dump(), '{{:GLID:}}'); ?>
							</select>
		 				</div>
		 				<div class="col-sm-3 p-2"> 
			 			   <button class="btn btn-primary btn-block m2" type="submit" value="View List" name="subbed" >View</button>
		 				</div>
					</div>
	  			</div>
	  			<div class="section">
  	 			<h6 class="">User Preferences:</h6>
				<div class="row no-gutters " id="prefblock">
							<div class="col  p-2"><label class="form-control"><input  type="checkbox" name="prefs[view]" id="all"   value="1"  <? echo  (isset($_SESSION["LISTlogged"]['prefs']['view']) && $_SESSION["LISTlogged"]['prefs']['view']) ? ' CHECKED ' : '' ; ?>	> View All</label></div>
							<div class="col  p-2"><label class="form-control"><input  type="checkbox" name="prefs[acc]" id="accordion"   value="1" <? echo  (isset($_SESSION["LISTlogged"]['prefs']['acc']) && $_SESSION["LISTlogged"]['prefs']['acc']) ? ' CHECKED ' : '' ; ?>					 
 > Accordion</label></div>
							<div class="col-md p-2"><label class="form-control"><input   type="checkbox" name="prefs[auto]" id="auto" value="1" <? echo  (isset($_SESSION["LISTlogged"]['prefs']['auto']) && $_SESSION["LISTlogged"]['prefs']['auto']) ? ' CHECKED ' : '' ; ?>					 
> Auto Update View</label></div>
	 				</div>
	  			</div>
	  			
	  			<div class="section">
 	 			<h6 class="section-title">Edit List:</h6>
				<div class="row no-gutters">
	 				<div class="col-sm p-2 "> 
						 <select name="editListEdit" id="editListEdit" class="form-control"> 
					 	<? 
						 	echo build_opts('{{:GLName:}}', $lists->dump(), '{{:GLID:}}', false,3, array('GLEditors',','.$_SESSION["LISTlogged"]['email'].',','!has','GLOwner',$_SESSION["LISTlogged"]['UserID']));
						 ?>
 						 </select>
	 				</div>
	 				<div class="col-sm-3 p-2 edit-view">
 		 			   <button class="btn btn-primary btn-block edit-view" type="submit" name="subbed" value="Dash Edit" >Edit</button>
	 				</div>
 				</div>
 	  			</div>
	  			<div class="section add-mode">
 	 			<h6 class="section-title">Manage List:</h6>
				<div class="row no-gutters">
	 				<div class="col-sm p-2 "> 
						 <select name="manageList" id="manageList" class="form-control ">
							<option value="">--NEW LIST--</option>
				 	<? 
					 	echo build_opts('{{:GLName:}}', $lists->dump(), '{{:GLID:}}', false,3, array('GLOwner',$_SESSION["LISTlogged"]['UserID'],'!=')); ?>
 						 </select>
	 				</div>
  	 				<div class="col-sm-3 p-2 edit-view"> 
		 			   <button class="btn btn-secondary btn-block" type="submit" name="subbed"  value="Manage_Delete">Delete</button>
	 				</div>
				</div>
				<div class="row no-gutters">
	 				<div class="col-sm p-2 "> 
		 				<input type="text" name='listNewName' id='listNewName' class="form-control">
 	 				</div>
	 				<div class="col-sm-3 p-2 "> 
		 			   <button class="btn btn-primary btn-block   edit-view" type="submit" name="subbed"  value="Manage_Update">Update</button>
 		 			   <button class="btn btn-primary btn-block m2 add-view" type="submit" name="subbed" value="Manage_Add">Add</button>
	 				</div>
 				</div>
 				<div class="row no-gutters">
					 <div class="col-sm p-2"><div class="el-list-shell col-sm p-2" id="editors" >
						 <h6>Editors:</h6>
						 <ul class="thelist"  >
							 <? /*echo build_email_list(trim($lists->the_('GLEditors'),','),'editors' , 4,'remove-icon' ); */?>
						 </ul>
						 <div class="add-shell"><input class="toadd" type="email"><span class="add-icon"></span></div>
					 </div></div>
					 <div class="col-sm p-2"><div class="el-list-shell " id="sharees"  >
						 <h6>Subscribers:</h6>
						 <ul class="thelist" >
							<? /*echo build_email_list(trim($lists->the_('GLSubs'),','),'shares' , 4,'remove-icon' ); */?>
						 </ul>
						 <div class="add-shell"><input class="toadd" type="email"><span class="add-icon"></span></div>
					 </div></div>
			</div>
			<input  type="hidden" name="t" id="t" value="<? echo $_SESSION['LISTlogged']['stoken'];?>">
	  	</div>
 		   </form>
		</div>
		 
	</body>
		<script type="text/javascript">
			const serviceURL ='http://rmdesign.byethost32.com/sharelist/_services.php?';
			const _STOKEN = document.getElementById('t').value;
 			var selectListManage = document.getElementById('manageList');
			var ownedListActions = document.getElementById('ownedListActions');
	 		var theShareesList = document.querySelector('#sharees ul.thelist');
	 		var theEditorsList = document.querySelector('#editors ul.thelist');
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
	 		 function shareesHandler(e ){
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

			prefblock.addEventListener('click', prefHandler);
			editors.addEventListener('click', editorHandler);
			sharees.addEventListener('click', shareesHandler);
	 		selectListManage.addEventListener('change', manageListHandler);
	 		 
	 		 
	 		 
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
	</script>
	
</html>
