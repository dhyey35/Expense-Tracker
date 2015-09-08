<?php
require_once('db.php');
function Secure($variable) {
$dbc=mysqli_connect(DOMAIN,USER,PASS,DB);
return mysqli_real_escape_string($dbc,htmlentities(trim($variable)));
}
?>