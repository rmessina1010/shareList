<?

   function ELF_open($listName=''){
return <<<EOT
    <form>
			<h2 class="pt-2 pb-2 m-0 list-title d-flex">
				<label clas="col ">List Title:</label>
				<input class="form-control col ml-2" type="text" name="listTitle" value="$listName">
 			</h2>
EOT;
   }
   
   
   function ELF_colUL($data){
   		$res='		    <ul class="columnade list-group">'."\n";
   		if ($data){ $res.= ELF_categories($data);}
   		$res.="\n</ul>\n";
   		return $res;  
   }

   function ELF_categories($data){
   		$res='';
   		while(true){//loop through all items
	   		//evaluate item
	   		//if new heading  
	   			// if not-start close category heading
		   		if ($res){ $res.="\n	 				</ul>\n	 			\n</li>\n";}
	   			//add category heading
	   			$res.=ELF_category_open($item);
	   		//add item
	   			$res.=ELF_item($item);
   		}
	   	//if not-start, close category heading
		if ($res){ $res.="\n	 				</ul>\n	 			\n</li>\n";}
   		return $res;  
   }

   function ELF_category_open($item){
   		$res=<<<EOT
									<li class="category">
										<span class="categoryName ">platter
										<i class="fa fa-angle-double-up btn btn-primary  pl-1 pr-1 pb-0 pt-0 float-right "   onclick="collapse(this,3)"></i>										
										</span>
										<ul class="list-group">
EOT;
     return $res;
   }

   function ELF_item($item){
	   $res=<<<EOT
        <li class="list-group-item"  >
	        <div class="row">
	            <div class="col row no-gutters" >
		            <div class="d-flex col row no-gutters">
			            <span class="col-md row row no-gutters">
	                        <label class="pl-2 item-label">
	                        	<input type="checkbox"  class="form-check-input d-inline ">Item:
								<input type="hidden" name="theNeed[]" value="0">
								<input type="hidden" name="itemID[]" value="168">
								<input type="hidden" name="category[]" value="general"> 
	                        </label>
	                        <input class="form-control col mb-2 ml-2" type="text">
			            </span>
	                    <label class="ml-2"> Qty.: <input class="form-control d-inline-block ml-2 num-ctrl" type="number"  min="0" >
	                    </label>
	                </div>
	                <span  class="pl-1 item-ctrl">
	                    <i class="fa fa-arrows  btn btn-outline-primary  m-1" onclick="slide(this,3)"></i>
						<i class="fa fa-times-circle btn  btn-outline-primary  m-1" onclick="if (confirmSub()) { deleteMe(this,3); }"></i>
	                </span>
	             </div>
	        </div> 
	        <div class="row">
	             <div class="col-sm d-flex mb-2">
	                 <label class="mr-2">Image:</label> <input type="text" class="form-control">
	            </div>
	            <div class="col-sm d-flex">
	                 <label class="mr-2">Notes:</label><textarea class="form-control" height="1"></textarea> 
	            </div>
	        </div>
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



   function VLF_categories($data){
   		$res='';
   		while(true){//loop through all items
	   		//evaluate item
	   		//if new heading  
	   			// if not-start close category heading
		   		if ($res){ $res.="\n	 				</ul>\n	 			\n</li>\n";}
	   			//add category heading
	   			$res.=VLF_category_open($item);
	   		//add item
	   			$res.=VLF_item($item);
   		}
	   	//if not-start, close category heading
		if ($res){ $res.="\n	 				</ul>\n	 			\n</li>\n";}
   		return $res;  
   }


function VLF_category_open($catName,$col){
	$col_btn = $col ? "\n".'										<i class="fa fa-angle-double-up btn btn-primary  pl-1 pr-1 pb-0 pt-0 float-right "   onclick="collapse(this,3)"></i>'."\n" : '';
	$res=<<<EOT
									<li class="category">
										<span class="categoryName ">$catName$col_btn
										</span>
										<ul class="list-group">
EOT;

	return $res;
}

function VLF_item($data){
  	$image_sleeve =  $data['img'] ? $image_sleeve ='							<span class="sleeve image" onclick="toggleClass(this, \'popmode\');"><span class="real"><img src="URL"></span></span>'."\n" : '';
 	$note_sleve =  $data['note'] ? $image_sleeve ='							<span class="sleeve text" onclick="toggleClass(this, \'popmode\');"><span class="real"><span>TXT</span></span></span>'."\n"  : '';
 	$res=<<<EOT
						<li class="list-group-item">
							<input name="OQT[11]" type="hidden" value="2">
$image_sleve$note_sleve
							<span class="quant">x<input name="QT[11]" type="text" value="2"></span>
							<label><input name="need[11]" value="11" type="checkbox" >thick steak</label>												
						</li>
EOT;
    //fill res
	return $res;
}


function build_email_list($data ,$name='shared', $indent =0,$spanClass='remove-icon'){
	$ul ='';
	$indent = str_repeat("\t", $indent);
 	if ( is_string($data)){$data = explode(',',$data );}
 	foreach ($data as $li){
	 	$li =trim($li);
	 	$ul.='<li>'.$li.'<input type="hidden" name="'.$name.'[]" value="'.$li.'"><span class="'.$spanClass.'"></li>'."/n";
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

function build_opts($text_str,array $data,$val_str='',$matches=false, $indent=3){
 	$opts='';
	$indent=str_repeat("\t", $indent);
	$val = ($val_str == $text_str || trim($val_str) === '')  ? '' : ' value = "'.$val_str.'" ';
	$opt_str_open='<option'.$val;
	$opt_str_close='>'.$text_str.'</option>'."\n";
	$data_size = count($data);
	foreach ($data as $row=>$datum){ 
		if (!is_array($datum)){ $datum = array($datum);}
		$datum['~~'] = count($datum);
		$datum['##'] = $row;
		$datum['@@'] = $data_size;
		$attrs= (is_array($matches)) ?  rm_attr_match($matches, $datum) : '' ;
		if ($attrs == 'skp'){ continue ;}
  		$opts.= $indent.key_fill($opt_str_open.$attrs.$opt_str_close , $datum);
 	}
	return $opts;
}

function rm_attr_match(array $matches, array $datum){
	$attrs='';
	foreach ($matches as $attr=>$needles){
		foreach ($needles as $needle){
		   $attrib =rm_mult_col_match($needle, $datum ) ? $attr : '';
		   if ($attrib == 'skp'){ continue 2;}
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

$test=<<<EOT
<SELECT>
<option value = "1" >hey-1</option>
<option value = "2" >hey-2</option>
<option value = "3" >hey-3</option>
<option value = "4" >hey-4</option>
<option value = "5" >hey-5</option>
<option value = "6" >hey-6</option>
<option value = "7" >hey-7</option>
</SELECT>
EOT;

echo rm_addAttr(rm_addAttr($test, array(6), 'DISABLED'), array(2,5));
?>
