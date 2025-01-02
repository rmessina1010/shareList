<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('-RMLCMS/processors/RMCMS.php');
	include_once  ('includes/functions_forms.php');
	$accordion = $_SESSION["LISTlogged"]['prefs']['acc'];
 	$autoUpdate = $_SESSION["LISTlogged"]['prefs']['auto'];
 	
 	
	// check if list exists and user has permissions to see it
	$listCheck = "SELECT * FROM `GLists` WHERE `GLID` = :glid  AND (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail) OR (`GLSubs` LIKE :theemail))";
	$listCheck = new RMCDO(false, $listCheck, array(':glid'=>$_GET['glid'], ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
	
	if (!$listCheck->the_('GLID')){ 
 		header('Location: error.php?e=1');
		die;
	}
  	//// process submitted form
 	include('includes/viewList_process.php');

  	//// prep list data
	$listID=  $listCheck->the_('GLID');
	$OPTs ='';
	if ( $_SESSION["LISTlogged"]['prefs']['view'] == 0){ $OPTs.= ' AND  `Needed` = 1 ';  }
	$item_sql ='SELECT * FROM `GLItems` WHERE `inGList` = :glid '.$OPTs.' ORDER BY `GLIOrd` ASC';
	$theList = new RMCDO(false, $item_sql, array(':glid'=>$_GET['glid']));
 ?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $listCheck->the_('GLName'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="design/css/main.css">
		<link type="text/css" rel="stylesheet" href="design/css/shared.css">
		<link type="text/css" rel="stylesheet" href="design/css/modal.css">

		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<link rel="stylesheet" href="design/css/view_list.css"   type="text/css" media="screen" charset="utf-8">
     <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
 	</head>
	<body>
				<h1 class="h3 text-center"><? echo $listCheck->the_('GLName'); ?></h1>
				<form method="POST" id="viewForm" class="collapsable">
					<div class="view-wrap ">
						<ul class="list" id="rootListUL"> 
							<? echo VLF_categories($theList); ?>						
						</ul>
					</div>
					<div class="control  fixed-bottom">
						<input  type="hidden" name="inList" id="inList" value="<? echo $listID;?>">
						<input  type="hidden" name="t" id="t" value="<? echo $_SESSION['LISTlogged']['stoken'];?>">
						<button type="submit" value="Update List" name="subbed" class="btn btn-primary">Update</button>							<button type="submit" value="Edit List" class="btn btn-secondary" name="subbed">Edit list</button>
						<!--<button type="submit" value="Edit Profile" name="subbed" class="btn btn-secondary">Edit Profile</button>-->
						<button type="submit" value="Dashboard" name="subbed" class="btn btn-secondary">Dashboard</button>						<button type="submit"  value="Logout" class="btn btn-secondary float-right" name="subbed">Logout</button>
						<!--
						<a href="dashboard.php?lid=xx" class="btn btn-secondary">Dashboard</a>
						<a href="editProfile.php" class="btn btn-secondary">Edit Profile</a>
						<a href="index.php?logout" class="btn btn-secondary">Log Out</a>-->
	 				</div>
 				</form>
	</body>
 	<script type="text/javascript"  src="js/general.js"></script>
 </html>