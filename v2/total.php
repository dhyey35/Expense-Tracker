<?php
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');

/* date to select exp of these month by passing in month and year */
	
	$date=getdate();
	/* saving only month and year in date */
	$date=$date['mon'].'-'.$date['year'];
	$id = $_SESSION['id'];

$exp_query = "SELECT sum(expense_amt) FROM expense WHERE user_id=$id AND expense_date LIKE '%$date'";
$exp_data = mysqli_query($dbc,$exp_query) or die("Error query");
$exp_total = mysqli_fetch_array($exp_data);
if(!$exp_total[0]){
	$exp_total[0] = "0";
}

$income_query = "SELECT sum(income_amt) FROM income WHERE user_id=$id AND income_date LIKE '%$date'";
$income_data = mysqli_query($dbc,$income_query) or die("Error income");
$income_total = mysqli_fetch_array($income_data);
if(!$income_total[0]){
	$income_total[0] = "0";	
}

$debtors_query = "SELECT sum(debtors_amt) FROM debtors WHERE user_id=$id AND debtors_date LIKE '%$date'";
$debtors_data = mysqli_query($dbc,$debtors_query) or die("Error debtor");
$debtors_total = mysqli_fetch_array($debtors_data);
if(!$debtors_total[0]){
	$debtors_total[0] = "0";	
}

$creditors_query = "SELECT sum(creditors_amt) FROM creditors WHERE user_id=$id AND creditors_date LIKE '%$date'";
$creditors_data = mysqli_query($dbc,$creditors_query) or die("Error creditors");
$creditors_total = mysqli_fetch_array($creditors_data);
if(!$creditors_total[0]){
	$creditors_total[0] = "0";	
}

$data = $exp_total[0]."=".$income_total[0]."=".$debtors_total[0]."=".$creditors_total[0];
die($data);
?>