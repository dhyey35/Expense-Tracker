<?php

//add comments
// after deleting add them to a file
session_start();
print "<h2 style='color:#2e8ae6;'> Welcome ".$_SESSION['username']."</h2>";
require_once('expense.php');
print "<hr color=#22b7f6>";
require_once('income.php');
print "<hr color=#22b7f6>";
require_once('creditors.php');
print "<hr color=#22b7f6>";
require_once('debtors.php');
print "<hr color=#22b7f6>";
?>