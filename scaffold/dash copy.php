<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
	
	$lists = "SELECT * FROM `GLists` WHERE (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail) OR (`GLSubs` LIKE :theemail))";
	$lists = new RMCDO(false, $lists, array( ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));

  ?><html>
	<head>
		<title>ShareList Dashborard: <? echo $_SESSION["LISTlogged"]['username']; ?></title>
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<style type="text/css" media="screen">
			.section {
				
 				border: 1px solid #555;
 				margin-bottom: 1em;
 			}
			form { margin:0;
				padding: 0;
 			}
			form > h6 { 
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
  		} 
  		.add-view, .add-mode .edit-view{
	  		display:none; margin: 0 !important;
  		} 
  		
  		   		</style>
	</head>
	<body>
		
		<div class="container">
		<? include ('includes/top_nav.php'); ?>
  			<form class="section">
	 			<h6 class=""> View List:</h6>
				<div class="row no-gutters">
	 				<div class="col-sm-9 p-2 "> 
		 				<select class="form-control" name="listChoice" id="listChoice">
			 			<?  echo build_opts('{{:GLName:}}', $lists->dump(), '{{:GLID:}}'); ?>
						</select>
	 				</div>
	 				<div class="col-sm-3 p-2"> 
		 			   <button class="btn btn-primary btn-block m2" type="submit">View</button>
	 				</div>
				</div>
 		   </form>
 			<form class="section">
	 			<h6 class="">Preferences</h6>
				<div class="row no-gutters">
							<div class="col-sm p-2"><label class="form-control"><input  type="checkbox" name="all" id="all"   value="1"  <? echo  (isset($_SESSION["LISTlogged"]['prefs']['view']) && $_SESSION["LISTlogged"]['prefs']['view']) ? ' CHECKED ' : '' ; ?>	> View All</label></div>
							<div class="col-sm p-2"><label class="form-control"><input  type="checkbox" name="accordion" id="accordion"   value="1" <? echo  (isset($_SESSION["LISTlogged"]['prefs']['acc']) && $_SESSION["LISTlogged"]['prefs']['acc']) ? ' CHECKED ' : '' ; ?>					 
 > Accordion</label></div>
							<div class="col-sm-5 p-2"><label class="form-control"><input   type="checkbox" name="auto" id="auto" value="1" <? echo  (isset($_SESSION["LISTlogged"]['prefs']['auto']) && $_SESSION["LISTlogged"]['prefs']['auto']) ? ' CHECKED ' : '' ; ?>					 
> Auto Update View</label></div>
	 				</div>
  		   </form>
 			<form class="section add-mode" id="listCTR">
	 			<h6 class="">List:</h6>
				<div class="row no-gutters">
	 				<div class="col-sm p-2 "> 
						 <select name="selectList" id="selectListEdit" class="form-control"> 
							 <option value="">--NEW LIST--</option>
							 <option value="1">--1 LIST--</option>
							 <option value="2">--2 LIST--</option>
 						 </select>
	 				</div>
	 				<div class="col-sm-2 p-2 edit-view">
 		 			   <button class="btn btn-primary btn-block   edit-view" type="submit">Update</button>
	 				</div>
	 				<div class="col-sm-2 p-2 edit-view">
 		 			   <button class="btn btn-secondary btn-block   edit-view" type="submit">Edit</button>
	 				</div>
	 				<div class="col-sm-2 p-2 edit-view"> 
		 			   <button class="btn btn-secondary btn-block" type="submit">Delete</button>
	 				</div>
				</div>
				<div class="row no-gutters">
	 				<div class="col-sm p-2 "> 
		 				<input type="text" name='listName' id='listName' class="form-control">
 	 				</div>
	 				<div class="col-sm-3 p-2 "> 
		 			   <button class="btn btn-secondary btn-block m2 edit-view" type="submit">Rename</button>
		 			   <button class="btn btn-primary btn-block m2 add-view" type="submit">Add</button>
	 				</div>
 				</div>
 				<div class="row no-gutters">
					 <div class="col-sm p-2"><div class="el-list-shell col-sm p-2" id="editors" >
						 <h6>Editors:</h6>
						 <ul class="thelist"  >
						 </ul>
						 <div class="add-shell"><input class="toadd" type="email"><span class="add-icon"></span></div>
					 </div></div>
					 <div class="col-sm p-2"><div class="el-list-shell " id="sharees"  >
						 <h6>Subscribers:</h6>
						 <ul class="thelist" >
						 </ul>
						 <div class="add-shell"><input class="toadd" type="email"><span class="add-icon"></span></div>
					 </div></div>
			</div>

 				
 		   </form>
		</div>
		 
	</body>
		<script type="text/javascript">
		 
		 
		 
		 var selectListEdit = document.getElementById('selectListEdit');
		 var ownedListActions = document.getElementById('ownedListActions');
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
	</script>
	
</html>
