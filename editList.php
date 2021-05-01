<?
	include_once  ('includes/loginCheck.php');   // check for login: if already logged in, reroute to main page
	include_once  ('../-RMCMS2_5/processors/RMengine2_5.php');
	include_once  ('includes/functions_forms.php');
	$accordion = $_SESSION["LISTlogged"]['prefs']['acc'];
 	$autoUpdate = $_SESSION["LISTlogged"]['prefs']['auto'];
  	
 	$listCheck = "SELECT * FROM `GLists` WHERE `GLID` = :glid  AND (`GLOwner` = :ownerid  OR (`GLEditors` LIKE :theemail))";
	$listCheck = new RMCDO(false, $listCheck, array(':glid'=>$_GET['glid'], ':theemail'=>'%,'.$_SESSION["LISTlogged"]['email'].',%', ':ownerid'=>$_SESSION["LISTlogged"]['UserID']));
 
 	if (!$listCheck->the_('GLID')){ // check if list exists and user has permissions to see it
 		header('Location: error.php?e=1');
		die;
	}
	
	
	if (isset($_POST['subbed']) && $_SESSION['LISTlogged']['stoken'] == $_POST['t']){// process form
		
		
		include ('includes/submit_redirs.php');
	}

	
	$listID=  $listCheck->the_('GLID');
	$item_sql ='SELECT * FROM `GLItems` WHERE `inGList` = :glid  ORDER BY `GLIOrd` ASC';
	$theList = new RMCDO(false, $item_sql, array($_GET['glid']));
	$is_owner = ($_SESSION["LISTlogged"]['UserID'] == $listCheck->the_('GLOwner'));
?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit List:<? echo $listCheck->the_('GLName'); ?></title>
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
     	
     	.no-gutters input.form-control{
	     	padding-left: .5em;
	     	padding-right: .5em;
     	}
     	
     	/*
       	.list-group-item .list-group-item{
	     	background: purple;
	     	position: relative;
     	}
     	.list-group-item .list-group-item span, .list-group-item .list-group-item .row, .list-group-item .list-group-item div{
	     	position: static !important;
     	}
      	.item-label:after{
	     	content: '';
	     	position: absolute !important;
	     	top:0;
	     	left: 0;
	     	right: 0;
	     	bottom: 0;
	     	z-index: 2;
	     	pointer-events: pass-through;
	     
      	}
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
     	ul li.highlite{
	     	background: pink!important;
     	}
    </style>
</head>

<body>
	<div class="container">
    <form method="POST" id="editForm" class="collapsable">
			<h2 class="pt-2 pb-2 m-0 list-title d-flex">
				<label clas="col ">List Title:</label>
 				<? if(!$is_owner):?>
 				<input class="form-control col ml-2" type="text" READONLY value="<? echo $listCheck->the_('GLName'); ?>">
				<? endif; 
				   $ln_type = !$is_owner ?  'hidden' : 'text';
				?>
				 <input class="form-control col ml-2" type="<?echo  $ln_type;?>" name="listTitle"  value="<? echo $listCheck->the_('GLName'); ?>">
 			</h2>
		    <ul class="columnade list-group" >
 			<? 
	 			//output master-template els
 	 			echo ELF_category_open(null,'masterCat'); 
 	 			echo ELF_item(null,'masterLI'); 
 	 			echo "</ul>\n";
	 			//
	 			echo ELF_categories($theList); 
	 		?>
			</ul>
<div class="control fixed-bottom pt-2" style="background: #ccc"><div class="container pt-1 pb-1"><div class="control-inner">
	<div class="button-sect  d-flex">
		<button type="button" onclick="addBefore(mainList)" class="btn btn-block m-1  btn-outline-secondary btn-sm">Add Category</button>
		<button type="button" onclick="checkToggle(this,5,'.toggle')" class="btn btn-block m-1  btn-outline-secondary btn-sm">Check All</button>
		<button class="btn btn-block m-1 btn-primary btn-sm" type="submit" name="subbed" value="subbed" onclick="this.className='hide';" >Update</button>
		<input id="toDelete" type="hidden" name="toDelete">
 	</div>
		<div class="button-sect  d-flex">
		<!--<a class="btn btn-secondary  btn-block m-1 btn-sm" href="viewlist.php?glid=17&amp;acc=1" >View List</a>
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="viewlist.php?glid=17&amp;acc=1" >Dashboard</a>
		<a class="btn btn-secondary  btn-block m-1 btn-sm" href="index.php?logout" >Log Out</a>-->
 		<input  type="hidden" name="inList" id="inList" value="<? echo $listID;?>">
		<input  type="hidden" name="t" id="inList" value="<? echo $_SESSION['LISTlogged']['stoken'];?>">
		<button type="submit" value="View List" class="btn btn-secondary  btn-block m-1 btn-sm" name="subbed">View list</button>
		<button type="submit" value="Dashboard" name="subbed" class="btn btn-secondary  btn-block m-1 btn-sm">Dashboard</button>					<button type="submit"  value="Logout" class="btn btn-secondary  btn-block m-1 btn-sm" name="subbed">Logout</button>
 	</div>
	
</div></div></div>     
 </form>
 	</div>
</body>
	<script type="text/javascript"  src="http://rmdesign.byethost32.com/sharelist/js/general.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
		//init elements
		var mainList = document.getElementById('masterCat').parentNode;
  		prepMaster('masterLI');
		prepMaster('masterCat');
 </script>

</html>