<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$logged_in = false; //for navbar

if(isset($_COOKIE['id']) and isset($_COOKIE['username']) and isset($_COOKIE['email']) and
	 !isset($_SESSION['id']) and !isset($_SESSION['email']) and !isset($_SESSION['username'])) {

	//cookies are set and session is not - so already logged in
	$_SESSION['id']=$_COOKIE['id'];
	$_SESSION['username']=$_COOKIE['username'];
	$_SESSION['email']=$_COOKIE['email'];
	$logged_in = true;
}
else if(isset($_SESSION['id']) and isset($_SESSION['email']) and isset($_SESSION['username'])){
	//sessions are set so user is logged in
	$logged_in = true;
}
?>