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
		/* writing them to file before deleting */
		$save_creditors=$dbc->prepare("SELECT * FROM creditors WHERE user_id=? AND creditors_id=?");
		$save_creditors->bind_param('ss',$user_id,$creditors_id);
		$save_creditors->execute();
		$result=$save_creditors->get_result();
		$row=mysqli_fetch_array($result);
		/* saving data to csv file*/
		if(file_exists("deleted.csv")) {
			$creditors_content="creditor,".$row['user_id'].",".$row['creditors_name'].",".$row['creditors_amt'];
			file_put_contents("deleted.csv",$creditors_content."\n",FILE_APPEND);
		} 
		else {
			print "Error while deleting creditors .";
		}

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
		/* saving debtors in a file before deleting */
		$save_debtors=$dbc->prepare("SELECT * FROM debtors WHERE user_id=? AND debtors_id=?");
		$save_debtors->bind_param('ss',$user_id,$debtors_id);
		$save_debtors->execute();
		$result=$save_debtors->get_result();
		$row=mysqli_fetch_array($result);
		/* appending to deleted.csv */
		if(file_exists("deleted.csv")) {
			$debtors_content="debtor,".$row['user_id'].",".$row['debtors_name'].",".$row['debtors_amt'];
			file_put_contents("deleted.csv",$debtors_content."\n",FILE_APPEND);
		}
		else {
			print "There was a problem deleting your debtors";
		}

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
