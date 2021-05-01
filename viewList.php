<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
	$accordion = $_SESSION["LISTlogged"]['prefs']['acc'];
 	$autoUpdate = $_SESSION["LISTlogged"]['prefs']['auto'];
 	
 	
	$listCheck = "SELECT * FROM `GLists` WHERE `GLID` = :glid  AND (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail) OR (`GLSubs` LIKE :theemail))";
	$listCheck = new RMCDO(false, $listCheck, array(':glid'=>$_GET['glid'], ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
	if (!$listCheck->the_('GLID')){ // check if list exists and user has permissions to see it
		//var_dump($listCheck->res, $_SESSION["LISTlogged"]['UserID']);
 		header('Location: error.php?e=1');
		die;
	}
  	
	if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){
		
 				if ( !$autoUpdate){ 
 					$notNeeded= isset($_POST['need']) ?  implode (',',$_POST['need']) : '0';
					$Q[]='UPDATE `GLItems` SET `Needed`=0 WHERE `GLIID` IN ('.$notNeeded.') AND `inGList`='.$_POST['inList'];
 
					if( $_SESSION["LISTlogged"]['prefs']['view']) {
						$Q[]='UPDATE `GLItems` SET `Needed`=1 WHERE `GLIID` NOT IN ('.$notNeeded.') AND `inGList`='.$_POST['inList']; }
					doQ( $Q, false);
				}
				if (isset($_POST['QT']) && !$autoUpdate){ 
	 				$stmnt =prepSTMT( 'UPDATE `GLItems` SET `QTY`= ? WHERE `GLIID`= ? AND `inGList`= ?');
					foreach ($_POST['QT'] as $qtID=>$qqt ){
						if ( $_POST['OQT'][$qtID] != $qqt && $qqt>0 ){$stmnt->execute (array($qqt,$qtID,$_POST['inList']));}
					}
				}
				include ('includes/submit_redirs.php');
	 	}
	
		
	$listID=  $listCheck->the_('GLID');
	$OPTs ='';
	if ( $_SESSION["LISTlogged"]['prefs']['view'] == 0){ $OPTs.= ' AND  `Needed` = 1 ';  }
	$item_sql ='SELECT * FROM `GLItems` WHERE `inGList` = :glid '.$OPTs.' ORDER BY `GLIOrd` ASC';
	$theList = new RMCDO(false, $item_sql, array($_GET['glid']));
	
	
 ?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $listCheck->the_('GLName'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="http://rmdesign.byethost32.com/lists/parts/css/main.css">
		<link type="text/css" rel="stylesheet" href="design/css/shared.css">
		<link type="text/css" rel="stylesheet" href="http://rmdesign.byethost32.com/lists/parts/css/modal.css">

		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<style type="text/css" media="screen">
				    
			.list-group-item label input{ margin-right: .33em;    }
			.list-group-item label{ display: block !important;  padding: .5em  ; margin:0 !important; overflow: hidden;}
			.real { z-index: 4000}
			.row{
				 margin: 0;
				 padding: 0;
				 list-style: none;
			}
			form.collapsable .category{
				background: #eee;
				padding-bottom: .67em;
			}
			form.collapsable .category:nth-child(2n+1){
				background: #bbb;

 			}
 			.list-group-item{ padding:0 !important; font-size: 70%;}
 			.list-group-item:hover, .categoryName i:hover { background: var(--teal); color: #fff;}
 			.categoryName i:hover { border-color: #09af7b;}
 			
 			textarea.share-list { display:block; width: calc(100% - 1.34em); margin:1em .67em; padding:.2em .5em; column-count:3; -webkit-column-count:3; line-height: 2; }
 			button.share-list.shw:before {
	 			content: 'Show ';
 			}
 			button.share-list:before {
	 			content: 'Hide ';
 			}
 		</style>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
 	</head>
	<body>
				<h1 class="h3 text-center"><? echo $listCheck->the_('GLName'); ?></h1>
				<form method="POST" id="viewForm" class="collapsable">
					<div class="view-wrap ">
						<ul class="list"> 
							<? echo VLF_categories($theList); ?>						
						</ul>
					</div>
					<div class="control  fixed-bottom">
						<input  type="hidden" name="inList" id="inList" value="<? echo $listID;?>">
						<input  type="hidden" name="t" id="inList" value="<? echo $_SESSION['LISTlogged']['stoken'];?>">
						<button type="submit" value="Update List" name="subbed" class="btn btn-primary">Update</button>							<button type="submit" value="Edit List" class="btn btn-secondary" name="subbed">Edit list</button>
						<button type="submit" value="Edit Profile" name="subbed" class="btn btn-secondary">Edit Profile</button>
						<button type="submit" value="Dashboard" name="subbed" class="btn btn-secondary">Dashboard</button>						<button type="submit"  value="Logout" class="btn btn-secondary float-right" name="subbed">Logout</button>
						<!--
						<a href="dashboard.php?lid=xx" class="btn btn-secondary">Dashboard</a>
						<a href="editProfile.php" class="btn btn-secondary">Edit Profile</a>
						<a href="index.php?logout" class="btn btn-secondary">Log Out</a>-->
	 				</div>
 				</form>
	</body>
	<script type="text/javascript"  src="http://rmdesign.byethost32.com/sharelist/js/general.js"></script>
	<script type="text/javascript">
		var futbar  =  document.getElementsByClassName('fixed-bottom');
		if (futbar[0]){
			window.onresize = function (){
				document.body.style.paddingBottom = futbar[0].offsetHeight+'px';
			}
		}
	</script>
 </html>