<?
	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	if (isset($errs[$_GET['e']])){
		$errorMessage = $errs[$_GET['e']];
	}else {
		$errorMessage = 'That info does not exist or you do not have permission  to see it.';
	}
	$errorMessage = '<p class="text-center warning">'.$errorMessage.'</p>';
?>
<html>
	<head>
		<title>error</title>
 		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<link rel="stylesheet" href="design/css/edit_profile.css" media="screen">
	</head>
	<body>
		<div class="container">
		<? include ('includes/top_nav.php'); ?>
			<div class="section my-auto">
				<h1 class="h3 text-center bg-danger p-2 text-white ">Oops! something went wrong</h1>
				<? echo $errorMessage;?>
			</div>
		</div>
 	</body>
</html>
