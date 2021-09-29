<?
 	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('-RMLCMS/processors/RMCMS.php');
	include_once  ('includes/rm_passwordFoos.php');
	include_once  ('includes/validation_foos.php');
	$oops='';//  must come before _process.php
 	include_once  ('includes/editProfile_process.php');
 ?><!DOCTYPE html>
<html>
	<head>
		<title><? echo $_SESSION["LISTlogged"]['username'] ?>'s Profile</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://bootswatch.com/4/cerulean/bootstrap.css" media="screen">
		<link rel="stylesheet" href="design/css/edit_profile.css" media="screen">
 	</head>
	<body>
 		<div class="container">
		<? include ('includes/top_nav.php'); ?>
   	    <form class="front-form form my-auto section" method="POST">
		<h6 class="text-center">Edit Profile</h6>
		<?echo $oops;?>
		<div class="row px-3">
		    <div class="form-group col-sm">
		      <label for="Firstname">First Name <? echo $form_errs->the_('Firstname','<span class="error">','</span>')?></label>
		      <input type="text" class="form-control" id="Firstname" name="Firstname" value="<? echo $form_vals->the_('Firstname')?>"  placeholder="Firstname" required="required">
		     </div>
		    <div class="form-group col-sm">
		      <label for="Lastname">Last Name <? echo $form_errs->the_('Lastname','<span class="error">','</span>')?></label>
		      <input type="text" class="form-control" id="Lastname" name="Lastname" value="<? echo $form_vals->the_('Lastname')?>"  placeholder="Lastname" required="required">
		     </div>
		</div>
	    <div class="form-group px-3">
	      <label for="email">Email <? echo $form_errs->the_('email','<span class="error">','</span>')?></label>
	      <input type="email" class="form-control" id="email" name="email" value="<? echo $form_vals->the_('email')?>"  placeholder="YourEmail@emails.com" required="required">
	      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
	    </div>
	    <div class="row px-3">
		    <div class="form-group col-sm">
		      <label for="Password1">New Password <? echo $form_errs->the_('Password1','<span class="error">','</span>')?></label>
		      <input type="password" class="form-control" id="Password1" name="Password1" >
		    </div>
		    <div class="form-group col-sm">
		      <label for="Password2">Confirm New Password <? echo $form_errs->the_('Password2','<span class="error">','</span>')?></label>
		      <input type="password" class="form-control" id="Password2" name="Password2" >
		    </div>
	    </div>
	    <div class="row px-3">
		    <div class="form-group col-sm">
		      <label for="currpass">Current Password <? echo $form_errs->the_('currpass','<span class="error">','</span>')?></label>
		      <input type="password" class="form-control" id="currpass"  name="currpass" >
			  <small class="form-text text-muted">For security purposes, your current password is required when making changes to your profile.</small>
		    </div>
	    </div>
		<div class="row px-3 ">
			<div class="col-sm">
				<input  type="hidden" name="t" id="t" value="<? echo $_SESSION['LISTlogged']['stoken'];?>">
				<button class="submit btn btn-primary  btn-block mb-3" name="act" value="update" >Update Profile</button></div>
			<div class="col-sm-4"><button class="btn btn-secondary btn-block mb-3" id="deleteBtn" name="act" value="delete">Delete Account</button></div>
		</div>
 	 </form>	
</div>
		 
	</body>
<script type="text/javascript">
	    var deleteBtn = document.getElementById('deleteBtn');
 		 deleteBtn.onclick = function(){
			 return confirm("Are you sure you wish to delete your account? The action is permanent and all your infomation will be lost. Click Cancel to abort. OK to continue.");
 		 }
</script>
</html>
