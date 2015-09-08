<?php
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['creditors_submit'])) {
	require_once('secure.php');
	$creditors_name=Secure($_POST['creditors_name']);
	$creditors_amt=Secure($_POST['creditors_amt']);

	if(!empty($creditors_name) and !empty($creditors_amt) and strlen($creditors_name)<30 and strlen($creditors_amt)<11 
		and preg_match('/^\w*$/',$creditors_name) and preg_match('/^[0-9]*$/',$creditors_amt)) {

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
	$creditors_query2="SELECT creditors_name,creditors_amt FROM creditors WHERE user_id=".$_SESSION['id'];
	$creditors_data=mysqli_query($dbc,$creditors_query2);
	while($creditors_row=mysqli_fetch_array($creditors_data)) {
		print $creditors_row['creditors_name']." ".$creditors_row['creditors_amt']."<br>";
	}
	mysqli_close($dbc);
	?>
</details>