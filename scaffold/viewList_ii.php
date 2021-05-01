<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	$listCheck = "SELECT * FROM `GLists` WHERE `GLID` = :glid  AND (`GLOWner` = :ownerid  OR `GLEditors` LIKE  CONCAT ('%,',:theemail,',%') OR `GLSubs` LIKE  CONCAT ('%,',:theemail,',%') )";
	$listCheck = new RMCDO(false, $listCheck, array('glid'=>$_GET['glid'], 'theemail'=>$_SESSION["LISTlogged"]['email'], 'ownerid'=>$_SESSION["LISTlogged"]['UserID']));
	if (!$listCheck->the_('GLID')){ 
		var_dump($listCheck);
		echo "nope";
		die;
		//header('error.php?e=1');
	}
	$OPTs ='';
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
					<?
						$closeCat ='';
						$lastCat=false;
						while($thisItem = $theList->getRow()){
							 if( $lastCat != $thisItem['GLICat']):
								 echo $closeCat;
								 $lastCat = $thisItem['GLICat'];
					?>
						<li class="category">
							<span class="categoryName "><? echo $thisItem['GLICat']; ?> 
								<?php if (true): ?><i class="fa fa-angle-double-up btn btn-primary  pl-1 pr-1 pb-0 pt-0 float-right"  style="font-size: 110%;" onclick="accordionHandler(this,2,'ul.list-group','collapse')"></i><?php endif; ?>										
							</span>
							<ul class="list-group">
							<?endif;?>
									<li class="list-group-item">
										<?php if ($thisItem['notes']): ?>
										<span class="sleeve text" onclick="toggleClass(this, 'popmode');"><span class="real"><span><? echo $thisItem['notes'];?></span></span></span>
										<?php endif; ?>
										<?php if ($thisItem['image']): ?>
										<span class="sleeve image" onclick="toggleClass(this, 'popmode');"><span class="real"><img src="<? echo $thisItem['image'];?>"></span></span>
										<?php endif; ?>
										<?php if ($thisItem['QTY'] > 1): ?>
										<span class="quant">x<input name="QT[<? echo $thisItem['GLIID'];?>]" type="text" value="<? echo $thisItem['QTY'];?>"></span>
										<input name="OQT[<? echo $thisItem['GLIID'];?>]" type="hidden" value="2">
										<?php endif; ?>
										<label><input name="need[<? echo $thisItem['GLIID'];?>]" value="<? echo $thisItem['GLIID'];?>" type="checkbox" <? echo $thisItem['Needed'] ? ' CHECKED ': '';  ?>><? echo $thisItem['ItemName']; ?></label>
									</li>
									
									
<?
$closeCat= <<<EOT
							</ul>
						</li>
EOT;
$theList->nextRow();
}
echo $closeCat;
?>						
				</ul>
					</div>
						<div class="control  fixed-bottom">
							<input  type="hidden" name="inList" id="inList" value="1">
							<button type="submit" value="Update List" name="subbed" class="btn btn-primary">Update</button>			
							<a href="editList.php?lid=xx" class="btn btn-secondary">Edit list</a>
							<a href="dashboard.php?lid=xx" class="btn btn-secondary">Dashboard</a>
							<a href="editProfile.php" class="btn btn-secondary">Edit Profile</a>
							<a href="index.php?logout" class="btn btn-secondary">Log Out</a>
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