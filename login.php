<?php
session_start();
if(isset($_SESSION['id']) and isset($_SESSION['username']) and isset($_SESSION['email'])) {
	die("You are already logged in as ".$_SESSION['username']);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Log in</title>
	</head>
	<body>
		<h2>Log In - Expense Tracker </h2>
		<form method="post">
			<input type="email" name="email" placeholder="E-mail" value="<?php  if(isset($_POST['submit'])) print $_POST['email'];?>" />
			<br>
			<br>
			<input type="password" name="password" placeholder="Password" />
			<br>
			<br>
			<input type="submit" name="submit" value="Log In" />
		</form>
	</body>
</html>


<?php
//require_once('auto_login.php');

if(isset($_POST['submit'])) {
	require_once('secure.php');
	$email=Secure($_POST['email']);
	$password=Secure($_POST['password']);
	
	/* use email address as salt than count the no of  chars and than add them at back of string and than hash 20,000 times */
	$password.=$email;
	$password.=strlen($password);
	for($i=0;$i<20000;$i++) {
		$password=hash('sha512',$password);
	}
	
	require_once('db.php');
	$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
	
	$query3=$dbc->prepare("SELECT user_id,username,email FROM user WHERE email=? AND password=?");
	$query3->bind_param('ss',$email,$password);
	$query3->execute();
	$query3->bind_result($query_user_id,$query_username,$query_email) or die("Error bind result 2");
	$query3->fetch();
	session_start();
	$_SESSION['id']=$query_user_id;
	$_SESSION['username']=$query_username;
	$_SESSION['email']=$query_email;
	
	setcookie('id',$query_user_id,time()+60*60*24*365*10);
	setcookie('username',$query_username,time()+60*60*24*365*10);
	setcookie('email',$query_email,time()+60*60*24*365*10);
	
	print "<strong>Log In SUCCESSFUL</strong>";
	//change
	header('Location: http://localhost/expense/index.php');
}
?>