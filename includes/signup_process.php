<?
	if(isset($_POST['signup_sub'])){ /////
		$form_vals = $_POST;
		$messages = rm_run_test($tests, $_POST);
		if  ($messages){
			foreach ($messages as $key=>$fv){ 
				if ($fv) {$form_vals[$key]='';} 
			}
		}else{
			//** encode pass
			$form_vals['Password1'] = rm_hash_pass  ($form_vals['Password1']);
			//** insert data;
			$data = array();
			$dkeys =array('LOPassword'=>'Password1' , 'LOName'=>'Firstname'  , 'LOLastName'=>'Lastname', 'LOEmail'=>'email');
			$sqlData= ':'.implode(', :', $dkeys);
			$sqlCols= '`'.implode('` , `', array_keys($dkeys)).'`';
			foreach ($dkeys as $dkey ){ $data[':'.$dkey] = $form_vals[$dkey]; }
			$SQL= "INSERT INTO `ListOwnersNew` ($sqlCols)  VALUES ($sqlData)";
			
			$q=new RMSO(false,$SQL);
			$q->_doQ($data) ;
  			//** start session as if logged in;
 			$oops=sharelist_login('email','Password1','<p class="error text-center">Opps, something went wrong.</p>','dash.php');
  		}
 	}
?>