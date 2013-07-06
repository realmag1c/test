<?php 
	require_once('conf.php');
	require_once('lan.php');
	function dc(){
		$connect=DB_DRIVER . ':host='. DB_HOST . ';dbname=' . DB_NAME;
		return new PDO($connect,DB_USER,DB_PASS);
	}

	if (!isset($_POST['delete'])){
		$db=dc();
		$q=$db->prepare('INSERT INTO poll (post_id, rating, login) VALUES (?,?,?);');
		$q->execute(array($_POST['post_id'],$_POST['rating'],$_POST['login']));
		$d=$q->fetch();
		echo $lang[$_COOKIE['lan']]['ypoll'].' : [ '.$_POST['rating'].' ]  ';
		echo '<form name="poll1" action="http://'.$_SERVER["HTTP_HOST"].'/article/'.$_POST['post_id'].'" method="POST">
		<input class="question_radio" type="submit" name="newg" value="'.$lang[$_COOKIE['lan']]['dpoll'].'" >
		<input type="hidden" name="login" value="'.$_POST['login'].'">
		<input type="hidden" name="post_id" value="'.$_POST['post_id'].'"> </form>';
		echo '<script type="text/javascript">alert("'.$lang[$_COOKIE['lan']]['tpoll'].'");</script>';
	}
	else{

		
		
	}
	
	

 die();
?>
