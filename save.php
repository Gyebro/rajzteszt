<?php 
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	// Sikertelen csatlakozás
	die("Error 1 - Can't connect!");
}
else {
	// Set UTF8
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	// Get POST data
	if(isset($_POST["id"]) && isset($_POST["stage"]) && isset($_POST["data"])
		&& isset($_POST["sel1"]) && isset($_POST["sel2"]) && isset($_POST["sel3"])) {
		// Save in format: USERID, STAGE, DATA,	SEL1, SEL2, SEL3
		$sql = "INSERT INTO drawings (USERID, STAGE, DATA, SEL1, SEL2, SEL3, COMMENT)".
			"VALUES ('".$_POST["id"]."','".$_POST["stage"].
			"','".$_POST["data"]."','".$_POST["sel1"]."','".$_POST["sel2"]."','".$_POST["sel3"]."','".$_POST["comment"]."')";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		echo "OK";
	} else {
		die("Error 2 - Nothing to save!");
	}
}
?>