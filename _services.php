<?
	session_start(); //continues user session
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
	if (!isset($_SESSION["LISTlogged"]['stoken']) || !isset($_GET['_t']) ||  $_GET['_t'] != $_SESSION["LISTlogged"]['stoken'] ){
 		echo 'Token mismatch error!!!'; 
		die;
	}
	
	// function ony works  if there is a user logged and a listID and output type has been passed
  	    if ( isset($_GET['eml'])  ){
		    $listSQL = "SELECT * FROM `GLists` WHERE `GLID` = :glid  AND  (`GLOwner` = :ownerid OR  `GLEditors` LIKE :theemail ) "; 
			$lists = new RMCDO(false, $listSQL, array( ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':glid'=>$_GET['glid'], ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
			$groups = explode(',', $_GET['eml']);
			$email_key = array('editors'=>'GLEditors', 'shares'=>'GLSubs' , 'all'=>'GLEditors' );
			$group_types = array('e'=>'editors', 's'=>'shares' , 'a'=> 'all' );
 		    foreach ($groups as $group){
			    if (!isset($group_types[$group])) {continue;} // if user input is not in white list, skip to next iteration

			    $aux_list = $group == 'a' ?  trim($lists->the_('GLSubs'),','): '' ;
			    $aux_list = $aux_list  ?  ','.$aux_list : '' ;
	  	    	$name = $group_types[$group];
				$spanClass = isset($_GET['s'.$group]) ?  $_GET['s'.$group] : 'remove-icon'  ;
				echo "			<div id=\"".$name."Emails\">\n".build_email_list(trim($lists->the_($email_key[$name]),',').$aux_list,$name , 4,$spanClass )."			</div>\n";
		    }
	    }
    
 //// build email lists for  dashboars list manager
    
    //sets user preferences
    if ( isset($_GET['pref'])  ){
	    			session_start();
	    			if (!isset($_SESSION['LISTlogged'])){die;}
 	  				if ($_GET['isChecked'] == '1') { $_SESSION['LISTlogged']['prefs'][$_GET['pref']] = 1;}  
	  				else{ unset($_SESSION['LISTlogged']['prefs'][ $_GET['pref']]); }
 	  				doQ('UPDATE `ListOwnersNew` SET `LOPrefs` =:p WHERE `LOID`=:id', array(':p'=>implode(',',array_keys($_SESSION['LISTlogged']['prefs'])), ':id'=>$_SESSION['LISTlogged']['UserID']));
 	  				
	}
	
	
	// perform Auto Update DB on View
	if ( isset($_GET['AUV'])){
			preg_match('/\[(\d*)\]/', $_GET['theName'], $matches);
			if (!isset($matches[1])) { echo "An error has occured:No ItemID."; die;}
 			$Q= 'UPDATE `GLItems` SET '; 
			$columns ='';
			$data = array(':id'=> $matches[1], ':list'=>$_GET['inList']);
 			if (isset($_GET['isNeeded'])) { 
 				$columns.= ' `Needed`= :isNeeded ';
				$data[':isNeeded'] = $_GET['isNeeded'];
			}
			if (isset($_GET['qty'])){ 
				$columns.= ($columns ? ',' : '').' `QTY`= :qty ';
				$data[':qty'] = $_GET['qty'];
			}
			$Q.=$columns.' WHERE `GLIID` = :id  AND `inGList`= :list';
			doQ( $Q, $data);
	}
  ?>