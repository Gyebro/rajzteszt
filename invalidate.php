<!DOCTYPE html>
<html>
<head>
	<title>Invalidate user</title>
</head>
<body>
<p>
<?php 
if (isset($_GET['key'])) if ($_GET['key']=="cupi") {
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) { die("Sikertelen csatlakozás: " . mysqli_error($con)); }
else {
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	if (isset($_GET['userid'])) { $userid = $_GET['userid']; 
		$sqlu = "UPDATE users SET INVALID = 1 WHERE ID = ".$userid;
		mysqli_query($con,$sqlu) or die(mysqli_error($con));
		echo "Felhasználó ".$userid." érvénytelenítve!";
	}	
}
}
?>
</p>
</body>