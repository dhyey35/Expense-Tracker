<?php
require_once('login_verifier.php');

if(isset($_POST['expense_submit'])) {
	require_once('secure.php');
	$expense_name=Secure($_POST['expense_name']);
	$expense_amt=Secure($_POST['expense_amt']);
	
	
	if(!empty($expense_name) and !empty($expense_amt) and strlen($expense_name)<30 and strlen($expense_amt)<11 and preg_match('/^\w*$/',$expense_name) and preg_match('/^[0-9]*$/',$expense_amt)) {
		$date=getdate();
		$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];
		
		require_once('db.php');
		$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);

		$exp_query=$dbc->prepare("INSERT INTO expense (user_id,expense_name,expense_amt,expense_date) VALUES(?,?,?,?)");
		$exp_query->bind_param('ssss',$_SESSION['id'],$expense_name,$expense_amt,$date);
		$exp_query->execute();
		
		print("Added $expense_name $expense_amt");
		mysqli_close($dbc);
	}
	else {
		print("Please enter name and amount properly ");
	}
}
?>

<details>
	<summary>
		<big>Expense</big>
	</summary>
	<form method="post">
	<input type="text" name="expense_name" placeholder="Expense Name">
	<input type="text" name="expense_amt" Placeholder="Expense Amount">
	<br>
	<br>
	<input type="submit" name="expense_submit" value="ADD EXPENSE" >
	 </form>
	<?php
	/* date to select exp of these month by passing in month and year */
	date_default_timezone_set('Asia/Calcutta');
	$date=getdate();
	/* saving only month and year in date */
	$date=$date['mon'].'-'.$date['year'];
	
	require_once('db.php');
	$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);

	$exp_query2="SELECT expense_name , expense_amt FROM expense WHERE user_id=".$_SESSION['id']." AND expense_date LIKE '%$date' ";
	$exp_data=mysqli_query($dbc,$exp_query2);
	while($exp_rows=mysqli_fetch_array($exp_data)) {
		print $exp_rows['expense_name']." ".$exp_rows['expense_amt']."<br>";
	}
	?>
</details>
