<?
	
	function makeToken(){ return  md5(uniqid()) ;}
	function checkToken($formToken, $t='token',  $l='logged'){ return  ($_SESSION[$l][$t] == $formToken) ;}