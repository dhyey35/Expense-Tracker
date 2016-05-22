<?php
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['expense_name']) and isset($_POST['expense_amt'])){
	require_once('secure.php');
	$expense_name=Secure($_POST['expense_name']);
	$expense_amt=Secure($_POST['expense_amt']);
	
	
	if(!empty($expense_name) and !empty($expense_amt) and strlen($expense_name)<30 and strlen($expense_amt)<11 and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$expense_name) and preg_match('/^[0-9]*$/',$expense_amt)) {

		$date=getdate();
		$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];
		
		

		$exp_query=$dbc->prepare("INSERT INTO expense (user_id,expense_name,expense_amt,expense_date) VALUES(?,?,?,?)");
		$exp_query->bind_param('ssss',$_SESSION['id'],$expense_name,$expense_amt,$date);
		$exp_query->execute() or fail("exp","db prob");
		
		success("Added $expense_name $expense_amt");
		
	}
	else {
		fail("exp","Please enter name and amount properly");
	}
}
else{
	fail("exp","If not working");
}

function fail($where,$message){
	die($where." ".$message);
}
function success($message){
	die($message);
	//die(json_encode(array("status" => "success","message" => $message)));
}
?>