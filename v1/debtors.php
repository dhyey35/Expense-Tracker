<script>
	function bigger(){
		document.getElementById("title").style.fontSize="25px";
	}
</script>
<?php
//add a paid/delete button
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
require_once('secure.php');
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['debtors_submit'])) {
	
	$debtors_name=Secure($_POST['debtors_name']);
	$debtors_amt=Secure($_POST['debtors_amt']);

	if(!empty($debtors_name) and !empty($debtors_amt) and strlen($debtors_name)<30 and strlen($debtors_amt)<11 
		and preg_match('/^[A-Za-z0-9\.\-\s]*$/',$debtors_name) and preg_match('/^[0-9]*$/',$debtors_amt)) {

		$date=getdate();
		$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];

		$debtors_query=$dbc->prepare("INSERT INTO debtors (user_id,debtors_name,debtors_amt,debtors_date) VALUES (?,?,?,?)");
		$debtors_query->bind_param('ssss',$_SESSION['id'],$debtors_name,$debtors_amt,$date);
		$debtors_query->execute();

		print("Added $debtors_name $debtors_amt");
	}
	else {
		print("Please enter proper name and amount");
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
		$save_debtors->execute() or die("Error deleting debtors 1");
		$result=$save_debtors->get_result() or die("Error debtors 2");
		$row=mysqli_fetch_array($result);
		if(empty($row)) {
			print("Debtors do not exist !! ");
			header("Location: http://localhost/expense/index.php");//change
			die();
		}
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

		print ("Deleted ");
	}
	else {
		print ("Please only delete your debtors");
	}
}
?>
<details ontoggle="bigger();">
	<summary>
		<big id="title">Debtors</big>
	</summary>
	<form method="post">
		<input type="text" name="debtors_name" placeholder="Debtor's Name">
		<input type="text" name="debtors_amt" placeholder="Debtor's Amount">
		<br>
		<br>
		<input type="submit" name="debtors_submit" value="Add Debtor">
	</form>
	<?php
	/* show all debtors */
	$debtors_query2="SELECT debtors_id,debtors_name,debtors_amt FROM debtors WHERE user_id=".$_SESSION['id'];
	$debtors_data=mysqli_query($dbc,$debtors_query2);
	while($debtors_row=mysqli_fetch_array($debtors_data)) {
		print $debtors_row['debtors_name']." ".$debtors_row['debtors_amt']." "."<a href=index.php?debtors_id=".
		$debtors_row['debtors_id']."&user_id=".$_SESSION['id']." > Received</a>  <br>";
	}
	print "<a href=all.php/#debtors_table>View all debtors</a>";
	mysqli_close($dbc);
	?>
</details>