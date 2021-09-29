<?
	
require ('settings.php');

class  DB_hub{
	   protected static $dbs 	= array();
	   
	   
 	   protected function __construct(){}
	   
	   public static function create( $db_name =DBNAME, $user=UACC ,  $password = UPASS , $db_host=HOST,  $db_type = DB_TYP_DEF){
		   if (is_string($db_name) &&  !isset(self::$dbs[$db_name])){
			   $temp = new DB( $db_name, $user , $password ,$db_host ,  $db_type );
			   if ($temp) { 
				   self::$dbs[$db_name] =$temp;
				   return self::$dbs[$db_name];
				}
		   }
	   }
	   
	   public static function store(DB $db){
		  $db_name = $db->getName();
		  if(!isset(self::$dbs[$db_name]) ){ self::$dbs[$db_name] = $db; return self::$dbs[$db_name] ;}
	   }
	   
	   public static function get( $db_name){ 
		   return isset(self::$dbs[$db_name]) ? self::$dbs[$db_name] : null;  
	   }
	   
	   public static function remove($db_name){
		   unset (self::$dbs[$db_name]);
	   }

	   
	   public static function connect($db_name, $cnn_name='_default'){
		   if(isset(self::$dbs[$db_name])){
			   return self::$dbs[$db_name]->connect($cnn_name);
		   }
	   }
	   public static function close($db_name, $cnn_name='_default'){
		   if(isset(self::$dbs[$db_name])){
			   return self::$dbs[$db_name]->close($cnn_name);
		   }
	   }
	   
}
class DB{
	
	protected $user 	= null;
	protected $password	= null;
	protected $db_name 	= null;
	protected $db_type 	= null;
	protected $db_host 	= null;
 	protected $db_dsn	= null;
 	protected $dbhs 	= array();


	function __construct($db_name =DBNAME, $user=UACC ,  $password = UPASS , $db_host=HOST,  $db_type = DB_TYP_DEF){
		$this->user 	= $user;
		$this->password	= $password;
		$this->db_name 	= $db_name;
		$this->db_type 	= $db_type;
		$this->db_host 	= $db_host;
		$this->setup();
	}
	
	
	function getName(){ return $this->db_name;}
	
	protected function setup(){
		$this->dbhs = array();
		$this->db_dsn = $this->db_type.":dbname=".$this->db_name.";host=".$this->db_host;
	}
	
	
	function connect($dbh='_default'){
 		return $this->open($dbh) ? $this->dbhs[$dbh] : null;
	}
	
	function open($dbh='_default'){
		if (is_scalar($dbh)){
			if (!isset($this->dbhs[$dbh])){
				try {
				    $this->dbhs[$dbh] = new PDO( $this->db_dsn, $this->user, $this->password);
	 			} catch (PDOException $e) {
				   	echo 'Connection failed: ' . $e->getMessage();
				   	return null;
				}
			}
		}
		return $this;
	}

	function close($dbh='_default'){
		if (is_scalar($dbh)){ 
			unset ($this->dbhs[$dbh]);
		}
		return $this;
	}

}


class DB_query{
	
	protected $dbh 		= null;
	protected $query	= 	'';
	protected $STMNT	= null;
	protected $args		= array();
	protected $holders	= array();
	protected $is_posit	= true;
	protected $hold_ct	= 0;
	protected $params	= null;

	
	function __construct($query,$dbh, array $attributes = array()){
 	  $this->dbh =  $dbh;
 	  $this->prep($query,$attributes);
	  return $this;
	}
	
	function prep($query, array $attributes = array()){
		$this->hold_ct 	= preg_match_all("/:\w+/", $query, $matches);
 		$this->is_posit	= !($this->hold_ct > 0 );
 		$holders		= ($matches && $matches[0]) ?  $matches[0] : array();
 		if ($this->is_posit) { 
	 		$query = str_replace(':?', '?', $query,$count);
	 		$this->hold_ct =$count;
	 		$this->holders= $count >0 ? array_fill(1, $count, true) : array(); 
	 	}else{
	 		$this->holders	= ($matches && $matches[0]) ?  array_flip($matches[0]) : array();
	 	}
		$this->query  	= $query;
 		$this->STMNT  = $this->dbh->prepare($query,$attributes);
		
		return $this;
	}
	
 	
	function bind_param(&$var,$col,$type='STR'){
		if ($this->STMNT){
			$col  =  $this->col_check($col);
			if($col){
				$type =  $this->type_check($type);
				$this->STMNT->bindParam($col,$var,$type);
			}
		}
		return $this;
	}
	
	function bind_val($val,$col,$type='STR'){
	 	if ($this->STMNT){
			$col  =  $this->col_check($col);
			if($col){
				$type =  $this->type_check($type);
				$this->STMNT->bindValue($col,$val,$type);
			}
		}
		return $this;
	}
	
	function list_holders(){
		return array_keys($this->holders);
	}
	
	function has_holder($key){
		return isset($this->holders[$key]);
	}
	
	function is_positional(){
		return $this->is_posit;
	}
	 	
	function set_params(array $params =array(),$type ='PARAM_STR'){////complete later
		foreach ($params as $col=>$val ) {
			$this->bindVal();
		}
		return $this;
	}
	
	function STMNT(){ return $this->STMNT;}
	
	function run($data = null){
		if ($this->STMNT){
			if (is_array($data)){    $this->STMNT->execute($data) ;}
			else{ $this->STMNT->execute();}
		}
		return $this->STMNT;
	}
	
	
	protected function col_check($col){
		if (is_string($col)) { $col = trim($col);}
 		if (!$this->is_posit){
			if (!is_string($col)) {return false;}
 			$col = $col[0] !==':' ? ':'.$col : $col;
			if (!isset($this->holders[$col])){return false;}
		}else{
			if (!is_numeric($col)) {return false;}
			if (is_string($col)) {$col = $col[0] ===':' ? substr($col, 1) : $col;}
			$col=$col+0;
		}
		return $col;
	}
	
	protected function type_check($type){
		if (!is_string($type) || !preg_match('/^ *(?:BOOL|NULL|INT|STR|LOB|STMT) *$/i', $type)){ $type = 'STR'; }
		$type = 'PARAM_'.strtoupper(trim($type));
		return constant('PDO::'.$type);
	}
}


function sql_str($operation, $table,  array $args= array()){
	$operation = is_scalar($operation) ? strtolower(trim($operation.'')) : 'r'; //sanitizes variable and/or defaults to 'r'
	$operation = $operation  ? $operation[0] : 'r'; //sanitizes variable and/or defaults to 'r'
		 
	$key_map = array(  'c'=>'cols', 'v'=>'vals', 'l'=>'limit', 'o'=>'order','w'=>'where', 'g'=>'group', 'h'=>'having', 'sl'=>'set_list');
	foreach ($key_map  as $mkey=>$mvar){ $$mvar = isset($args[$mkey])  ?  $args[$mkey] : null; 	}
	
	$join_key_map = array('j'=>'join',    'lj'=>'join','rj'=>'join','ij'=>'join');
	foreach ($join_key_map  as $join_key=>$jval){ $joins[$join_key] = isset($args[$join_key]) ? $args[$join_key] : array(); }
	 
	$WHERE    = $where  ? ' WHERE '.$where : '';
	$HAVING   = $having ? ' HAVING '.$having : '';
	$LIMIT    = $limit  ? ' LIMIT '.$limit : '';
	$ORDERBY  = $order  ? ' ORDER BY '.$order : '';
	$GROUPBY  = $group  ? ' GROUP BY '.$group : '';
	$JOIN 	  = '';
	$join_sequences = array (  'j'=>'', 'ij'=>' INNER JOIN ', 'lj'=>' LEFT JOIN ','rj'=>' RIGHT JOIN ' );
	foreach( $join_sequences as $sequence =>$join_command ){
		if (is_string($joins[$sequence])){
			if ($joins[$sequence]) { $JOIN .= ($JOIN ? ' , ' : '').$join_command.$joins[$sequence]; }
		}else{
			foreach ($joins[$sequence] as $new_join){
				if (is_string($new_join) && $new_join){ 
					$JOIN .= ($JOIN ? ' , ' : '').$join_command.$joins[$sequence];
				}
			}
		}
 	}
	
  	
 	switch ($operation){
		case 'p':
			$sql = $table;
		break;
		
		case 'c':
	
			if( is_array($cols)){
				if (!$vals){
					$vals = array_values($cols);
					$cols =array_keys($cols);
				}
				$cols = implode(' , ', $cols);
			}
			if(is_array($vals)){
				$val_str=array();
				foreach($vals as $val){
					if (!is_array($val)) { 
						$val_str = implode(' , ', $vals); 
						break;
					}
					$val_str[] =  implode(' , ', $val);
				}
				if (is_array($val_str)) {
					$val_str = implode(') , (' , $val_str);
				}
				$vals = $val_str;
			}
			
			$vals =  '('.$vals.')';
			$sql = "INSERT INTO $table SET ($cols) VALUES $vals";
 		break;
		
		case 'u':
			// set-list
			$set_list_str = $comma ='';
			if (is_string($set_list)){ $set_list_str = $set_list;}
			else{// the set_list has been pre parsed 
				if(is_array($set_list)){ $cols  = $set_list;}
				elseif ( is_array($cols)){ 
					if ( !is_array($vals)  ){
						$cols =  $vals ? array_combine($cols,array_fill(0, count($cols),':?')) : $cols;
					}
					else{ $cols =  array_combine($cols,$vals);}
				} 
				foreach ($cols as $col=>$val){ $set_list_str .=  $comma.$col.' = '.$val; $comma =' , ';}
			}
			$sql = "UPDATE $table $JOIN SET $set_list_str $WHERE $ORDERBY $HAVING $LIMIT";
		break;
		
		case 'd':
			$sql= "DELETE FROM $table $WHERE $ORDERBY $LIMIT";
		break;
		default:
		    if( is_array($cols)){ 
			    $cols= $vals ? implode(',', array_keys($cols)) : implode(',' , $cols )  ;
			}
			$sql = "SELECT $cols FROM $table $JOIN $WHERE $ORDERBY  $GROUPBY $HAVING $LIMIT";
		
 	}
 	
 	return $sql;
 }

function sql_C_str($table, $cols,$vals=null){
	return sql_str('c',$table,array ('c'=>$cols, 'v'=>$vals));
}
function sql_R_str($table, $cols='*',$where=null,$ord=null,$lim=null,array $args=array()){////
	$args['w']=$where;
	$args['c']=$cols;
	$args['o']=$ord;
	$args['l']=$lim;
	return sql_str('r',$table,$args);
}
function sql_U_str($table, $cols, $where, array $args=array()){///
	$args['w']=$where;
	$args['c']=$cols;
	return sql_str('c',$table,$args);
}
function sql_D_str($table, $where, $lim=null, $ord=null){
	return sql_str('d',$table, array('w'=>$where, 'l'=>$lim, 'o'=>$ord));
}
function sql_p_str($table){
	return sql_str('p',$table);
}