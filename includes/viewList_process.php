<?
if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){

		if ( !$autoUpdate){ 
			$notNeeded= isset($_POST['need']) ?  implode (',',$_POST['need']) : '0';
			$Q[]='UPDATE `GLItems` SET `Needed`=0 WHERE `GLIID` IN ('.$notNeeded.') AND `inGList`='.$_POST['inList'];

			if( $_SESSION["LISTlogged"]['prefs']['view']) {
				$Q[]='UPDATE `GLItems` SET `Needed`=1 WHERE `GLIID` NOT IN ('.$notNeeded.') AND `inGList`='.$_POST['inList']; }
			doQ( $Q, false);
		}
		if (isset($_POST['QT']) && !$autoUpdate){ 
			$stmnt =prepSTMT( 'UPDATE `GLItems` SET `QTY`= ? WHERE `GLIID`= ? AND `inGList`= ?');
			foreach ($_POST['QT'] as $qtID=>$qqt ){
				if ( $_POST['OQT'][$qtID] != $qqt && $qqt>0 ){$stmnt->execute (array($qqt,$qtID,$_POST['inList']));}
			}
		}
		include ('includes/submit_redirs.php');
}
?>