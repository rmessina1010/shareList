<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('-RMLCMS/processors/RMCMS.php');
	include_once  ('includes/rm_passwordFoos.php');
	include_once  ('includes/validation_foos.php');
	include_once  ('includes/login_foos.php');
	
	$form_vals =  array('Firstname'=>'',  'Lastname'=>'', 'email'=>'');
	$tests = array(
			'Password1'=>array(
 				'req'=> 'You must create a password for your account.' 
 				),
			'Password2'=>array(
				'lim'=>true,
				'req'=>   'You must confirm your password.',
				'match'=> array('err'=>'Confirmation mismatch.', 'args'=>$_POST['Password1']),
 				),
			'email'=>array(
				'lim'=>true,
 				'req'=> 'You must provide an email for your account.',
				'email'=> 'The email provided is not valid.',
				'inDB'=> array('err'=>'That account already exists. Use a different email.', 'args'=>array('t'=>'ListOwnersNew', 'c'=>'LOEmail'), 'x'=>false),
  				),
			'Firstname'=>array(
 				'req'=> 'Please tell us your name.',
 				'words'=>'Invalid name!'
   			),
			'Lastname'=>array(
 				'req'=> 'Please tell us your last name.',
 				'words'=>'Invalid name!'
   			) 
 	);
 	
	$oops='';//  must come before _process.php
	include('includes/signup_process.php');
	
	$form_vals = new RMCDO(array($form_vals));
	$form_errs = new RMCDO(array($messages));
 ?><!DOCTYPE html>
 <html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"  >
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>ShareList: Sign Up</title>
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen"  >
		<link rel="stylesheet" href="design/css/front_form.css" media="screen">
		
 	</head>
	<body class="container h-100">
<div class="row h-100 justify-content-center align-items-center">
 	<form class="front-form form my-auto" method="POST">
		<h2 class="text-center">Sign up for ShareLIST</h2>
		<? echo $oops; ?>
	    <div class="form-group">
	      <label for="Firstname">First Name <? echo $form_errs->the_('Firstname','<span class="error">','</span>')?></label>
	      <input type="text" class="form-control" id="Firstname" name="Firstname" value="<? echo $form_vals->the_('Firstname')?>"  placeholder="Firstname" required="required">
	     </div>
	    <div class="form-group">
	      <label for="Lastname">Last Name <? echo $form_errs->the_('Lastname','<span class="error">','</span>')?></label>
	      <input type="text" class="form-control" id="Lastname" name="Lastname" value="<? echo $form_vals->the_('Lastname')?>"  placeholder="Lastname" required="required">
	     </div>
	    <div class="form-group">
	      <label for="email">Email <? echo $form_errs->the_('email','<span class="error">','</span>')?></label>
	      <input type="email" class="form-control" id="email" name="email" value="<? echo $form_vals->the_('email')?>"  placeholder="YourEmail@emails.com" required="required">
	      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
	    </div>
	    <div class="form-group">
	      <label for="Password1">Choose Password <? echo $form_errs->the_('Password1','<span class="error">','</span>')?></label>
	      <input type="password" class="form-control" id="Password1"  name="Password1"required="required">
	    </div>
	    <div class="form-group">
	      <label for="Password2">Confirm Password <? echo $form_errs->the_('Password2','<span class="error">','</span>')?></label>
	      <input type="password" class="form-control" id="Password2" name="Password2" required="required" >
	    </div>
		<button class="submit btn btn-primary  btn-block" name="signup_sub">Sign up</button>
		<p class="mt-3 text-center">Already have an account? <a href="index.php">Log in here.</a></p>
	 </form>	
    </div>
 </body>
</html>
