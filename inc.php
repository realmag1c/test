<?

// lang check
require_once 'conf.php';
require_once 'lan.php';

$role=role();


//cookie
if (isset($_POST['langua'])){
  Setcookie('lan','ua', time()+60*60*24*30,"/");
  print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
}

if (isset($_POST['langen'])){
  Setcookie('lan','en', time()+60*60*24*30,"/");
  print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
}

if (!isset($_COOKIE["lan"])){
 setcookie ('lan', 'en', time()+60*60*24*30,"/");
 $lan='en';
 print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
}
else{
 $lan=$_COOKIE["lan"];
}
 
 

// Main function

function showcontent(){
global $lan,$role,$lang;





//main page
if ((!isset($_GET['do']))or($_GET['do']=='news')){
	showmain($_GET['p']);
}

if (isset($_POST['comtext'])){
	comment();
}

if(isset($_POST['password2'])){ 
	register();
}

if(isset($_POST['savepro'])){ 
	savepro();
}

//login
if((!isset($_POST['password2']))&&(isset($_POST['login']))){ 
	login();
}

// add news
if((isset($_POST['texten']))&&(isset($_POST['titleen']))){
	if (($role=='editor')or($role=='admin')){
		if (isset($_POST['titleen'])){$ten=$_POST['titleen'];}else{$ten='';}
		if (isset($_POST['titleua'])){$tua=$_POST['titleua'];}else{$tua='';}
		if (isset($_POST['texten'])){$teen=$_POST['texten'];}else{$teen='';}
		if (isset($_POST['textua'])){$teua=$_POST['textua'];}else{$teua='';}
		$db=dc();
		$insert = $db->query("INSERT INTO `news` (`titleen` ,`posten`, `titleua` ,`postua` ) VALUES ('".mysql_real_escape_string($ten)."', '".mysql_real_escape_string($teen)."', '".mysql_real_escape_string($tua)."', '".mysql_real_escape_string($teua)."')");
		if ($insert==true){echo '<script type="text/javascript">alert("'.$lang[$_COOKIE['lan']]['saved'].'");</script>';}
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
	}
}

// edit news
if((isset($_POST['etexten']))&&(isset($_POST['etitleen']))){
	if (($role=='editor')or($role=='admin')){
		if (isset($_POST['etitleen'])){$ten=$_POST['etitleen'];}else{$ten='';}
		if (isset($_POST['etitleua'])){$tua=$_POST['etitleua'];}else{$tua='';}
		if (isset($_POST['etexten'])){$teen=$_POST['etexten'];}else{$teen='';}
		if (isset($_POST['etextua'])){$teua=$_POST['etextua'];}else{$teua='';}
		$db=dc();
		$insert = $db->query(" UPDATE `news` SET titleen='".mysql_real_escape_string($ten)."' ,posten= '".mysql_real_escape_string($teen)."',  titleua='".mysql_real_escape_string($tua)."' , postua= '".mysql_real_escape_string($teua)."' WHERE id=".$_POST['n']);
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'/article/'.$_POST['n'].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
		if ($insert==true){echo '<script type="text/javascript">alert("'.$lang[$_COOKIE['lan']]['saved'].'");</script>';}
	}
  
}

if (isset($_GET['do'])){
if ($_GET['do']=='addnews'){
	if (clogin()=='ok'){  add();}
}

if ($_GET['do']=='edit'){
	if (clogin()=='ok'){  edit();}
}

if ($_GET['do']=='remove'){
	if (clogin()=='ok'){ remove($_GET['p']);}
}

if ($_GET['do']=='article'){
	article();
}

if (($_GET['do']=='profile')){
	profile();
}

if ($_GET['do']=='editprofile'){
	profileed();
}

if ($_GET['do']=='profilerm'){
	profilerm();
}

if ($_GET['do']=='userslist'){
	userslist();
}

//register
if ($_GET['do']=='register'){
	reg();
}

if ($_GET['do']=='logout'){
	logout();
}
}
}


// users list
function userslist(){
	global $lang,$role;
		if ($role=='admin'){
			$db=dc();
			$resp = $db->query("SELECT user_role, user_login  FROM users ");
			if($resp){
				echo '<div><ul>';
				while($au = $resp->fetch()){
					echo '<li><b>'.$au['user_login'].'</b> - '.$au['user_role'].' - ';
					echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/editprofile/'.$au['user_login'].'">'.$lang[$_COOKIE['lan']]['pedit'].'</a> ';
					echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/profilerm/'.$au['user_login'].'">'.$lang[$_COOKIE['lan']]['prem'].'</a>';
					echo '</li>';
				}
				echo '</ul></div>';
			}
		}
}

//Delete profile
function profilerm(){
	global $lang,$role;
	$db=dc();
//	echo '<script type="text/javascript">alert("Are you sure?");</script>';
	if (!isset($_GET['p'])){
		$db->query("DELETE FROM users WHERE user_hash='".mysql_real_escape_string($_COOKIE['hash'])."';");	
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
	}
	elseif(role()=='admin'){
		$db->query("DELETE FROM users WHERE user_login='".mysql_real_escape_string($_GET['p'])."';");	
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'/userslist"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
	}
}

//show profile 
function profile(){
	
	global $lang, $role;
	$db=dc();
    $query = $db->query("SELECT * FROM users WHERE user_login='".$_GET['p']."' LIMIT 1");
    $data = $query->fetch();
	echo "<h2>".$data['user_login']."'s profile</h2>";	
	echo '<center><img src="http://'.$_SERVER["HTTP_HOST"].'/avatars/'.$data['user_ava'].'"></center>';
	echo '<div><ul>
	<li>First name: '.$data['user_fname'].'</li>
	<li>Last name: '.$data['user_lname'].'</li>
	<li>Email: '.$data['user_email'].'</li>
	<li>Registered since: '.$data['user_regdate'].'</li>
	<li>Last login: '.$data['user_llogin'].'</li>
	</ul></div>';
	if($role!==false){
		echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/editprofile">'.$lang[$_COOKIE['lan']]['pedit'].'</a> ';
		echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/profilerm">'.$lang[$_COOKIE['lan']]['prem'].'</a>';	
	}
}

//profile save
function savepro(){
	global $role;
	if(is_uploaded_file($_FILES["fupload"]["tmp_name"])){
		$path='avatars/';
		if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name'])){
			$filename=$_FILES['fupload']['name'];
            $source=$_FILES['fupload']['tmp_name'];
            $target=$path . $filename;
            move_uploaded_file($source,$target);
			if(preg_match('/[.](GIF)|(gif)$/',$filename)) {
				$im=imagecreatefromgif($path.$filename) ; 
            }
            if(preg_match('/[.](PNG)|(png)$/',$filename)) {
                $im=imagecreatefrompng($path.$filename) ;
            }
            if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',$filename)) {
                $im=imagecreatefromjpeg($path.$filename);
            }
 
			$w=150;
			$w_src=imagesx($im);
			$h_src=imagesy($im);
			$dest=imagecreatetruecolor($w,$w);
/*
			if($w_src>$h_src) {
				imagecopyresampled($dest, $im, 0, 0,round((max($w_src,$h_src)-min($w_src,$h_src))/2),0, $w, $w,min($w_src,$h_src), min($w_src,$h_src));}
			if ($w_src<$h_src) {
				imagecopyresampled($dest, $im, 0, 0,0, 0, $w, $w,min($w_src,$h_src),min($w_src,$h_src)); }
			if ($w_src==$h_src){
				imagecopyresampled($dest,    $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);     }
*/
			imagecopyresampled($dest,$im, 0, 0, 0, 0, $w, $w, $w_src, $h_src);
			$date=time();
			$avatar=$date.".jpg";
			imagejpeg($dest,$path.$avatar);
			
			unlink($target);
		}
	}
	$db=dc();
	if (isset($_POST['fname'])){$fname=$_POST['fname'];}else{$fname='';}
	if (isset($_POST['lname'])){$lname=$_POST['lname'];}else{$lname='';}
	if ($role=='admin'){
		$db->query("UPDATE users SET user_role='".mysql_real_escape_string($_POST['role'])."', user_ava='".mysql_real_escape_string($avatar)."', user_fname='".mysql_real_escape_string($fname)."', user_lname='".mysql_real_escape_string($lname)."', user_email='".mysql_real_escape_string($_POST['email'])."' WHERE user_login='".mysql_real_escape_string($_POST['ulogin'])."';");		
		echo $_POST['ulogin'];
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'/userslist"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
	}
	else{
		$db->query("UPDATE users SET user_ava='".mysql_real_escape_string($avatar)."', user_fname='".mysql_real_escape_string($fname)."', user_lname='".mysql_real_escape_string($lname)."', user_email='".mysql_real_escape_string($_POST['email'])."' WHERE user_hash='".mysql_real_escape_string($_COOKIE['hash'])."';");
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'/profile"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
	}
	

}

//profile edit
function profileed(){
global $lang,$role;
	$db=dc();
	if ($role=='admin'){$query = $db->query("SELECT * FROM users WHERE user_login='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");}
	else{$query = $db->query("SELECT * FROM users WHERE user_hash='".mysql_real_escape_string($_COOKIE['hash'])."' LIMIT 1");}
    $data = $query->fetch();
	echo '<h2>Edit profile for '.$data['user_login'].'</h2>';
	echo '<div align="center">
	<form action="" method="post" enctype="multipart/form-data">';
	if ($role=='admin'){
		echo 'Role: <select name="role">';echo $data['user_role'];
		echo '<option value="'.$lang[$_COOKIE['lan']]['admin'].'" '; if($data['user_role']=='admin'){echo 'selected';}  echo'>'.$lang[$_COOKIE['lan']]['admin'].'</option>';
		echo '<option value="'.$lang[$_COOKIE['lan']]['editor'].'" '; if($data['user_role']=='editor'){echo 'selected';}  echo'>'.$lang[$_COOKIE['lan']]['editor'].'</option>';
		echo '<option value="'.$lang[$_COOKIE['lan']]['user'].'" '; if($data['user_role']=='user'){echo 'selected';}  echo'>'.$lang[$_COOKIE['lan']]['user'].'</option>';
		echo '<option value="'.$lang[$_COOKIE['lan']]['ban'].'" '; if($data['user_role']=='banned'){echo 'selected';}  echo'>'.$lang[$_COOKIE['lan']]['ban'].'</option>';
		echo '</select>';
		echo '<input name="ulogin" type="hidden" value="'.$data['user_login'].'"><br>';
	}
	echo $lang[$_COOKIE['lan']]['fname'].'<br /><input name="fname" type="text" size="20" value="'.$data['user_fname'].'"><br />
	'.$lang[$_COOKIE['lan']]['lname'].'<br /><input name="lname" type="text" size="20" value="'.$data['user_lname'].'"><br />
	'.$lang[$_COOKIE['lan']]['email'].'<br /><input name="email" type="text" size="20" value="'.$data['user_email'].'"><br /><br />
	<center><img src="http://'.$_SERVER["HTTP_HOST"].'/avatars/'.$data['user_ava'].'"></center>
	'.$lang[$_COOKIE['lan']]['ava'].'<br /><input type="FILE" name="fupload">
	<input name="savepro" type="submit" value="'.$lang[$_COOKIE['lan']]['save'].'"><br />
	</form>
	</div>';
	if ($role!=='admin'){echo '<div><a href="http://'.$_SERVER["HTTP_HOST"].'/profile/'.$data['user_login'].'">'.$lang[$_COOKIE['lan']]['profile'].'</a></div>';}
}


// Register
function reg(){
global $lang;
echo '<h2>Register</h2>';
echo '<div align="center">
<form action="" method="post" enctype="multipart/form-data">
'.$lang[$_COOKIE['lan']]['login'].'<br /><input name="login" type="text" size="20"><br />
'.$lang[$_COOKIE['lan']]['pass'].'<br /><input name="password" type="password" size="20"><br />
'.$lang[$_COOKIE['lan']]['retypepass'].'<br /><input name="password2" type="password" size="20"><br />
'.$lang[$_COOKIE['lan']]['email'].'<br /><input name="email" type="text" size="20"><br /><br />
<input name="submit" type="submit" value="'.$lang[$_COOKIE['lan']]['regb'].'"><br />
</form>
</div>';
}


function register(){
global $lang;
$db=dc();

$query = $db->query("SELECT user_id FROM `users`  WHERE `user_login`='".mysql_real_escape_string($_POST['login'])."'");
$row = $query->rowCount();
$query = $db->query("SELECT user_id FROM `users`  WHERE `user_email`='".mysql_real_escape_string($_POST['email'])."'");
$row1 = $query->rowCount();
if(empty($_POST['login'])){echo '<script type="text/javascript">alert("'.$lang['en']['elog'].'");</script>';}
  elseif(!preg_match("/[-a-zA-Z0-9]{3,15}/", $_POST['login'])){echo '<script type="text/javascript">alert("'.$lang['en']['wlog'].'");</script>';}
    elseif(empty($_POST['password'])){echo '<script type="text/javascript">alert("'.$lang['en']['wpas'].'");</script>';} 
      elseif($row > 0){echo '<script type="text/javascript">alert("'.$lang['en']['euser'].'");</script>';}
        elseif(!preg_match("/[-a-zA-Z0-9]{3,30}/", $_POST['password'])){ echo '<script type="text/javascript">alert("'.$lang['en']['epas'].'");</script>'; }
          elseif($_POST['password'] != $_POST['password2']){echo '<script type="text/javascript">alert("'.$lang['en']['wpas2'].'");</script>';}
            elseif((!preg_match("/[-a-zA-Z0-9_]{3,20}@[-a-zA-Z0-9]{2,64}\.[a-zA-Z\.]{2,9}/", $_POST['email']))or($row1>0)){echo '<script type="text/javascript">alert("'.$lang['en']['eemail'].'");</script>';}
              else{
				  $login = mysql_real_escape_string($_POST['login']); 
				  $password = md5(md5(mysql_real_escape_string($_POST['password'])));  
				  $email = mysql_real_escape_string($_POST['email']);
				  $insert = $db->query("INSERT INTO `users` (`user_login` ,`user_password` ,`user_email`,`user_regdate`, `user_role` ) VALUES ('$login', '$password', '$email','".date( 'Y-m-d H:i:s' )."', 'user')");}

if($insert == true){  
	echo $lang['en']['reg'];  
	login();
}else{  

	print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'/register"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
  }                
dbd();
}


function login(){
global $lang;

function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
    }
    return $code;
}

$db=dc();

if(isset($_POST['submit']))
{
    $query = $db->query("SELECT user_id, user_password, user_role FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."' LIMIT 1");
    $data = $query->fetch();
	if ($data['user_role']!=='banned'){
		if($data['user_password'] === md5(md5($_POST['password'])))    {
			$hash = md5(generateCode(10));
			$db->query("UPDATE users SET user_hash='".$hash."', user_llogin='".date( 'Y-m-d H:i:s' )."' WHERE user_id='".$data['user_id']."'");
			setcookie("id", $data['user_id'], time()+60*60*24*30,"/");
			setcookie("hash", $hash, time()+60*60*24*30,"/");
			print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');   
		}
    else
    {
		echo '<script type="text/javascript">alert("'.$lang[$_COOKIE['lan']]['elp'].'");</script>';
    }
	}
    else{echo '<script type="text/javascript">alert("'.$lang[$_COOKIE['lan']]['banned'].'");</script>';}
}
dbd();
}


function clogin(){
global $lang;
$db=dc();
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{   
    $query = $db->query("SELECT * FROM users WHERE user_hash = '".$_COOKIE['hash']."' LIMIT 1");
    $userdata = $query->fetch();
    if(($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id']))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print $lang[$_COOKIE['lan']]['cookie'];
    }
    else
    {
        return 'ok';
    }
}
else
{
    return 0;
}
}


function users(){
global $lang;
$db=dc();
$query = $db->query("SELECT * FROM `users` ");
$row = $query->rowCount();
return $row;
}

function logout(){
global $lang;
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');       
}


function comment(){
	$db=dc();
	if ((isset($_POST['comtitle']))and($_POST['comtitle']!=='')){$cti=$_POST['comtitle'];} else{$cti=cutt($_POST['comtext']);}
	$db->query("INSERT INTO `comments` (`post_id` ,`title`, `comment` ,`login` ,`comdate` ) VALUES ('".$_POST['post_id']."', '".$cti."', '".$_POST['comtext']."', '".$_POST['user']."', '".date( 'Y-m-d H:i:s' )."');");
}

function article(){
global $lang,$role;
    	$db=dc();
    	$query = $db->query("SELECT * FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");
    	$data = $query->fetch();
		if($_COOKIE['lan']=='en'){ $t='titleen'; $te='posten';}else{$t='titleua'; $te='postua';}
    	echo '<h3>'.$data[$t].'</h3>';
    	echo '<div>'.$data[$te].'</div>';
    	if (($role=='editor')or($role=='admin')){ 
		echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/edit/'.$data['id'].'">'.$lang[$_COOKIE['lan']]['edit'].'</a> ';
		echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/remove/'.$data['id'].'">'.$lang[$_COOKIE['lan']]['remove'].'</a><hr>';

		}

		//comments
		echo '<h3>Comments</h3>  <form action="" method="post">';
		$q=$db->query("SELECT * FROM comments WHERE post_id='".mysql_real_escape_string($_GET['p'])."'");
		if ($q){
			while($d = $q->fetch()){
				echo '<b>'.$d['title'].'</b><br>';
				echo $d['comment'].'<br>';
				echo 'Posted: '.$d['comdate'].' by <a href="http://'.$_SERVER["HTTP_HOST"].'/profile/'.$d['login'].'">'.$d['login'].'</a>';
			}
		}
		if ($role!==false){		
			echo'<br><b>'.$lang[$_COOKIE['lan']]['title'].'</b>
			<p><input type="text" size="39" name="comtitle" value=""></p>
			<p><textarea rows="7" cols="55" name="comtext"></textarea></p>
			<input type="hidden" name="post_id" value="'.$_GET['p'].'">
			<input type="hidden" name="user" value="'.user().'">
			<p><input type="submit" value="'.$lang[$_COOKIE['lan']]['post'].'"></p>    
			</form>';
		//end comment
		}
}


function add(){
global $lang;
echo '  <form action="" method="post">
    <b>'.$lang['en']['title'].'</b>
    <p><input type="text" size="39" name="titleen" value=""></p>
    <p><textarea rows="20" cols="55" name="texten"></textarea></p>

    <b>'.$lang['ua']['title'].'</b>
    <p><input type="text" size="39" name="titleua" value=""></p>
    <p><textarea rows="20" cols="55" name="textua"></textarea></p>
    <p><input type="submit" value="'.$lang[$_COOKIE['lan']]['post'].'"></p>    
  </form>';
}



function edit(){
global $lang;
$db=dc();
    $query = $db->query("SELECT * FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");
    $data = $query->fetch();
    if($_COOKIE['lan']=='en'){ $t='titleen'; $te='posten';}else{$t='titleua'; $te='postua';}
	echo '  <form action="" method="post">
    <b>'.$lang['en']['title'].'</b>
    <p><input type="text" size="39" name="etitleen" value="'.$data['titleen'].'"></p>
    <p><textarea rows="20" cols="55" name="etexten">'.$data['posten'].'</textarea></p>
    <b>'.$lang['ua']['title'].'</b>
    <p><input type="text" size="39" name="etitleua" value="'.$data['titleua'].'"></p>
    <p><textarea rows="20" cols="55" name="etextua">'.$data['postua'].'</textarea></p>

    <input type="hidden" name="n" value="'.$_GET['p'].'">
    <p><input type="submit" value="'.$lang[$_COOKIE['lan']]['save'].'"></p>
	</form>';
}

function cutt($tex){
	if(strlen($tex)>15){	
		return substr($tex,0,strpos($tex,' ',15));
	}
	else{return $tex;}
}

function cut($text){
if(strlen($text>150)){
	$s= substr($text,1, 150);
	return substr($s,0,strrpos($s,' ')).'...';
}
else{return $text;}
}


function showmain($page){
global $lang;
  $db=dc();
  if($page<1){$page=1;}
  $p=($page-1)*5;
  if (clogin()=='ok'){$cl=true;}else{$cl=false;}
  $query = $db->query("SELECT id FROM `news` ;");
  $row = $query->rowCount();
  $query="SELECT DISTINCT *  FROM news ORDER BY id DESC LIMIT ".mysql_real_escape_string($p).",5;";
  $resp=$db->query($query);
  if($resp){
	if($_COOKIE['lan']=='en'){ $t='titleen'; $te='posten';}else{$t='titleua'; $te='postua';}
    while($au = $resp->fetch()){
      echo '<p><a href="http://'.$_SERVER["HTTP_HOST"].'/article/'.$au['id'].'"><b>'.$au[$t].'</b></a></p>';
      echo '<p>'.cut($au[$te]).'</p>';
      echo '<p><a href="http://'.$_SERVER["HTTP_HOST"].'/article/'.$au['id'].'">'.$lang[$_COOKIE['lan']]['read'].'</a></p>';
      echo'<hr><br>';
    }
    if ($row>5){
      echo $lang[$_COOKIE['lan']]['page'].' : ';
      
      for ($i=1;$i<(floor($row/5)+2);$i++){ 
        if ($i==$page){echo '<b>'.$i.' </b>';} 
        else {echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/news/'.$i.'"> '.$i.' </a>';}
      }
    }
  } 
}





function remove($p){
	$query = $db->query("DELETE * FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."'");
}



// titles

function title(){
global $role,$lang;
$db=dc();
	$title=$lang[$_COOKIE['lan']]['main'];
if ($_GET['do']=='addnews'){
  $title='Add News';
}

if ($_GET['do']=='edit'){
  $title='Edit News';
}

if ($_GET['do']=='article'){
  $t="title".$_COOKIE['lan'];
  $query = $db->query("SELECT $t FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");
  $data = $query->fetch();
  
  $title=$data[$t];
}


if ($_GET['do']=='register'){
$title='Registration';
}

if ($_GET['do']=='userslist'){
$title='Users List';
}

if ($_GET['do']=='profile'){
$title='Profile';
}

if ($_GET['do']=='editprofile'){
$title='Edit Profile';
}
echo $title;
}




//functions
function role(){
	if (isset($_COOKIE['hash'])){
		$db=dc();
		$query=$db->query("SELECT user_role FROM users WHERE user_hash='".mysql_real_escape_string($_COOKIE['hash'])."' LIMIT 1");
		$data = $query->fetch();
		return $data['user_role'];
	}
	else{return false;}
}

function user(){
	if (isset($_COOKIE['hash'])){
		$db=dc();
		$query=$db->query("SELECT user_login FROM users WHERE user_hash='".mysql_real_escape_string($_COOKIE['hash'])."' LIMIT 1");
		$data = $query->fetch();
		return $data['user_login'];
	}
	else{return false;}
}


function dc(){
	$connect=DB_DRIVER . ':host='. DB_HOST . ';dbname=' . DB_NAME;
	return new PDO($connect,DB_USER,DB_PASS);
}
function dbc(){
global $dbcn, $config;
$dbcn = mysql_connect($config['server'], $config['login'], $config['passw']) or die("Error!"); 
mysql_select_db($config['name_db'], $dbcn) or die("Error!"); 
}

function dbd(){
  global $dbcn;
  if (!mysql_close($dbcn)){echo 'cant terminate connection<br>';}  
}





?>
