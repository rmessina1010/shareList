<?
	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
	$accordion = $_SESSION["LISTlogged"]['prefs']['acc'];
 	$autoUpdate = $_SESSION["LISTlogged"]['prefs']['auto'];
  	
	if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){
	}
	
	
	$listCheck = "SELECT * FROM `GLists` WHERE `GLID` = :glid  AND (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail))";
	$listCheck = new RMCDO(false, $listCheck, array(':glid'=>$_GET['glid'], ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
	if (!$listCheck->the_('GLID')){ 
		var_dump($listCheck->res, $_SESSION["LISTlogged"]['UserID']);
 		//header('Location: error.php?e=1');
		die;
	}
	$listID=  $listCheck->the_('GLID');
	$item_sql ='SELECT * FROM `GLItems` WHERE `inGList` = :glid  ORDER BY `GLIOrd` ASC';
	$theList = new RMCDO(false, $item_sql, array($_GET['glid']));
	
	
	
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><? echo $listCheck->the_('GLName'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    
    <style type="text/css" media="screen">
    	.columnade  .list-group .list-group-item:hover{
	    	background:#dfdfdf;
	    	
    	}
    	.button-sect{
		    width:100%;
 			
		}
 	    
 	    body>.container{
	 	    padding-bottom: 6em;
 	    }
		
		label{ font-weight: bold ; font-size: .8em;}
		
		.columnade textarea {
			height: 2.35em;
		}
		
		.item-ctrl{
		    width: 3em;
	    }
	    
	    .num-ctrl{
			width:66% !important; 
		}

    	@media screen and (min-width:767px){
	    	
	    			.num-ctrl{
			width:4em !important; 
			vertical-align: top;
		}


	        .item-ctrl{
		        width: auto;
	        }
	    	.control-inner{
		    	display: flex;
	    	}
 	    	.button-sect{
		    	width:40%;
 		    	display: flex;

	    	}
	    	.button-sect + .button-sect{
			    margin-left: auto;
 	    	}
 	    	
 	    	body>.container{
	 	    	padding-bottom: 3em;
 	    	}
 	    	
 	    	.ilb{
	 	    	display: inline-block !important;
 	    	}
     	}
     	/*
     	.columnade{
	     	display: block;
-webkit-columns: 400px 2 !important;
   -moz-columns: 400px 2 !important;
        columns: 400px 2 !important;
        -webkit-column-break-inside:avoid;
        -moz-column-break-inside:avoid;
        column-break-inside:avoid;
     	}
     	*/
     	
     	li.category:nth-child(odd){
	     	background: #efefef;
     	}
     	.cat-label>span, .list-title>label{
 	     	align-self: center;
     	}
    </style>
</head>

<body>
	<div class="container">
 
    <form>
			<h2 class="pt-2 pb-2 m-0 list-title d-flex">
				<label clas="col ">List Title:</label>
				<input class="form-control col ml-2" type="text" name="listTitle" value="<? echo $listCheck->the_('GLName'); ?>">
 			</h2>
		    <ul class="columnade list-group">
 			<? echo ELF_categories($theList); ?>
			</ul>
<div class="control fixed-bottom pt-2" style="background: #ccc"><div class="container pt-1 pb-1"><div class="control-inner">
	<div class="button-sect  d-flex">
		<button type="button" onclick="addBefore(this.parentNode)" class="btn btn-block m-1  btn-outline-secondary btn-sm">Add Category</button>
		<button type="button" onclick="checkToggle(this,2,'.toggle')" class="btn btn-block m-1  btn-outline-secondary btn-sm">Check All</button>
		<button class="btn btn-block m-1 btn-primary btn-sm" type="submit" name="subbed" value="subbed" onclick="this.className='hide';" >Update</button>
		<input id="toDelete" type="hidden" name="toDelete">
 	</div>
		<div class="button-sect  d-flex">
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="viewlist.php?glid=17&amp;acc=1" >View List</a>
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="viewlist.php?glid=17&amp;acc=1" >Dashboard</a>
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="index.php?logout" >Log Out</a>
	</div>
	
</div></div></div>     
 </form>
 
	</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>