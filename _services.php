<?
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
	session_start(); //continues user session
    if(isset($_SESSION["LISTlogged"]['UserID']) && isset($_GET['glid']) ){// function ony works  if there is a user logged and a listID and output type has been passed
	    if ( isset($_GET['eml'])  ){
		    $listSQL = "SELECT * FROM `GLists` WHERE `GLIID` = :glid  AND  (`GLOwner` = :ownerid OR  `GLEditors` LIKE :theemail ) "; 
			$lists = new RMCDO(false, $listSQL, array( ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':glid'=>$_GET['glid'], ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
			$groups = explode(',', $_GET['eml']);
			$email_key = array('editors'=>'GLEditors', 'sharees'=>'GLSubs' , 'all'=>'GLEditors' );
			$group_types = array('e'=>'editors', 's'=>'sharees' , 'a'=> 'all' );
		    foreach ($groups as $group){
			    if (!isset($group_types[$group])) {continue;} // if user input is not in white list, skip to next iteration
			    $aux_list = $group == 'a' ?  trim($lists->the_('GLSubs'),','): '' ;
			    $aux_list = $aux_list  ?  ','.$aux_list : '' ;
	  	    	$name = $group_types[$group];
				$spanClass = isset($_GET['s'.$group]) ?  $_GET['s'.$group] : 'remove-icon'  ;
				echo "			<div id=\"$name-emails\">\n".build_email_list(trim($lists->the_($email_key[$name]),',').$aux_list,$name , 4,$spanClass )."			</div>\n";
		    }
	    }
	    
	    
	    
	    
	    
	    
	    if ( isset($_GET['tml'])  ){
		    $listSQL =array(
			    array('GLEditors'=>"afakeemail{$_GET['glid']}@me.com, another{$_GET['glid']}@me.com,third{$_GET['glid']}@ma.com,somethinelse{$_GET['glid']}@we.com", 'GLSubs'=>"afakeemail{$_GET['glid']}@me.com, another{$_GET['glid']}@me.com,third{$_GET['glid']}@ma.com,somethinelse{$_GET['glid']}@we.com"));
 			$lists = new RMCDO($listSQL);
			$groups = explode(',', $_GET['tml']);
			$email_key = array('editors'=>'GLEditors', 'sharees'=>'GLSubs' , 'all'=>'GLEditors' );
			$group_types = array('e'=>'editors', 's'=>'sharees' , 'a'=> 'all' );

		    foreach ($groups as $group){
			    if (!isset($group_types[$group])) {continue;} // if user input is not in white list, skip to next iteration

			    $aux_list = $group == 'a' ?  trim($lists->the_('GLSubs'),','): '' ;
			    $aux_list = $aux_list  ?  ','.$aux_list : '' ;
	  	    	$name = $group_types[$group];
				$spanClass = isset($_GET['s'.$group]) ?  $_GET['s'.$group] : 'remove-icon'  ;
				echo "			<div id=\"".$name."Emails\">\n".build_email_list(trim($lists->the_($email_key[$name]),',').$aux_list,$name , 4,$spanClass )."			</div>\n";
		    }
	    }
    
    }
 ?>