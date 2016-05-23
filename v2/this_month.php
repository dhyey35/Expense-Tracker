<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Expense Tracker</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css">
	</head>
<?php
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
date_default_timezone_set('Asia/Calcutta');
/* date to select exp of these month by passing in month and year */

$date=getdate();
/* saving only month and year in date */
$date=$date['mon'].'-'.$date['year'];
?>
<body>
	<div class="container-fluid text-center">
		<h2 id="title">List Of This Month</h2>
		<table align="center" id="exp_table" class="text-center table table-striped">
			<caption class="text-center">Expenses</caption>
			<tr>
				<th class="text-center">Name</th>
				<th class="text-center">Amount</th>
			</tr>
<?php

	$exp_query2="SELECT expense_name , expense_amt FROM expense WHERE user_id=".$_SESSION['id']." AND expense_date LIKE '%$date' ";
	$exp_data=mysqli_query($dbc,$exp_query2);
	/* printing exp of this month */
	while($exp_rows=mysqli_fetch_array($exp_data)) {
		print "<tr>".
				"<td>".
					$exp_rows['expense_name'].
				"</td>".
				"<td>".	
					$exp_rows['expense_amt'].
				"</td>".
				"</tr>";
	}
	print "</table>";
	print "<a href=all.php/#expense_table >View all expenses</a>";

?>
	
	</div>
</body>
</html>