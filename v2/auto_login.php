<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_COOKIE['id']) and isset($_COOKIE['username']) and isset($_COOKIE['email']) and !isset($_SESSION['id']) and !isset($_SESSION['email']) and !isset($_SESSION['username'])) {
	
	$_SESSION['id']=$_COOKIE['id'];
	$_SESSION['username']=$_COOKIE['username'];
	$_SESSION['email']=$_COOKIE['email'];
}
?>