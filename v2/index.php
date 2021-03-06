<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Expense Tracker</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
		<script src="cookie.js"></script>

	</head>
	<body>
		<?php require_once("navbar.php") ?>
		<div id="holder" class="contianer-fluid">
			<!-- <div class="row"> 
				<h2 class="col-md-10" id="title">Expense Tracker</h2>
				<h3 class="col-md-2" id="username"><span id="displayName">username</span>
					<span id="v"> &#9660;<h3>
			</div>-->

			<hr />

			<p id="message"></p>
			<details id="exp_details" open>
				<summary>
					<big>Expense</big>
				</summary>
				<div id="expense" > 
					<input type="text" id="expense_name" name="expense_name" placeholder="Expense Name">
					<input type="text" id="expense_amt" name="expense_amt" placeholder="Expense Amount">
					<br>
					<br>
					<input type="submit" id="expense_submit" name="expense_submit" value="Add">
					<div id="exp_total">Total This Month: <span id="exp_total_amt"></span></div>
				</div>
			</details>

			<hr/>

			<details id="income_details">
				<summary>
					<big>Income</big>
				</summary>
				<div id="income">
					<input type="text" id="income_name" name="income_name" placeholder="Income Name">
					<input type="text" id="income_amt" name="income_amt" placeholder="Income Amount">
					<br>
					<br>
					<input type="submit" id="income_submit" name="income_submit" value="Add">
					<div id="income_total">Total This Month: <span id="income_total_amt"></span></div>
					<!-- <a href="view_all.php?" -->
				</div>
			</details>

			<hr>

			<details id="creditors_details">
				<summary>
					<big>Creditors</big>
				</summary>
				<div id="creditors">
					<input type="text" id="creditors_name" name="creditors_name" placeholder="Creditors Name">
					<input type="text" id="creditors_amt" name="creditors_amt" placeholder="Creditors Amount">
					<br>
					<br>
					<input type="submit" id="creditors_submit" name="creditors_submit" value="Add">
					<div id="creditors_total">Total This Month: <span id="creditors_total_amt"></span></div>
				</div>
			</details>

			<hr>

			<details id="debtors_details">
				<summary>
					<big>Debtors</big>
				</summary>
				<div id="debtors">
					<input type="text" id="debtors_name" name="debtors_name" placeholder="Debtors Name">
					<input type="text" id="debtors_amt" name="debtors_amt" placeholder="Debtors Amount">
					<br>
					<br>
					<input type="submit" id="debtors_submit" name="debtors_submit" value="Add">
					<div id="debtors_total">Total This Month: <span id="debtors_total_amt"></span></div>
				</div>
			</details>
		</div>

		<script type="text/javascript">
			//To-Do
			//new for showing all when clicked on All
			//internal link for exp,income... in show all
			//login and signup page
			//deletion of cred and deb
			//add navbar.php to all pages
			
		$(document).ready(function(){
			//displaying username
			$("#displayName").html(Cookies.get("username"));

			/* retriving total of exp,inc..*/
			function showTotal(){
			$.ajax({
				url:'total.php',
				type:'post',
				success:function(data){
					data = data.split("=");
					$("#exp_total_amt").html(data[0]);
					$("#income_total_amt").html(data[1]);
					$("#creditors_total_amt").html(data[2]);
					$("#debtors_total_amt").html(data[3]);
				}
			});
			}
			
			//calling above func
			showTotal();

			function clearFields(){
				$("input[type=text]").val(" ");
			}

			$("input[id$=amt]").keypress(function(key){
				if(key.keyCode == 13){
					$(this).next().next().next().trigger("click");
				}
			});

			//closes all other details tag except the one that is clicked
			$('details').click(function (event) {
			    $('details').not(this).removeAttr("open");  
			});
			
			//ajax call when user presses button
			$("#expense_submit").click(function(){
				var exp_data = $("#expense :input").serializeArray();
				addData(exp_data);
			});
			$("#income_submit").click(function(){
				var income_data = $("#income :input").serializeArray();
				addData(income_data);
			});
			$("#creditors_submit").click(function(){
				var creditors_data = $("#creditors :input").serializeArray();
				addData(creditors_data);
			});
			$("#debtors_submit").click(function(){
				var debtors_data = $("#debtors :input").serializeArray();
				addData(debtors_data);
			});

			//single func for all ajax calls to submit data
			function addData(dataToAdd){
				var name = dataToAdd[0].value;
				var amt = dataToAdd[1].value;
				if(name == null || name == "" || isNaN(amt)){
					$("#message").html("Please enter proper Name and Amount");
					return;
				}
				else if(name.length > 30 || amt.length > 11){
					$("#message").html("Name should not be more than 30 characters and Amount 11 characters");
					return;
				}
			$.ajax({
						type:'post',
						url:'backend.php',
						data:dataToAdd,
						success:function(data){
							$("#message").html(data);
							showTotal();
							clearFields();
						},
						error: function(XMLHttpRequest){
							console.log("error");
							$("#message").html("Internet Error");
							if(XMLHttpRequest.readyState == 0){
								$("#message").html("Please check internet Connection !!");
							}else{
								$("#message").html("Error Occured !!");
							}
							
						}
					})
			}
		});
		</script>
	</body>
</html>