<?
function rm_scrub_tags($html,$tag='style|script'){
	if (!trim($html)){ return $html;}
    $regex = '#<('.$tag.')[^>]*>.*</\1>#i';
  	$htm  = preg_filter( $regex, '', $html);
  	$html = $htm ? :$html;
 	return $html;
}

function rm_sani_attrs($html, array $allow =array()){
	if (!trim($html)){ return $html;}
	$opts = array (
			'css'=> array (  'class'=>1 ,'id'=>1, 'style'=>1),
			'mouse'=> array ( 'up'=>1 ,'down'=>1, 'enter'=>1, 'leave'=>1, 'over'=>1, 'out'=>1),
			'key'=> array ( 'up'=>1 ,'down'=>1, 'press'=>1),
			'gen'=> array ( 'blur'=>1 ,'focus'=>1, 'change'=>1, 'load'=>1, 'click'=>1)
	);
	foreach ($allow as $group=>$type){
		if (!is_array($type)){ continue;}
 		foreach ($type as $attr){
 			unset($opts[$group][$attr]);
		}
	}
	foreach ($opts as $opt_key=>$opt){
		$opts[$opt_key] =  $opts[$opt_key] ? '(?:'.implode('|',array_keys($opts[$opt_key])).')' :'';
	}
 	$on=$close_on='';
	if(($opts['mouse']||$opts['key']||$opts['gen'])){
		if ($opts['css'] ) { $opts['css'].='|';}
		$on='(?:on';
 		$close_on=')';
 		if ($opts['gen']){
	 		$on.=substr($opts['gen'],0, -1).(($opts['mouse']||$opts['key']) ? '|':'');
	 		$close_on.=')';
 		}
 		if ($opts['key']){
	 		$on.='(?:key'.$opts['key'].')'.($opts['mouse'] ? '|':'');
	 		$close_on.=')';
 		}
 		if ($opts['mouse']){
	 		$on.='(?:mouse'.$opts['mouse'].')';
  		}
	}
    $regex = '#(?:'.$opts['css'].$on.$close_on.'\s*=\s*([\'"]).*(?<!\\\\)\1#iU';
   	$htm  = preg_filter( $regex, '', $html);
  	$html = $htm ? :$html;
 	return $html;
}
?>