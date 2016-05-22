<?php
require_once('login_verifier.php');
require_once('db.php');
require_once('secure.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');
$date=getdate();
$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];
		
function fail($where,$message){
	die($where." ".$message);
}
function success($message){
	die($message);
}



if(isset($_POST['expense_name']) and isset($_POST['expense_amt'])){
	
	$expense_name=Secure($_POST['expense_name']);
	$expense_amt=Secure($_POST['expense_amt']);
	
	
	if(!empty($expense_name) and !empty($expense_amt) and strlen($expense_name)<30 and strlen($expense_amt)<11 and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$expense_name) and preg_match('/^[0-9]*$/',$expense_amt)) {

		$exp_query=$dbc->prepare("INSERT INTO expense (user_id,expense_name,expense_amt,expense_date) VALUES(?,?,?,?)");
		$exp_query->bind_param('ssss',$_SESSION['id'],$expense_name,$expense_amt,$date);
		$exp_query->execute() or fail("exp","db prob");
		
		success("Added $expense_name $expense_amt");
		
	}
	else {
		fail("exp","Please enter name and amount properly");
	}
}

else if(isset($_POST['income_name']) and isset($_POST['income_amt'])) {

	$income_name = Secure($_POST['income_name']);
	$income_amt = Secure($_POST['income_amt']);
	
	if(!empty($income_name) and !empty($income_amt) and strlen($income_name)<30 and strlen($income_amt)<11 and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$income_name) and preg_match('/^[0-9]*$/',$income_amt)) {
		
		$date=getdate();
		$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];

		$income_query=$dbc->prepare("INSERT INTO income (user_id,income_name,income_amt,income_date) VALUES (?,?,?,?)");
		$income_query->bind_param('ssss',$_SESSION['id'],$income_name,$income_amt,$date);
		$income_query->execute();

		success("Added $income_name $income_amt");

	}
	else {
		fail("Please enter income name and amount properly");
	}
	
}
else if(isset($_POST['creditors_name']) or isset($_POST['creditors_amt'])) {
	
	$creditors_name = Secure($_POST['creditors_name']);
	$creditors_amt = Secure($_POST['creditors_amt']);

	if(!empty($creditors_name) and !empty($creditors_amt) and strlen($creditors_name)<30 and strlen($creditors_amt)<11 
		and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$creditors_name) and preg_match('/^[0-9]*$/',$creditors_amt)) {

		$creditors_query=$dbc->prepare("INSERT INTO creditors (user_id,creditors_name,creditors_amt,creditors_date) 
			VALUES (?,?,?,?)");
		$creditors_query->bind_param('ssss',$_SESSION['id'],$creditors_name,$creditors_amt,$date);
		$creditors_query->execute();

		success("Added $creditors_name $creditors_amt");
	}
	else {
		fail("Please enter proper creditors name and amount");
	}
}
else if(isset($_POST['debtors_name']) and isset($_POST['debtors_amt'])) {
	
	$debtors_name = Secure($_POST['debtors_name']);
	$debtors_amt = Secure($_POST['debtors_amt']);

	if(!empty($debtors_name) and !empty($debtors_amt) and strlen($debtors_name)<30 and strlen($debtors_amt)<11 
		and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$debtors_name) and preg_match('/^[0-9]*$/',$debtors_amt)) {

		$debtors_query=$dbc->prepare("INSERT INTO debtors (user_id,debtors_name,debtors_amt,debtors_date) VALUES (?,?,?,?)");
		$debtors_query->bind_param('ssss',$_SESSION['id'],$debtors_name,$debtors_amt,$date);
		$debtors_query->execute();

		success("Added $debtors_name $debtors_amt");
	}
	else {
		fail("Please enter proper name and amount");
	}

}

?>