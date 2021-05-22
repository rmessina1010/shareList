<?
	if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){// process form
		

	   if ($_POST['subbed'] === $updateBttnVal){
		//var_dump($_POST['theNeed']); 
			 $Q=false;
			 $listTitle =trim(strip_tags($_POST['listTitle']));
			 if ($listName !== $listTitle && $listTitle !== '' ){
			   doQ('UPDATE `GLists` SET `GLName` =:n WHERE `GLID`=:id AND `GLOwner`=:own' , array(':n'=>$listTitle,':id'=> $GLID,':own'=>$_SESSION["LISTlogged"]['UserID'])) ;  //echo 'change list name to'.$_POST['listTitle'];
			   $listName = $listTitle;
 			 }
			 if (isset($_POST['toDelete']) && $_POST['toDelete']){
 			   doQ('DELETE FROM `GLItems` WHERE `GLIID` IN('.$_POST['toDelete'].')');//echo 'delete these items:'.$_POST['toDelete'];
			 }
	 		 $catNegOffset=0;
			 $itemOrder=1;
			 $theCategory ='';
			 if (isset($_POST['itemID']) && $_POST['itemID']){
				 foreach ($_POST['itemID'] as $itemKey=>$itemKeyVal){
				 	$coordinatedKey=$itemKey-$catNegOffset;
				 	$qt= ($_POST['qt'][$coordinatedKey] > 1) ? $_POST['qt'][$coordinatedKey] : 1; // prevent quantities of less than 1
				 	if ($itemKeyVal === 'NULL'){ 
				 		$theCategory = trim(strip_tags($_POST['category'][$itemKey])); // remember category name
				 		$catNegOffset++; 
				 		continue;
				 	}
				 	if ( trim($_POST['itemName'][$coordinatedKey]) ===''){ continue;}  // no blank named items are allowed
				 	/// sanitize image, name, comment data
				 		$itemName = trim(strip_tags($_POST['itemName'][$coordinatedKey]));
				 		$theImg= trim(strip_tags(rm_sani_attrs(rm_scrub_tags($_POST['img'][$coordinatedKey]))));
				 		$theComm= rm_sani_attrs(rm_scrub_tags($_POST['comm'][$coordinatedKey]));;
				 	///
				 	if ($itemKeyVal === '?'){ 
				 		doQ('INSERT INTO  `GLItems` (`inGList`,`GLICat`,`ItemName`,`Needed`,`QTY`,`image`,`notes`,`GLIOrd`) VALUES (:in,:cat,:name,:need,:qty,:img, :notes,:ord)' , array(':in'=>$GLID,':cat'=>$theCategory, ':name'=>$itemName,':need'=>$_POST['theNeed'][$coordinatedKey],':qty'=>$qt,':img'=>$theImg,':notes'=>$theComm,':ord'=>$coordinatedKey )) ;//echo "$theCategory: insert new item with order =$itemOrder needed:{$_POST['theNeed'][$coordinatedKey]}<br>";
				 	}
				 	else{
				 		doQ('UPDATE  `GLItems` SET `inGList` = :in ,`GLICat` = :cat ,`ItemName` =  :name,`Needed` = :need, `QTY` = :qty,`image` = :img ,`notes` = :notes, `GLIOrd` =:ord WHERE `GLIID` = :id ' , array(':in'=>$GLID,':cat'=>$theCategory, ':name'=>$itemName,':need'=>$_POST['theNeed'][$coordinatedKey],':qty'=>$qt,':img'=>$theImg, ':notes'=>$theComm ,':ord'=>$coordinatedKey, ':id'=> $itemKeyVal)) ; //echo "$theCategory: edit item id=$itemKeyVal with order =$itemOrder needed:{$_POST['theNeed'][$coordinatedKey]} <br>";
				 	}
				 	$itemOrder++;
				 }
			}
		}
		include ('includes/submit_redirs.php');
	}
?>