<?php
require_once('auto_login.php');
if(!isset($_SESSION['id']) or !isset($_SESSION['username']) or !isset($_SESSION['email'])) {
	//change
	header('Location: http://localhost/expense/login.php');
	die();
}
?>