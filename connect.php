<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "btl";

//Create connect server
$conn = mysql_connect($servername, $username, $password) or die ("loi connect:".mysql_error());

//Select db
$db = mysql_select_db($dbname, $conn) or die ("loi select:".mysql_error());

?>