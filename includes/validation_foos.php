<?


	function rm_check_kids($input){
		return  (preg_match( '/^~5\d{6}$/', $input) === 1);
	}
	function rm_check_req($input){
		return  (trim($input) !== '');
	}

	function rm_check_word($input){
		return  (preg_match( '/^[a-z]+$/i', $input) === 1 ||  !$input);
	}
	
	function rm_check_words($input){
		return  (preg_match( '/^[a-z](\s?[a-z]+)*$/i', $input) === 1  ||  !$input);
	}
	function rm_check_match($input1,$input2){
		return   ( $input1.'' === $input2.'');
	}
 	
	function rm_check_email($input){
		return  (preg_match( '/^[\w\_][\w\.\_\-]+@[\w\.\_\-]+\.[a-z]{2,8}$/i', $input) === 1);
	}
	
	function rm_check_min_len($input,$l){
		return  (strlen($input) >= $l );
	}

	function rm_check_max_len($input,$l){
		return  (strlen($input) <= $l );
	}

	function rm_check_exists($data,$args){
 			$table =  $args['t'] ? $args['t']  : false;
			$col   =  $args['c'] ? $args['c']  : false;
 			$q=new RMSO('`'.$table.'`','`'.$col.'`','`'.$col.'`= :u' );
	 		$r=$q->_doQ(array(':u'=>$data));
	 		$r=$r->fetch(PDO::FETCH_ASSOC);
       	 	return    $r ? true: false ;
 	}

    function rm_return_isset($key,$data){ return  isset($data[$key]) ? $data[$key] : false; }
    
	function rm_run_test($tests,$dataRow){
		$message =array();
		foreach($tests as $target=>$test){
			$m = '';
			if(isset($dataRow[$target])){
				$limm=false;
 				if (isset($test['_lim'])){  
	 				$limm= ($test['_lim']);
	 				unset($test['_lim']);
	 			}
				foreach($test as $foo=>$args){
 					$theTestFoo = 'rm_check_'.$foo;
					if(function_exists($theTestFoo)){
						$xpct = true;
  						if (is_array($args)){
 							$xpct 	= isset($args['x']) 			? ($args['x']) : true;
							$error 	= (isset($args['err']) )  		? $args['err'] : 'Invalid input.';
							$arg 	= (isset($args['args']) ) 		? $args['args'] :  null;
						}else{
							$error = $args;
							$arg   = null;
						}
   						$m.= ( $theTestFoo($dataRow[$target],$arg)  === $xpct) ?   '' : $error;
 						if ( $m && $limm){  break;}
					}
					
				}
				if ($m){ $message[$target] = $m; }
			}
 		}
 		return $message;
	}
	

 ?>