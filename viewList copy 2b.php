<?
include_once('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
include_once('../-RMCMS2_5/processors/RMengine2_5.php');
$listCheck = "SELECT * FROM GLists WHERE GLID = :glid  AND (GLOWner = :ownerid  OR GLEditors LIKE  CONCAT ('%,',:theemail,',%') OR GLEditors LIKE LIKE  CONCAT ('%,',:theemail,',%') )";
$listCheck = new RMDO(false, $listCheck, array('glid' => 1, 'theemail' => 2, 'ownerid' => 3));
if (!$listCheck) {
	header('error.php?e=1');
}
$item_sql = 'SELECT * FROM GLItems WHERE GLID = :glid ' . $OPTs . ' ODER BY GLIDOrd ASC';
?>
<!DOCTYPE html>
<html>

<head>
	<title><? echo $listCheck->the(''); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="http://rmdesign.byethost32.com/lists/parts/css/main.css">
	<link type="text/css" rel="stylesheet" href="../design/css/shared.css">
	<link type="text/css" rel="stylesheet" href="http://rmdesign.byethost32.com/lists/parts/css/modal.css">

	<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
	<style type="text/css" media="screen">
		.list-group-item label input {
			margin-right: .33em;
		}

		.list-group-item label {
			display: block !important;
			padding: .5em;
			margin: 0 !important;
			overflow: hidden;
		}

		.quant {
			padding: .25em;
		}

		.sleeve {
			float: right;
			max-height: 2em;
		}

		.real {
			z-index: 4000
		}

		.row {
			margin: 0;
			padding: 0;
			list-style: none;
		}

		form.collapsable .category {
			background: #eee;
			padding-bottom: .67em;
		}

		form.collapsable .category:nth-child(2n+1) {
			background: #bbb;

		}

		.list-group-item {
			padding: 0 !important;
			font-size: 70%;
		}

		.list-group-item:hover,
		.categoryName i:hover {
			background: var(--teal);
			color: #fff;
		}

		.categoryName i:hover {
			border-color: #09af7b;
		}

		textarea.share-list {
			display: block;
			width: calc(100% - 1.34em);
			margin: 1em .67em;
			padding: .2em .5em;
			column-count: 3;
			-webkit-column-count: 3;
			line-height: 2;
		}

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
	<h1 class="h3 text-center"><? echo $listCheck->the(''); ?></h1>
	<form method="POST" id="viewForm" class="collapsable">
		<div class="view-wrap ">
			<ul class="list">
				<?
				$closeCat = '';
				$lastCat = false;
				while ($thisItem = $theList->getRow()) {
					if ($lastCat != $thisItem['ListCat']) :
						echo $closeCat;
						$lastCat = $thisItem['ListCat'];
				?>
						<li class="category">
							<span class="categoryName "><? echo $thisItem['ListCat']; ?>
								<?php if ($_SESSION['logged']['prefs']['collapse']) : ?><i class="fa fa-angle-double-up btn btn-primary  pl-1 pr-1 pb-0 pt-0 float-right" style="font-size: 110%;" onclick="collapse(this,3)"></i><?php endif; ?>
							</span>
							<ul class="list-group">
							<? endif; ?>
							<li class="list-group-item">
								<?php if ($thisItem['comment']) : ?>
									<span class="sleeve text" onclick="toggleClass(this, 'popmode');"><span class="real"><span><? echo $thisItem['comment']; ?></span></span></span>
								<?php endif; ?>
								<?php if ($thisItem['img']) : ?>
									<span class="sleeve image" onclick="toggleClass(this, 'popmode');"><span class="real"><img src="<? echo $thisItem['img']; ?>"></span></span>
								<?php endif; ?>
								<?php if ($thisItem['qty']) : ?>
									<span class="quant">x<input name="QT[<? echo $thisItem['LIID']; ?>]" type="text" value="<? echo $thisItem['qty']; ?>"></span>
									<input name="OQT[<? echo $thisItem['LIID']; ?>]" type="hidden" value="2">
								<?php endif; ?>
								<label><input name="need[<? echo $thisItem['LIID']; ?>]" value="<? echo $thisItem['LIID']; ?>" type="checkbox"><? echo $thisItem['ListItemName']; ?></label>
							</li>


						<?
						$closeCat = <<<EOT
							</ul>
						</li>
EOT;
					}
					echo $closeCat;
						?>
							</ul>
		</div>
		<div class="control  fixed-bottom">
			<input type="hidden" name="inList" id="inList" value="1">
			<button type="submit" value="Update List" name="subbed" class="btn btn-primary">Update List</button>
			<a href="editList.php?lid=xx" class="btn btn-secondary">Edit list</a>
			<a href="dashboard.php?lid=xx" class="btn btn-secondary">Dashboard</a>
			<a href="editProfile.php" class="btn btn-secondary">Edit Profile</a>
			<a href="index.php?logout" class="btn btn-secondary">Log Out</a>
		</div>
	</form>
</body>
<script type="text/javascript" src="http://rmdesign.byethost32.com/lists/parts/js/general.js"></script>
<script type="text/javascript">
	var futbar = document.getElementsByClassName('fixed-bottom');
	if (futbar[0]) {
		window.onresize = function() {
			document.body.style.paddingBottom = futbar[0].offsetHeight + 'px';
		}
	}
</script>

</html>