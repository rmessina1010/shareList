<?
 
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('-RMLCMS/processors/RMCMS.php');
	include_once  ('includes/dash_process.php');
	include_once  ('includes/functions_forms.php');
	$listSQL = "SELECT * FROM `GLists` WHERE (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail) OR (`GLSubs` LIKE :theemail)) ORDER BY `GLName` ASC"; //SQL selects lists based on ownerID and ownerEmail
	$lists = new RMCDO(false, $listSQL, array( ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
?><html>
	<head>
		<title>ShareList Dashborard: <? echo $_SESSION["LISTlogged"]['username']; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<link rel="stylesheet" href="design/css/dashboard.css" media="screen">
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
							 <? echo build_opts('{{:GLName:}}', $lists->dump(), '{{:GLID:}}', false,3, array('GLEditors',','.$_SESSION["LISTlogged"]['email'].',','!has','GLOwner',$_SESSION["LISTlogged"]['UserID'])); ?>
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
							<?  echo build_opts('{{:GLName:}}', $lists->dump(), '{{:GLID:}}', false,3, array('GLOwner',$_SESSION["LISTlogged"]['UserID'],'!=')); ?>
 						 </select>
	 				</div>
  	 				<div class="col-sm-3 p-2 edit-view"> 
		 			   <button class="btn btn-secondary btn-block" type="submit" name="subbed" id="delList" value="Manage_Delete">Delete</button>
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
						 <ul class="thelist"></ul>
						 <div class="add-shell"><input class="toadd" type="email"><span class="add-icon"></span></div>
					 </div></div>
					 <div class="col-sm p-2"><div class="el-list-shell " id="sharees"  >
						 <h6>Subscribers:</h6>
						 <ul class="thelist"></ul>
						 <div class="add-shell"><input class="toadd" type="email"><span class="add-icon"></span></div>
					 </div></div>
			</div>
			<input  type="hidden" name="t" id="t" value="<? echo $_SESSION['LISTlogged']['stoken'];?>">
	  	</div>
 		   </form>
		</div>
 	</body>
 	<script src="js/helper_functions.js" charset="utf-8"></script>
 	<script src="js/shared_inits.js" charset="utf-8"></script>
 	<script src="js/dash_vars_init.js" charset="utf-8"></script>
 	<script src="js/dash_functions.js" charset="utf-8"></script>
 	<script type="text/javascript">
	    var deleteBtn = document.getElementById('delList');
		 deleteBtn.onclick = function(){
			 return confirm("Are you sure you wish to delete this list? The action is permanent and all your infomation will be lost. Click Cancel to abort. OK to continue.");
 		 }
   </script>
</html>
