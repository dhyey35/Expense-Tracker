<?php
if(isset($_POST['submit'])) {
	require_once('secure.php');
	$dbc=mysqli_connect('localhost','dhyey','password','expense_tracker');
	$email=Secure($_POST['email']);
	$new_password=Secure($_POST['new_password']);
	if(strlen($new_password)<8 or strlen($new_password)>300) {
		die("Please enter password of minimum 8 characters");
	}
	/* checking for upper case , lower case and digit*/
	if(!preg_match('/[A-Z]+[a-z]+[0-9]+/',$new_password)) {
		die('Please enter password with one upper case , one lower case and one digit eg : Test1234');
	}
	if($new_password=='Test1234') {
		die("Sorry you cannot have your username or Test1234 as your password");
	}
	/* use email address as salt than count the no of  chars and than add them at back of string and than hash 20,000 times */
	$new_password.=$email;
	$new_password.=strlen($new_password);
	for($i=0;$i<20000;$i++) {
		$new_password=hash('sha512',$new_password);
	}

	$query=$dbc->prepare("UPDATE user SET password=? WHERE email=?");
	$query->bind_param('ss',$new_password,$email);
	$query->execute() or die("Error updating password");
	
	print("Password updated !!");
}
?>
<form method="post">
<input type="email" name="email">
<br>
<input type="password" name="new_password">
<br>
<input type="submit" name="submit">
</form>