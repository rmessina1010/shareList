<?php
	define('SHARE_ROOT', 'https://sharelist.raymessinadesign.com');
	session_start();
	if ( isset($_GET['logout'])){  session_destroy();}
	$currPage =strtok(basename($_SERVER['REQUEST_URI']), '?');
	if (!isset($_SESSION["LISTlogged"]) && ( $currPage !=='index.php' &&  $currPage !== 'sign_up.php')){ 
 		header('Location: index.php') or die("Please log in."); 
	}
	elseif ( isset($_SESSION["LISTlogged"]) && ( substr($currPage, -4) !=='.php' || $currPage == 'index.php' ||  $currPage == 'sign_up.php')){ 
 		header('Location: dash.php') or die("Error."); 
	}
?>