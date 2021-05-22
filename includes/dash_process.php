<?
	if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){
		$SQL  = false;
 		$data[':editors']	= ','.implode(',', $_POST['editors']).',';
 		$data[':shares']		= ','.implode(',', $_POST['shares']).',';
   		$glistname ='';
  		if ($_POST['listNewName']){
	  		$glistname 			= ' `GLName` = :listname, ';
 			$data[':listname'] 	= $_POST['listNewName'];
  		}
 		/// UPDATE LIST
		if ($_POST['subbed'] == 'Manage_Update'){
  			$data[':glid'] = $_POST['manageList'];
 			$SQL = 'UPDATE `GLists` SET '.$glistname.' `GLEditors` = :editors, `GLSubs` = :shares WHERE `GLID` = :glid';
		}
		// ADD LIST
		if ($_POST['subbed'] == 'Manage_Add'  &&  $_POST['listNewName']){
 			$data[':owner']		= $_SESSION["LISTlogged"]['UserID'];
 			$SQL = 'INSERT INTO `GLists` (`GLOwner`, `GLName`, `GLEditors`, `GLSubs`) VALUES (:owner, :listname, :editors, :shares)';
		}
		// DELETE LIST
		if ($_POST['subbed'] == 'Manage_Delete' ){
  			$SQL[] = 'DELETE FROM `GLists` WHERE  `GLID` = :id';
  			$SQL[] = 'DELETE FROM `GLItems` WHERE  `inGList` = :id ';
  			$data = array(':id'=>$_POST['manageList']);
		}
		if ($SQL){ doQ($SQL, $data); }
		include ('includes/submit_redirs.php');
	}
?>