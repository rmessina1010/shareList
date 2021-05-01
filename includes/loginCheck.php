<?php
	session_start();
 	//include ('setPefs.php');
	if ( isset($_GET['logout'])){  
			/** save preferences;
			$prefs=$next='';
			if (isset($_SESSION["LISTlogged"]['prefs']) && is_array($_SESSION["LISTlogged"]['prefs'])){
				foreach ($_SESSION["LISTlogged"]['prefs'] as $prefKey=>$prefVal){
				 		if ($prefVal) { $prefs.=  $next.$prefKey; $next= $prefs ? ',' :'';}
 				}
 				$u=new RMSO('ListOwners','`LOPrefs` =:pref','`LOID`= :id',false,array('mode'=>'u') );
				$u->_doQ( array('pref'=>$prefs, 'id'=>$_SESSION["LISTlogged"]['UserID']));
			}
			*/
  		session_destroy();
	}
	$currPage =strtok(basename($_SERVER['REQUEST_URI']), '?');
	if (!isset($_SESSION["LISTlogged"]) && ( $currPage !=='index.php' &&  $currPage !== 'sign_up.php')){ 
 		header('Location: index.php') or die("Please log in."); 
	}
	elseif ( isset($_SESSION["LISTlogged"]) && ( $currPage=='sharelist' || $currPage == 'index.php' ||  $currPage == 'sign_up.php')){ 
 		header('Location: dash.php') or die("Error."); 
	}
	
  ?>