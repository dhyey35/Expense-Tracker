<?php
//add a paid/delete button
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
require_once('secure.php');
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['creditors_submit'])) {
	
	$creditors_name=Secure($_POST['creditors_name']);
	$creditors_amt=Secure($_POST['creditors_amt']);

	if(!empty($creditors_name) and !empty($creditors_amt) and strlen($creditors_name)<30 and strlen($creditors_amt)<11 
		and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$creditors_name) and preg_match('/^[0-9]*$/',$creditors_amt)) {

		$date=getdate();
		$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];

		$creditors_query=$dbc->prepare("INSERT INTO creditors (user_id,creditors_name,creditors_amt,creditors_date) 
			VALUES (?,?,?,?)");
		$creditors_query->bind_param('ssss',$_SESSION['id'],$creditors_name,$creditors_amt,$date);
		$creditors_query->execute();

		print("Added $creditors_name $creditors_amt");
	}
	else {
		print("Please enter proper creditors name and amount");
	}
}
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
		if(empty($row)) {
			print("Creditors do not exist !! ");
			header("Location: http://localhost/expense/index.php");//change
			die();
		}
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

		print ("Deleted");
	}
	else {
		print ("Please only delete your creditors");
	}
}

?>
<details>
	<summary>
		<big>Creditors</big>
	</summary>
	<form method="post">
		<input type="text" name="creditors_name" Placeholder="Creditor's Name" >
		<input type="text" name="creditors_amt" placeholder="Creditor's Amount" >
		<br>
		<br>
		<input type="submit" name="creditors_submit" value="Add Creditor"> 
	</form>
	<?php
	/* all creditors to be displayed */
	$creditors_query2="SELECT creditors_id,creditors_name,creditors_amt FROM creditors WHERE user_id=".$_SESSION['id'];
	$creditors_data=mysqli_query($dbc,$creditors_query2);
	while($creditors_row=mysqli_fetch_array($creditors_data)) {
		print $creditors_row['creditors_name']." ".$creditors_row['creditors_amt']." "."<a href=index.php?creditors_id=".
		$creditors_row['creditors_id']."&user_id=".$_SESSION['id']." >Paid</a>"."<br>";
	}
	print "<a href=all.php/#creditors_table>View all creditors</a>";
	mysqli_close($dbc);
	?>
</details>