<?php
//add a paid/delete button
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['debtors_submit'])) {
	require_once('secure.php');
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
?>
<details>
	<summary>
		<big>Debtors</big>
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
		print $debtors_row['debtors_name']." ".$debtors_row['debtors_amt']." "."<a href=delete.php?debtors_id=".
		$debtors_row['debtors_id']."&user_id=".$_SESSION['id']." > Received</a>  <br>";
	}
	mysqli_close($dbc);
	?>
</details>