<!DOCTYPE html>
<html>
	<head>
		<title>Sign up</title>
	</head>
	<body style="text-align:center;">
		<h2> Sign up - Expense Tracker </h2>
		<form method="post">
			<input type="text" name="username" placeholder="Name" value="<?php if(isset($_POST['username'])) print htmlentities($_POST['username'],ENT_QUOTES,'utf-8'); ?>" />
			<br>
			<br>
			<input type="email" name="email" placeholder="E-mail" value="<?php if(isset($_POST['email'])) print htmlentities($_POST['email'],ENT_QUOTES,'utf-8'); ?>" />
			<br>
			<br>
			<input type="password" name="password" placeholder="Password">
			<br>
			<br>
			<input type="password" name="confirm_password" placeholder="Confirm Password">
			<br>
			<br>
			<input type="submit" name="submit">
		</form>
	</body>
	
</html>
<?php

if(isset($_POST['submit'])) {
	require('secure.php');
	$username=Secure($_POST['username']);
	$email=Secure($_POST['email']);
	$password=Secure($_POST['password']);
	$confirm_password=Secure($_POST['confirm_password']);
	
	/* validation username max 40 chars email max 254 chars password min 8 chars max 300 chars to prevent buffer overflow*/
	if(strlen($username)<1 or strlen($username)>40) {
		die("Please enter username within 40 characters");
	}
	/* checking @ is there  only once*/
	$email=explode('@',$email);
	if(count($email)==2) {
		if(!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\.\-_?#!&=]*$/',$email[0])){
			die('Please enter a valid email ID');
		}
		//checkdnsrr('$email[1]') write if condition when on linux/unix
	}
	else {
		die('Please enter an email with only one @ symbol !!');
	}
	/* joining back email */
	$email=implode('@',$email);
	if(strlen($email)>254) {
		die("Please enter email id within 254 chars");
	}
	/* validating password */
	if($password!=$confirm_password) {
		die("Please enter identical passwords");
	}
	if(strlen($password)<8 or strlen($password)>300) {
		die("Please enter password of minimum 8 characters");
	}
	/* checking for upper case , lower case and digit*/
	if(!preg_match('/[A-Z]+[a-z]+[0-9]+/',$password)) {
		die('Please enter password with one upper case , one lower case and one digit eg : Test1234');
	}
	if($password=='Test1234' or $password==$username) {
		die("Sorry you cannot have your username or Test1234 as your password");
	}
	/* ensuring that email is not already registered */ 
	$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
	
	$query=$dbc->prepare("SELECT username FROM user WHERE email=?");
	$query->bind_param('s',$email);
	$query->execute();
	$query->bind_result($query_username) or die("Error bind result");
	if($query->fetch()!=0) {
		die("Your email is already registered . Please <a href=login.php>Log in</a>");
	}
	
	/* use email address as salt than count the no of  chars and than add them at back of string and than hash 20,000 times */
	$password.=$email;
	$password.=strlen($password);
	for($i=0;$i<20000;$i++) {
		$password=hash('sha512',$password);
	}

	$query2=$dbc->prepare("INSERT INTO user (username,email,password,join_date) VALUES(?,?,?,CURDATE())");
	$query2->bind_param('sss',$username,$email,$password);
	$query2->execute() or die("Error signing up");
	
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
	
	print"<strong>Sign Up SUCCESSFUL</strong>";
	//change
	header('Location: http://localhost/expense/index.php');
}
?>