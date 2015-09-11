<?php

require_once('login_verifier.php');
require_once('secure.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);

/* Deleting creditors */

if(isset($_GET['creditors_id']) and isset($_GET['user_id'])) {
	
	$creditors_id=Secure($_GET['creditors_id']);
	$user_id=Secure($_GET['user_id']);
	/* ensuring that the user delets only his creditors */
	if($_SESSION['id']==$_GET['user_id']) {
		$paid_query=$dbc->prepare("DELETE FROM creditors WHERE user_id=? AND creditors_id=?");
		$paid_query->bind_param('ss',$user_id,$creditors_id);
		$paid_query->execute();

		print ("<big>Deleted . <a href=index.php>Go Back</a> </big> ");
	}
	else {
		print ("Please only delete your creditors");
	}
}

/* Deleting debtors */

else if(isset($_GET['debtors_id']) and isset($_GET['user_id'])) {
	
	$debtors_id=Secure($_GET['debtors_id']);
	$user_id=Secure($_GET['user_id']);
	/* ensuring that the user delets only his debtors */
	if($_SESSION['id']==$_GET['user_id']) {
		$received_query=$dbc->prepare("DELETE FROM debtors WHERE user_id=? AND debtors_id=?");
		$received_query->bind_param('ss',$user_id,$debtors_id);
		$received_query->execute();

		print ("<big>Deleted . <a href=index.php>Go Back</a> </big> ");
	}
	else {
		print ("Please only delete your debtors");
	}
}
else {
	print "Please select a creditor or debtor to delete <a href=index.php> Go back</a>";
}
?>
