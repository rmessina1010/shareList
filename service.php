<?
	header ("Access-Control-Allow-Origin: *");
	header("Content-Type:application/json");
error_reporting  (E_ERROR); 
ini_set ("display_errors", true); 
ini_set ("mysql.connect_timeout", 1200); 
ini_set ("default_socket_timeout", 1200); 
include_once  ('-RMLCMS/processors/settings.php');  
   	
	$pdo=new PDO(DB_TYP_DEF.':host='.HOST.';dbname='.DBNAME , UACC, UPASS);
	$sql = "SELECT * FROM `GLItems` WHERE `inGList` = ?";
	$stmt=$pdo->prepare($sql);
	$listID  = isset($_GET['l']) ? $_GET['l'] : 1;
	$stmt->execute(array($listID));
	$data= json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
	echo($data); 
?>