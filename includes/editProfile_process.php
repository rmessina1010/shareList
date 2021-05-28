<?
	if(isset($_POST['act'])  &&  $_SESSION['LISTlogged']['stoken'] == $_POST['t']){ /////
		//delete lists and items data beloging to ID
 		if ($_POST['act'] === 'delete'){
	 		$delData= array(':u'=>$_SESSION["LISTlogged"]['UserID']);
	 		// get the users lists
			$s= new RMSO(false,'SELECT `GLID` FROM `GLists` WHERE `GLOwner` = :u' );
	 		$r=$s->_doQ($delData);
	 		$r=$s->STMNT()->fetchAll(PDO::FETCH_COLUMN);
 	 		//delete user
 	 		doQ( 'DELETE FROM `ListOwnersNew` WHERE `LOID` = :u' ,$delData);
 	 		if ($r){  //delete  any lists  might have been created by the user and all items in those lists
		 		doQ( 'DELETE FROM `GLists` WHERE `GLOwner` = :u' ,$delData);
		 		doQ( 'DELETE FROM `GLItems` WHERE `inGList` IN ('.implode(',',$r).')');
   	 		}
			//logout
  			header('Location:index.php?logout=1');
		}
		else{
			
	$tests = array( 'Firstname'=>array( 'req'=> 'Please tell us your name.', 'words'=> 'Inavlid name.'), 'Lastname'=>array('req'=> 'Please tell us your last name.','words'=> 'Inavlid name.')  );
 	if( $_POST['email'] && $_SESSION["LISTlogged"]['email']  != $_POST['email']){
	 	$tests['email'] = array(
				'_lim'=>true,
				'email'=> 'The email provided is not valid.',
				'exists'=> array('err'=>'That account already exists. Use a different email.', 'args'=>array('t'=>'ListOwnersNew', 'c'=>'LOEmail'),   'x'=>false));
	}
 	if( $_POST['Password1'] || $_POST['Password2'] || $_POST['currpass']){
  		$q=new RMSO('`ListOwnersNew`', '*' ,'`LOID` = :u' );
 		$r=$q->_doQ(array(':u'=>$_SESSION["LISTlogged"]['UserID']));
 		$r=$r->fetch(PDO::FETCH_ASSOC);
		$tests = array_merge($tests,	
			array('Password1'=>array(
 						'req'=> 'You must create a password for your account.' 
 						),
 				'Password2'=>array(
						'_lim'=>true,
						'req'=>   'You must confirm your password.',
						'match'=> array('err'=>'Confirmation mismatch.', 'args'=>$_POST['Password1']),
						),
				'currpass'=>array(
						'_lim'=>true,
						'req'  =>   'You must enter your current password.',
						'pass' =>   array('err'=>'Wrong password.', 'args'=>$r['LOPassword']),
				)
  			)
  		);
   	}
 			$form_vals = $_POST;
			$messages  = rm_run_test($tests, $_POST);
			if  ($messages){
				// reset erred values ($form_vals)
 				$df['email']  = $_SESSION["LISTlogged"]['email']; // part of an array of custon default values
				foreach ($messages as $key=>$fv){ 
					if ($fv) {$form_vals[$key]= isset($df[$key]) ? $df[$key] : '';} 
				}
			}elseif  (isset ($_SESSION["LISTlogged"]['UserID'])){
				
				$dkeys =array(  'LOName'=>'Firstname'  , 'LOLastName'=>'Lastname');//maps
				//** encode pass
				if ($form_vals['Password1']) {
					$form_vals['Password1'] = rm_hash_pass  ($form_vals['Password1']);
					$dkeys['LOPassword'] = 'Password1';
				}
				if ($form_vals['email'] != $_SESSION["LISTlogged"]['email']) {
					$dkeys['LOEmail'] = 'email';
				}
				//** update data;
				$data = array();
				$SQL= 'UPDATE `ListOwnersNew` SET ';
				$comma ='';
				foreach ($dkeys as $col => $dfield){
 					$data[':'.$dfield] = $form_vals[$dfield];
					$SQL.= " $comma  `$col` = :$dfield ";
					$comma=',';
				}
				$data[':id'] = $_SESSION["LISTlogged"]['UserID'];
				$SQL.= ' WHERE `LOID` = :id ';
 				$q=new RMSO(false,$SQL);
 				if ( $q->_doQ($data)){				
					$oops = '<p class="text-success text-center">Profile updated.<p>';
 					$_SESSION["LISTlogged"]['email'] = $form_vals['email'] ;
 					$_SESSION['LISTlogged']['username']=$form_vals['Firstname'];
  				}
	  		}
	  		$form_vals = new RMCDO(array($form_vals));
	  		$form_errs = new RMCDO(array($messages));
	 	}
 	 }else{
 	    $form_vals = new RMCDO(false, 'SELECT `LOName` AS `Firstname`, `LOLastName` AS `Lastname`, `LOEmail` AS `email` FROM `ListOwnersNew` WHERE `LOID` = :id', array(':id'=>$_SESSION["LISTlogged"]['UserID']));
	  	$form_errs = new RMCDO(array());
	 }
?>