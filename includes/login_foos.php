<?
	function sharelist_login($emkey,$message,$passkey,$loc){
 		$q=new RMSO('`ListOwnersNew`','*','`LOEmail`= :u' );
 		$r=$q->_doQ(array(':u'=>$_POST[$emkey]));
 		$row=  $r->fetch(PDO::FETCH_ASSOC) ;
 		//enables sesssion data 
    	if ($row && (rm_check_pass($_POST[$passkey],$row['LOPassword']) ) ){
 			$_SESSION["LISTlogged"]['UserID']=$row['LOID'];
			$_SESSION["LISTlogged"]['username']=$row['LOName'];
			$_SESSION["LISTlogged"]['email']=$row['LOEmail'];
			//load preferences
			$prefs= explode(",",$row['LOPrefs']);
 			foreach ($prefs as $pref){ $_SESSION["LISTlogged"]['prefs'][$pref] = 1;}
 			//redirect 
  			header('Location:'.$loc);
		}
		return $message;
  	}
 ?>