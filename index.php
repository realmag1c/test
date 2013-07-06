<?php
//error_reporting(0);
//error_reporting(E_ERROR | E_PARSE);
session_start();

require_once 'inc.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ua">
	
<head>
	<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
	<title><?php title();?></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/style.css" type="text/css" media="screen, projection" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () { 
    $(".question_radio").click(function () {

      $.post("http://<?php echo $_SERVER["HTTP_HOST"]; ?>/poll.php",{"rating":$("input[name='group1']:checked").val(),"post_id":<? echo $_GET['p'];?>,"login":"<? echo user();?>"}, function(respond){$(".poll").html(respond);});

    });
  });
</script>
</head>

<body>

<div id="wrapper">

	<div id="header">
		<strong><a href="http://<?php echo $_SERVER["HTTP_HOST"]; ?>"><?echo $lang[$_COOKIE['lan']]['main'];?></a></strong><br>
		<div>
		
		<form action="<? $_SERVER['PHP_SELF'] ?>" method="post">
		<button type="submit" name="langua"><img src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/images/ua.jpg"> </button>
		<button type="submit" name="langen"><img src="http://<?php echo $_SERVER["HTTP_HOST"]; ?>/images/en.jpg"> </button>
		</form>
		</div>
	</div><!-- #header-->

	<div id="middle">
		<div id="container">
			<div id="content">


        <?php showcontent(); ?>
<br>
        
			</div><!-- #content-->
		</div><!-- #container-->




		<div class="sidebar" id="sideLeft">


<?php

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
	if ($role=='admin'){ echo '<div><a href="http://'.$_SERVER["HTTP_HOST"].'/userslist">'.$lang[$_COOKIE['lan']]['ulist'].'</a></div>'; }
	echo '<div><a href="http://'.$_SERVER["HTTP_HOST"].'/profile/'.user().'">'.$lang[$_COOKIE['lan']]['profile'].'</a></div>';
	if (($role=='editor')or($role=='admin')){ echo '<div><a href="http://'.$_SERVER["HTTP_HOST"].'/addnews">'.$lang[$_COOKIE['lan']]['add'].'</a></div>';}
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
