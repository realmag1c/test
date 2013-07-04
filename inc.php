<?

// lang check
require_once 'conf.php';
require_once 'lan.php';




// Main function

function showcontent(){
global $lan;
//cookie
if (isset($_POST['lang'])){
  Setcookie('lan',$_POST['lang'], time()+60*60*24*30,"/");
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


//main page
if ((!isset($_GET['do']))or($_GET['do']=='news')){

showmain($_GET['p']);

}

//register
if ($_GET['do']=='register'){
  reg();
}

if(isset($_POST['password2'])){ 
  register();
}

//login
if((!isset($_POST['password2']))&&(isset($_POST['login']))){ 
  login();
}
if ($_GET['do']=='logout'){
  logout();
}

// add news
if((isset($_POST['text']))&&(isset($_POST['title']))){
	if (clogin()=='ok'){
		dbc();
		$insert = mysql_query("INSERT INTO `news` (`title` ,`lan` ,`post` ) VALUES ('".htmlspecialchars(stripslashes($_POST["title"]))."', '".htmlspecialchars(stripslashes($_COOKIE["lan"]))."', '".htmlspecialchars(stripslashes($_POST["text"]))."')");
		if ($insert==true){echo '<b>Saved</b>';}
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
	}
}

// edit news
if((isset($_POST['text1']))&&(isset($_POST['title1']))){
	if (clogin()=='ok'){
		dbc();
		$insert = mysql_query(" UPDATE `news` SET title='".htmlspecialchars(stripslashes($_POST["title1"]))."' ,post= '".htmlspecialchars(stripslashes($_POST["text1"]))."' WHERE id=".$_POST['n']);
		print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'/article/'.$_POST['n'].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
		if ($insert==true){echo '<b>Saved</b>';}
	}
  
}


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
dbc();
global $lang;
$query = mysql_query("SELECT * FROM `users`  WHERE `user_login`='".mysql_real_escape_string($_POST['login'])."'");
$row = mysql_num_rows($query);
if(empty($_POST['login'])){echo $lang['en']['elog'];}
  elseif(!preg_match("/[-a-zA-Z0-9]{3,15}/", $_POST['login'])){echo $lang['en']['wlog'];}
    elseif(empty($_POST['password'])){echo $lang['en']['wpas'];} 
      elseif($row > 0){echo $lang['en']['euser'];}
        elseif(!preg_match("/[-a-zA-Z0-9]{3,30}/", $_POST['password'])){ echo $lang['en']['epas']; }
          elseif($_POST['password'] != $_POST['password2']){echo $lang['en']['wpas2'];}
            elseif(!preg_match("/[-a-zA-Z0-9_]{3,20}@[-a-zA-Z0-9]{2,64}\.[a-zA-Z\.]{2,9}/", $_POST['email'])){echo $lang['en']['eemail'];}
              else{$login = htmlspecialchars(stripslashes($_POST['login'])); $password = md5(md5(htmlspecialchars(stripslashes($_POST['password']))));  $email = mysql_real_escape_string($_POST['email']);$insert = mysql_query("INSERT INTO `users` (`user_login` ,`user_password` ,`user_email` ) VALUES ('$login', '$password', '$email')");}

 if($insert == true){  
  echo $lang['en']['reg'];  
  print ('<script language="JavaScript">document.location="http://'.$_SERVER["HTTP_HOST"].'";  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
  mail($email,$lang['en']['title'],$lang['en']['msg'],"Content-type: text/plain; charset=windows-1251 \r\nFrom: admin@".$_SERVER["HTTP_HOST"]);
  }else{  
  echo $lang['en']['err'];  
  print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
  }                


dbd();
}


function login(){
global $lang;

function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

}


dbc();

if(isset($_POST['submit']))
{
    $query = mysql_query("SELECT user_id, user_password FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."' LIMIT 1");

    $data = mysql_fetch_assoc($query);

    if($data['user_password'] === md5(md5($_POST['password'])))    {

        $hash = md5(generateCode(10));

        mysql_query("UPDATE users SET user_hash='".$hash."' WHERE user_id='".$data['user_id']."'");
        setcookie("id", $data['user_id'], time()+60*60*24*30,"/");
        setcookie("hash", $hash, time()+60*60*24*30,"/");
        print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');
        
    }

    else

    {

        print $lang[$_COOKIE['lan']]['elp'];

    }

}
dbd();
}


function clogin(){
global $lang;
dbc();
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{   
    $query = mysql_query("SELECT * FROM users WHERE user_id = '".intval(mysql_real_escape_string($_COOKIE['id']))."' LIMIT 1");
    $userdata = mysql_fetch_assoc($query);
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
dbc();
$query = mysql_query("SELECT * FROM `users` ");
$row = mysql_num_rows($query);
return $row;
}

function logout(){
global $lang;
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        print ('<script language="JavaScript">setTimeout(function(){document.location="http://'.$_SERVER["HTTP_HOST"].'"},1000);  </script><center><h4>'.$lang[$_COOKIE['lan']]['java'].'</h4></center>');       
}

function add(){
global $lang;

echo '  <form action="" method="post">
    <b>'.$lang[$_COOKIE['lan']]['title'].'</b>
    <p><input type="text" size="73" name="title" value="'.$_POST["title"].'"></p>
    <p><textarea rows="20" cols="55" name="text">'.$_POST["text"].'</textarea></p>
    <p><input type="submit" value="'.$lang[$_COOKIE['lan']]['post'].'"></p>
  </form>';

}


function edit(){
global $lang;
dbc();
    $query = mysql_query("SELECT * FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");
    $data = mysql_fetch_assoc($query);



echo '  <form action="" method="post">
    <b>'.$lang[$_COOKIE['lan']]['title'].'</b>
    <p><input type="text" size="73" name="title1" value="'.$data["title"].'"></p>
    <p><textarea rows="20" cols="55" name="text1">'.$data["post"].'</textarea></p>
    <input type="hidden" name="n" value="'.$_GET['p'].'">
    <p><input type="submit" value="'.$lang[$_COOKIE['lan']]['save'].'"></p>
  </form>';

}

function cut($text){
if(strlen($text>150)){
	$s= substr($text,1, 150);
	return substr($s,1,strrpos($s,' ')).'...';
}
else{return $text;}
}


function showmain($page){
global $lang;
  dbc();
  if($page<1){$page=1;}
  $p=($page-1)*5;
  if (clogin()=='ok'){$cl=true;}else{$cl=false;}
  $query = mysql_query("SELECT * FROM `news` WHERE lan='".mysql_real_escape_string($_COOKIE['lan'])."'");
  $row = mysql_num_rows($query);
  $query="SELECT DISTINCT *  FROM news WHERE lan='".mysql_real_escape_string($_COOKIE['lan'])."' ORDER BY id DESC LIMIT ".mysql_real_escape_string($p).",5;";
  $resp=mysql_query($query);
  if($resp){
    while($au = mysql_fetch_array($resp)){
      echo '<p><a href="http://'.$_SERVER["HTTP_HOST"].'/article/'.$au['id'].'"><b>'.$au['title'].'</b></a></p>';
      echo '<p>'.cut($au['post']).'</p>';
      echo '<p><a href="http://'.$_SERVER["HTTP_HOST"].'/article/'.$au['id'].'">Read more</a></p>';
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



function article(){
global $lang;
    	dbc();
    	if (clogin()=='ok'){$cl=true;}else{$cl=false;}    
    	$query = mysql_query("SELECT * FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");
    	$data = mysql_fetch_assoc($query);
    	echo '<h3>'.$data['title'].'</h3>';
    	echo '<div>'.$data['post'].'</div>';
    	if ($cl){ 
		echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/edit/'.$data['id'].'">'.$lang[$_COOKIE['lan']]['edit'].'</a> ';
		echo '<a href="http://'.$_SERVER["HTTP_HOST"].'/remove/'.$data['id'].'">'.$lang[$_COOKIE['lan']]['remove'].'</a><hr>';
	
	}
}


function remove($p){
	$query = mysql_query("DELETE * FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."'");
}



// titles

function title(){
global $title;
dbc();
if ($_GET['do']=='addnews'){
  $title='Add news.';
}

if ($_GET['do']=='edit'){
  $title='Edit news';
}

if ($_GET['do']=='article'){
  $query = mysql_query("SELECT title FROM news WHERE id='".mysql_real_escape_string($_GET['p'])."' LIMIT 1");
  $data = mysql_fetch_assoc($query);
  $title=$data['title'];
}


if ($_GET['do']=='register'){
$title='Registration';
}


}




//functions
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
