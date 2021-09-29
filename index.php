<?  
	
	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('-RMLCMS/processors/RMCMS.php');
	include_once  ('includes/rm_passwordFoos.php');
	include_once  ('includes/login_foos.php');
	$message='';
 	if( isset($_POST['logsub'])){ $message=sharelist_login('email','Password1','Login Denied.','dash.php');}
 	
 	?><!DOCTYPE html>
<html>
	<head>
		<title>ShareList: Sign In</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<link rel="stylesheet" href="design/css/front_form.css" media="screen">
 	</head>
	<body class="container h-100">
<div class="row h-100 justify-content-center align-items-center">
	 <form class="front-form form my-auto" action="index.php" method="POST">
			<h2 class="text-center">Login to ShareLIST</h2>
			<? echo $message ? '<p class="error text-center">'.$message."</p>\n" : ''; ?>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" required="required" id="email" placeholder="Enter email" name="email" >
			</div>
			<div class="form-group">
				<label for="Password1">Password</label>
				<input type="password" class="form-control" id="Password1" placeholder="Enter Password" name="Password1" required="required">
	    	</div>
			<button class="submit btn btn-primary  btn-block" name="logsub">Log In</button>
			<p class="mt-3 text-center">Don't have an account? <a href="sign_up.php">Create one here.</a></p>
	 </form>	
  </div>
  </body>
</html>

