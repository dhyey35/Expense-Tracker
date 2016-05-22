<?php
require_once('login_verifier.php');
$_SESSION[]=array();
session_destroy();
if(isset($_COOKIE['id'])) {
	setcookie('id','',time()-30);
}
if(isset($_COOKIE['username'])) {
	setcookie('username','',time()-30);
}
if(isset($_COOKIE['email'])) {
	setcookie('email','',time()-30);
}
//change
header("Location: http://localhost/expense/v2/login.php");
print "<strong style='font-size:1em;'>You have been successfully logged out <a href=login.php >Log In Again</a></strong>";
?>