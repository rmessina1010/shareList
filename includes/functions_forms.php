<?
    function ELF_category_open($item, $isMaster = false){
	    $id = $isMaster ? ' id ="'.$isMaster.'" ' :'';
   		$res=<<<EOT
 	 			<li class="category  list-group-item" $id>
					<div class="colapse cat-header row no-gutter">
 						<label class="col d-flex cat-label"><span class="mr-2">Category:</span><input type="text" name="category[]" value="$item" class="form-control"><input type="hidden" name="itemID[]" value="NULL" ></label>
 						<span class="control col-lg-5 pb-2 ">
							<button class = "btn btn-outline-secondary " type="button" onclick="addBetween(this.parentNode.parentNode)">Add item</button> 
							<button type="button" class="toggle btn btn-outline-secondary" onclick="checkToggle(this,3)">check all</button>
							<span class="d-inline-block">
								<i class="fa fa-angle-double-down btn btn-outline-primary  m-1" onclick="accordionHandler(this,4,'ul.list-group','collapse')"></i>
								<i class="fa fa-arrows btn btn-outline-primary  m-1" onclick="slide(this,4,'category')"></i>
								<i class="fa fa-times-circle btn btn-outline-primary  m-1" onclick="if (confirmSub()) { deleteMe(this,4); }" ></i>
							</span>
						</span> 
					</div>
     <ul class="list-group collapse">
EOT;
     return $res;
   }

   function ELF_item($item,$isMaster = false){
	   $id = $isMaster ? ' id ="'.$isMaster.'" ' :'';
       if ($isMaster && !is_array($item) ){
	       $item = array ( 'Needed' => 0,  'ItemName'=>'', 'QTY'=> 1, 'image'=>'',  'notes'=>'', 'GLIID'=>'?', 'GLICat'=>'?' );
       }
       if (!is_array($item) ){ return;}
 	   $checked =  $item['Needed']     ? ' CHECKED ' : '';
 	   $res=<<<EOT
        <li class="list-group-item lgi" $id>
	        <div class="row">
	            <div class="col row no-gutters" >
		            <div class="d-flex col row no-gutters">
			            <span class="col-md row row no-gutters">
	                        <label class="pl-2 item-label">
	                        	<input type="checkbox"  class="form-check-input d-inline itembox" value="{$item['GLIID']}" name="theItem[]" onclick="itemAdjuster(this)"$checked>Item: 
								<input type="hidden" name="theNeed[]" value="{$item['Needed']}">
								<input type="hidden" name="itemID[]" value="{$item['GLIID']}">
								<input type="hidden" name="category[]" value="{$item['GLICat']}"> 
	                        </label>
	                        <input class="form-control col mb-2 ml-2" type="text"  name="itemName[]" value="{$item['ItemName']}">
			            </span>
	                    <label class="ml-2"> Qty.: <input class="form-control d-inline-block ml-2 num-ctrl" type="number" name="qt[]" min="0" value="{$item['QTY']}">
	                    </label>
	                </div>
	                <span  class="pl-1 item-ctrl">
	                    <i class="fa fa-arrows  btn btn-outline-primary  m-1" onclick="slide(this,4,'lgi')"></i>
						<i class="fa fa-times-circle btn  btn-outline-primary  m-1" onclick="if (confirmSub()) { deleteMe(this,4); }"></i>
	                </span>
	             </div>
	        </div> 
	        <div class="row">
	             <div class="col-sm d-flex mb-2">
	                 <label class="mr-2">Image:</label> <input type="text" class="form-control" name="img[]"value="{$item['image']}">
	            </div>
	            <div class="col-sm d-flex">
	                 <label class="mr-2">Notes:</label><textarea class="form-control" rows="1" name="comm[]">{$item['notes']}</textarea> 
	            </div>
	        </div>
        </li>
EOT;     
return $res;   
}
  
   function ELF_colUL($data){
   		$res='		    <ul class="columnade list-group">'."\n";
   		if ($data){ $res.= ELF_categories($data);}
   		$res.="\n</ul>\n";
   		return $res;  
   }

   function ELF_categories($theList){
   		$res='';
		$closeCat ='';
		$lastCat=false;
		//$acc =(isset($_SESSION['LISTlogged']['prefs']['acc']) && $_SESSION['LISTlogged']['prefs']['acc']);
		while($thisItem = $theList->theRow()){
			if( $lastCat != $thisItem['GLICat']){
				$res.= $closeCat;
				$lastCat = $thisItem['GLICat'];
				$res.=ELF_category_open($thisItem['GLICat'], false);
			}
			$res.= ELF_item($thisItem);
			$closeCat= <<<EOT
							</ul>
						</li>
						
EOT;
			//$theList->nextRow();
		}
		$res.= $closeCat;
		return $res;
	}

   function VLF_category_open($item, $col = true){
	   $col = $col ?  'collapse' : '';
	   $collbutton	= $col ? "<i class=\"fa fa-angle-double-down btn btn-primary  pl-1 pr-1 pb-0 pt-0 float-right\"  style=\"font-size: 110%;\" onclick=\"accordionHandler(this,2,'ul.list-group','collapse')\"></i>" : '';
   		$res=<<<EOT
									<li class="category">
										<span class="categoryName ">$item
										$collbutton										
										</span>
										<ul class="list-group $col">
EOT;
     return $res;
   }
   

   function VLF_item($thisItem){
	   if (!is_array($thisItem)){return '';}
	   $notes = ($thisItem['notes']) ?  "<span class=\"sleeve text\" onclick=\"toggleClass(this, 'popmode');\"><span class=\"real\"><span>{$thisItem['notes']}</span></span></span>" :'';
	   $images = ($thisItem['image']) ?  "<span class=\"sleeve image\" onclick=\"toggleClass(this, 'popmode');\"><span class=\"real\"><img src=\"{$thisItem['image']}\"/></span></span>" :'';
	   $isChecked =  !$thisItem['Needed'] ? ' CHECKED ': '';
	   $autoUpdate = (isset($_SESSION['LISTlogged']['prefs']['auto'] ) && $_SESSION['LISTlogged']['prefs']['auto'])  ?
	   				 'onchange="AUView(this)"' : '';
	   				 
	   $qty	=  ($thisItem['QTY'] > 1) ?  "<span class=\"quant\">x<input name=\"QT[{$thisItem['GLIID']}]\" type=\"text\" value=\"{$thisItem['QTY']}\" $autoUpdate></span>
										<input name=\"OQT[{$thisItem['GLIID']}]\" type=\"hidden\" value=\"{$thisItem['QTY']}\">":'';
										
	   $res=<<<EOT
									<li class="list-group-item">
										$notes
										$images
										$qty
 										<label><input name="need[{$thisItem['GLIID']}]" value="{$thisItem['GLIID']}" type="checkbox" $isChecked $autoUpdate>{$thisItem['ItemName']}</label>
									</li>
EOT;     
return $res;   
}
   
   function close_F($left,$right=''){
	   $right  = ($right && is_string($right)) ? <<<EOT
	<div class="button-sect  d-flex">
$right
	</div>
EOT	   
	   			: '';
	   			
	   $res=<<<EOT
<div class="control fixed-bottom pt-2" style="background: #ccc"><div class="container pt-1 pb-1"><div class="control-inner">
	<div class="button-sect  d-flex">
$left
 	</div>
$right
</div></div></div>     
 </form>
EOT;

return $res;
}

function close_ELF($data){
	$right=<<<EOT
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="viewlist.php?glid=17&amp;acc=1" >View List</a>
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="viewlist.php?glid=17&amp;acc=1" >Dashboard</a>
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="index.php?logout" >Log Out</a>
EOT;
	$left=<<<EOT
		<button type="button" onclick="addBefore(this.parentNode)" class="btn btn-block m-1  btn-outline-secondary btn-sm">Add Category</button>
		<button type="button" onclick="checkToggle(this,2,'.toggle')" class="btn btn-block m-1  btn-outline-secondary btn-sm">Check All</button>
		<button class="btn btn-block m-1 btn-primary btn-sm" type="submit" name="subbed" value="subbed" onclick="this.className='hide';" >Update</button>
		<input id="toDelete" type="hidden" name="toDelete">
EOT;
//replace info

	return  close_F($left, $right);
}   

function closeVLF($listID,$sub){
	$sub = $sub ? '<button type="submit" value="Update List" name="subbed" class="btn btn-primary"> Update List</button>' :'';			
	$res =<<<EOT
						<div class="control  fixed-bottom">
							<input  type="hidden" name="inList" id="inList" value="$listID">
							$sub
							<button type="submit" value="Edit List" name="subbed" class="btn btn-secondary">Edit List</button>
							<button type="submit" value="Controls" name="subbed" class="btn btn-secondary">Dashboard</button>
							<a href="index.php?logout" class="btn btn-secondary">Log Out</a>
	 					</div>
 				</form>
	
EOT;
return $res;
}   


function closeVLF_alt($listID,$sub){
	$sub = $sub ? '<button type="submit" value="Update List" name="subbed" class="btn btn-primary"> Update List</button>' :'';			
	$left =<<<EOT
							<input  type="hidden" name="inList" id="inList" value="$listID">
							$sub
							<button type="submit" value="Edit List" name="subbed" class="btn btn-secondary">Edit List</button>
							<button type="submit" value="Controls" name="subbed" class="btn btn-secondary">Dashboard</button>
EOT;
	$right='							<a href="index.php?logout" class="btn btn-secondary">Log Out</a>';

 return close_F($left,$right='');
}   



   function VLF_open(){
	   $res=<<<EOT
				<form method="POST" id="viewForm" class="collapsable">
EOT;		
		return $res;	   
   }
   
   function VLF_colUL($data, $wrap =true){
   		$res=($wrap  ? '							<div class="view-wrap ">'."\n" : '').'							<ul class="list">'."\n";
   		if ($data){ $res.= VLF_categories($data);}
   		$res.='							</ul>'."\n".($wrap  ? '							</div>'."\n" : '');
   		return $res;  
   }



   function VLF_categories($theList){
   		$res='';
		$closeCat ='';
		$lastCat=false;
		$acc =(isset($_SESSION['LISTlogged']['prefs']['acc']) && $_SESSION['LISTlogged']['prefs']['acc']);
		while($thisItem =  $theList->theRow()){
			if( $lastCat != $thisItem['GLICat']){
				$res.= $closeCat;
				$lastCat = $thisItem['GLICat'];
				$res.=VLF_category_open($thisItem['GLICat'], $acc);
			}
			$res.= VLF_item($thisItem);
			$closeCat= <<<EOT
							</ul>
						</li>
EOT;
		//$theList->nextRow();
		}
		$res.= $closeCat;
		return $res;
	}


function build_email_list($data ,$name='shared', $indent =0,$spanClass='remove-icon'){
	$ul ='';
	$indent = str_repeat("\t", $indent);
 	$data = (is_string($data) && $data ) ? explode(',',$data ) : array(); 
 	foreach ($data as $li){
	 	$li =trim($li);
	 	$ul.="$indent<li>$li<input type=\"hidden\" name=\"{$name}[]\" value=\"$li\"><span class=\"$spanClass\"></span></li>\n";
 	}
 	return $ul;
}




function rm_key_fill   ($str,  array $data =array(),$del1='{{:', $del2=':}}'){
 	$pattern='/'.$del1.'(.*)'.$del2.'/mU';
 	preg_match_all($pattern, $str, $matches);
 	$matches = array_unique($matches[1]) ;
	foreach  ($matches as $match){
	 	$rep = isset($data[$match]) ?   $data[$match] :'';
	 	$str = str_replace($del1.$match.$del2, $rep, $str); 
	}
	return $str;
}

function build_opts($text_str,array $data,$val_str='',$matches=false, $indent=3, $filter=false  ){
 	$opts='';
	$indent=str_repeat("\t", $indent);
	$val = ($val_str == $text_str || trim($val_str) === '')  ? '' : ' value = "'.$val_str.'" ';
	$opt_str_open='<option'.$val;
	$opt_str_close='>'.$text_str.'</option>'."\n";
	$data_size = count($data);
	
	$do_filter = (is_array($filter) &&  count($filter) > 1);
	$filter_key= $do_filter ? $filter[0] : false;
	$filter_val= $do_filter? $filter[1] : false;
	$filter_eq =  isset($filter[2]) ? $filter[2] : '==';
	$filter_override_eq =  isset($filter[5]) ? $filter[5] : '==';
	$filter_override =  (isset($filter[3]) && isset($filter[4]));
	
	foreach ($data as $row=>$datum){ 
		if (!$filter_override  || !RM_compare($datum[$filter[3]], $filter[4], $filter_override_eq )){
		 if ($do_filter && RM_compare($datum[$filter_key],$filter_val,$filter_eq )){ continue;}
		}
		if (!is_array($datum)){ $datum = array($datum);}
		$attrs= (is_array($matches)) ?  rm_attr_match($matches, $datum) : '' ;
		if ($attrs == 'skp'   ){ continue ;}
		$datum['~~'] = count($datum);
		$datum['##'] = $row;
		$datum['@@'] = $data_size;
  		$opts.= $indent.rm_key_fill($opt_str_open.$attrs.$opt_str_close , $datum);
 	}
	return $opts;
}

function rm_attr_match(array $matches, array $datum){
	$attrs='';
	foreach ($matches as $attr=>$needles){
		foreach ($needles as $needle){
 		   $attrib =rm_mult_col_match($needle, $datum ) ? $attr : '';
		   if ($attrib == 'skp' ){ $attrs =$attrib; continue 2;}
		   if ($attrib){
			   $attrs.=" $attrib ";
			   continue;
		   }
		}
	}
	return  $attrs;
}

function rm_addAttr($str, $values,$attr='SELECTED' , $useTag=false){
	if (!$useTag){
		$eq =  '=';
		$quo = '"';
		preg_match('/ value(\s*=\s*)(.)/', $str, $matches );
		if (isset($matches)){
			$eq   = $matches[1];
			$quo  = $matches[2] ;
  		}
	}
 	if (!is_array($values)) { $values = array($values);}
 	foreach($values  as $value){
 	 	if (!$useTag){
	 		$val_marker = ' value'.$eq.$quo.$value.$quo;
	 		$val_rep = $val_marker.' '.$attr.' ';
 		}else{
	 		$val_marker='>'.$value.'</'.$tag.'>';
	 		$val_rep =  ' '.$attr.' '.$val_marker;
  		}
 		$str = str_replace($val_marker, $val_rep, $str);
 	}
	return $str;
}

 	function rm_mult_col_match( array $needle, array $row ){
 		return ($needle === array_intersect_key($row, $needle)) ;
   	}

?>