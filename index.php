<?
//error_reporting(0);
error_reporting(E_ERROR | E_PARSE);
session_start();

require_once 'inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
	<title><?title(); echo $title;?></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" href="http://<? echo $_SERVER["HTTP_HOST"]; ?>/style.css" type="text/css" media="screen, projection" />
</head>

<body>

<div id="wrapper">

	<div id="header">
		<strong><a href="http://<? echo $_SERVER["HTTP_HOST"]; ?>"><?echo $lang[$_COOKIE['lan']]['main'];?></a></strong>
	</div><!-- #header-->

	<div id="middle">

		<div id="container">
			<div id="content">


        <? showcontent();

        ?>
<br>
        
			</div><!-- #content-->
		</div><!-- #container-->




		<div class="sidebar" id="sideLeft">
<div>
<form action="<? $_SERVER['PHP_SELF'] ?>" method="post">
<select name="lang" size="1">
<option value="ru" <?if ($lan=='ru'){echo 'selected';}?>>Русский</option>
<option value="en" <?if ($lan=='en'){echo 'selected';}?>>English</option>
</select> 
<input name="submit" type="submit" value="<?echo $lang[$lan]['choose'];?>" />


</form>
</div>

<?

echo $lang[$_COOKIE['lan']]['users'].': '.users();
if (!isset($_COOKIE['id'])){

echo '<div align="center">
<form action="" method="post" enctype="multipart/form-data">
'.$lang[$lan]['login'].':<br /><input name="login" type="text" size="20"><br />
'.$lang[$lan]['pass'].':<br /><input name="password" type="password" size="20"><br />
<input name="submit" type="submit" value="'.$lang[$lan]['enter'].'"><br />
</form>
</div><br><a href="http://'.$_SERVER["HTTP_HOST"].'/register">'.$lang[$_COOKIE['lan']]['regb'].'</a>';

}
else{
echo '<div><a href="http://'.$_SERVER["HTTP_HOST"].'/addnews">'.$lang[$_COOKIE['lan']]['add'].'</a></div>';
echo '<div><a href="http://'.$_SERVER["HTTP_HOST"].'/logout">'.$lang[$_COOKIE['lan']]['logout'].'</a></div>';
}

?>
  
		</div><!-- .sidebar#sideLeft -->

		<div class="sidebar" id="sideRight">
      .
		</div><!-- .sidebar#sideRight -->

	</div><!-- #middle-->

	<div id="footer">
		<hr>
	</div><!-- #footer -->

</div><!-- #wrapper -->

</body>
</html>
