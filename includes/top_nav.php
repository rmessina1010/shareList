           <? 
	           $tpage =basename($_SERVER['PHP_SELF']);
	           $tpages =  array( 
	           		array( 'pg'=> 'dash.php', 'txt'=>'Dashboard', 'qry'=>'' ) ,
	           		array( 'pg'=> 'editProfile.php', 'txt'=>'Edit Profile', 'qry'=>'' ) 
	          );
	       ?>
	  		<nav class="navbar navbar-light bg-light row no-gutters navbar-expand-sm">
	  			<div class="row col no-gutters  " >
		  			<div class="navbar-brand col-2">LOGO</div>   
		  			<div class="py-2 col">Hello: <span><? echo $_SESSION["LISTlogged"]['username'] ?></span></div>
	  			</div>
			    <ul class=" navbar navbar-nav  ml-auto"  >
				<? foreach ($tpages as $apage):?>
				  <? if ($tpage != $apage['pg']):?>
  			      <li class="nav-item " >
			        <a class="nav-link" href="<? echo $apage['pg'], $apage['qry']; ?>"><? echo $apage['txt']  ?></a>
			      </li>
			      <? endif; ?>
				<?endforeach; ?>
 			      <li class="nav-item " >
			        <a class="nav-link" href="index.php?logout=y">Log Out</a>
			      </li>
			    </ul>
			</nav>
