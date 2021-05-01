<?
	function rm_login(array $args = array()){
		$defaults = array (
			'message'=>'Log in failed.',
			'uTable'=>'`Users`',
			'uTableCols'=>'*',
			'uTableWhere'=>'`Username` = :u',
			'loginData'=>array(':u'=>$_POST['username']),
			'passCol'=>'Password',
			'passKey'=>'password',
			'sessionName'=>'logged',
			'sessionVars'=>array('UserID'=>'LOID'),
			'stoken'=>'stoken',
			'loc'=>false
		);
		extract($defaults);
		extract($args);
 		$q=new RMSO($uTable,$uTableCols,$uTableWhere);
 		$r=$q->_doQ($loginData);
 		$row=  $r->fetch(PDO::FETCH_ASSOC) ;
 		//enables sesssion data 
    	if ($row && (rm_check_pass($_POST[$passKey],$row[$passCol]) ) ){
			//set session params
			foreach ($sessionVars as $vkey=>$dbcol){
				if (is_array($dbcol)){
					$dbcol = array_values($dbcol);
					$arrFlag= (is_array($dbcol[0])  && is_array($dbcol));
					$subKeys= $arrFlag ? $dbcol[0] :  is_string($dbcol[0]) ? explode(',', $dbcol[0]) : array();
					$valueFlag = isset($dbcol[1])  ? $dbcol[1] : 1;
					foreach ($subKeys as $subKey=>$subVal){ 
						if ($arrFlag){ 
							if(isset($row[$subVal])) { $_SESSION[$sessionName][$vkey][$subKey] = $row[$subVal] ;}
					    }else{
						   $_SESSION[$sessionName][$vkey][$subKey] = $valueFlag;
					    }
						
					}
				}
				elseif(isset($row[$dbcol])){
						 $_SESSION[$sessionName][$vkey]= $row[$dbcol];
				}
			}
	    	//create secure token, if enabled
			if($stoken){ $_SESSION[$sessionName][$stoken]=md5(uniqid()); }
 			//redirect 
  			if ($loc) { header('Location:'.$loc);}
  			return false;
		}
		return $message;
  	}
  	
 	 	function sharelist_login($logkey,$passkey,$message,$loc=false){
 		$q=new RMSO('`ListOwnersNew`','*','`LOEmail`= :u' );
 		$r=$q->_doQ(array(':u'=>$_POST[$logkey]));
 		$row=  $r->fetch(PDO::FETCH_ASSOC) ;
 		//enables sesssion data 
    	if ($row && (rm_check_pass($_POST[$passkey],$row['LOPassword']) ) ){
 			$_SESSION['LISTlogged']['UserID']=$row['LOID'];
			$_SESSION['LISTlogged']['username']=$row['LOName'];
			$_SESSION['LISTlogged']['email']=$row['LOEmail'];
			$_SESSION['LISTlogged']['stoken']=md5(uniqid());
			//load preferences
			$prefs =trim($row['LOPrefs']);
			$prefs= $prefs  ? explode(",",$prefs ) : array();
 			foreach ($prefs as $pref){ $_SESSION['LISTlogged']['prefs'][$pref] = 1;}
 			//redirect 
  			if ($loc) { header('Location:'.$loc);}
  			return false;
		}
		return $message;
  	}
 
 ?>