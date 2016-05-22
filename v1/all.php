<!DOCTYPE html>
<html>
<link href="style.css" type="text/css" rel="stylesheet" >
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

<body class="container-fluid">
<a href=#expense_table >Expense</a> |<a href=#income_table >Income</a> |
<a href=#creditors_table >Creditors</a> |<a href=#debtors_table >Debtors</a> |
<?php

/* prints all expenses , incomes , creds , deb till date */
require_once('nav.html');
require_once('login_verifier.php');
require_once('db.php');
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);

/* expense table */
$query="SELECT expense_date, expense_name , expense_amt FROM expense WHERE user_id=".$_SESSION['id']." ORDER BY expense_date ASC";
$data=mysqli_query($dbc,$query);
print "<table id=expense_table>".
		"<caption>Expense</caption>".
		"<tr>".
		"<th>Date</th>".
		"<th>Name</th>".
		"<th>Amount</th>".
		"</tr>";
while($row=mysqli_fetch_array($data)) {
	print "<tr>".
			"<td>".$row['expense_date']."</td>".
			"<td>".$row['expense_name']."</td>".
			"<td>".$row['expense_amt']."</td>".
			"</tr>";
}
print "</table><br><br><br>";

/* income table */
$query2="SELECT income_date ,income_name,income_amt FROM income WHERE user_id=".$_SESSION['id'];
$data2=mysqli_query($dbc,$query2);
print "<table id=income_table>".
		"<caption>Income</caption>".
		"<tr>".
		"<th>Date</th>".
		"<th>Name</th>".
		"<th>Amount</th>".
		"</tr>";
while($row2=mysqli_fetch_array($data2)) {
	print "<tr>".
			"<td>".$row2['income_date']."</td>".
			"<td>".$row2['income_name']."</td>".
			"<td>".$row2['income_amt']."</td>".
			"</tr>";
}
print "</table><br><br><br>";

/* creditors table*/
$query3="SELECT creditors_date ,creditors_name,creditors_amt FROM creditors WHERE user_id=".$_SESSION['id'];
$data3=mysqli_query($dbc,$query3);
print "<table id=creditors_table>".
		"<caption>creditors</caption>".
		"<tr>".
		"<th>Date</th>".
		"<th>Name</th>".
		"<th>Amount</th>".
		"</tr>";
while($row3=mysqli_fetch_array($data3)) {
	print "<tr>".
			"<td>".$row3['creditors_date']."</td>".
			"<td>".$row3['creditors_name']."</td>".
			"<td>".$row3['creditors_amt']."</td>".
			"</tr>";
}
print "</table><br><br><br>";

/* debtors table*/
$query4="SELECT debtors_date ,debtors_name,debtors_amt FROM debtors WHERE user_id=".$_SESSION['id'];
$data4=mysqli_query($dbc,$query4);
print "<table id=detors_table>".
		"<caption>debtors</caption>".
		"<tr>".
		"<th>Date</th>".
		"<th>Name</th>".
		"<th>Amount</th>".
		"</tr>";
while($row4=mysqli_fetch_array($data4)) {
	print "<tr>".
			"<td>".$row4['debtors_date']."</td>".
			"<td>".$row4['debtors_name']."</td>".
			"<td>".$row4['debtors_amt']."</td>".
			"</tr>";
}
print "</table><br><br><br>";
?>
<body>
</html>