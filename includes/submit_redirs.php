<?
					switch ($_POST['subbed']){
					case 'Dashboard':
						header('Location: dash.php') or die("the list control is missing!");
						break;
					case 'Edit List':
					case 'Dash Edit':
						$subkey=array('Edit List'=>'inList', 'Dash Edit'=>'editListEdit');
						header('Location: editList.php?glid='.$_POST[$subkey[$_POST['subbed']]]) or die("the list edit page is missing!");
						break;
					case 'View List':
						header('Location: viewList.php?glid='.$_POST['inList']) or die("the list edit page is missing!");
						break;
					case 'Edit Profile':
 						header('Location: editProfile.php') or die("the profile edit page is missing!");
						break;
					case 'Logout':
						header('Location:index.php?logout') or die("the list edit is missing!");
						break;
				}
?>
