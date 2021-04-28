<?
   function rm_hash_pass  ($raw ,$c=PASSWORD_BCRYPT, array $options=array()){
	   if ( function_exists('password_hash')){ return password_hash($raw, $c, $options);}
 	   return crypt($raw)   ;
   }
   
   function rm_check_pass($raw,$stored){
	   ///echo $raw,'<br>',$stored,'**';  
	  if ( function_exists('password_hash')){ return  password_verify ($raw,$stored); }
	  return (crypt( $raw,$stored) == $stored);
   }
?>