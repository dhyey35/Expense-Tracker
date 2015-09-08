<?php
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['income_submit'])) {
	require_once('secure.php');
	$income_name=Secure($_POST['income_name']);
	$income_amt=Secure($_POST['income_amt']);
	
	if(!empty($income_name) and !empty($income_amt) and strlen($income_name)<30 and strlen($income_amt)<11 and preg_match('/^\w*$/',$income_name) and preg_match('/^[0-9]*$/',$income_amt)) {
		
		$date=getdate();
		$date=$date['mday'].'-'.$date['mon'].'-'.$date['year'];

		$income_query=$dbc->prepare("INSERT INTO income (user_id,income_name,income_amt,income_date) VALUES (?,?,?,?)");
		$income_query->bind_param('ssss',$_SESSION['id'],$income_name,$income_amt,$date);
		$income_query->execute();

		print ("Added $income_name $income_amt");

	}
	else {
		print("Please enter income name and amount properly");
	}
	
	

}

?>
<details>
	<summary>
		<big>Income</big>
	</summary>
	<form method="post">
		<input type="text" name="income_name" placeholder="Income Name" >
		<input type="text" name="income_amt" placeholder="Income Amount" >
		<br><br>
		<input type="submit" name="income_submit" value="Add Income">
	</form>
	<?php
	/* storing month and year in date to get incomes of this month and show them below add income button */
	$date=getdate();
	$date=$date['mon'].'-'.$date['year'];

	$income_query2="SELECT income_name,income_amt FROM income WHERE user_id=".$_SESSION['id']." AND income_date LIKE '%$date'";
	$income_data=mysqli_query($dbc,$income_query2);
	while($income_row=mysqli_fetch_array($income_data)) {
		print $income_row['income_name']." ".$income_row['income_amt']."<br>";
	}
	mysqli_close($dbc);
	?>
</details>