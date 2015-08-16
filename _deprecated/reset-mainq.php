<!DOCTYPE html>
<html>
<head>
	<title>Reset</title>
</head>
<body>
<p>
<?php 
if (isset($_GET['key'])) if ($_GET['key']=="cupi") {
echo "PHP verzió: ".phpversion()."<br>";
echo "Csatlakozás MySQL kiszolgálóhoz<br>";
include '../database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakozás: " . mysqli_error($con));
}
else {
	echo "Sikeres csatlakozás<br>";
	echo "Tábla 'mainq' törölve (ha létezik).<br>";
	if(mysqli_num_rows(mysqli_query($con,"SHOW TABLES LIKE 'mainq'"))==1) {
		$sql = "DROP TABLE mainq";
		mysqli_query($con,$sql) or die(mysqli_error($con));
	}
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	$sql = "CREATE TABLE mainq
	(ID INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(ID),
	USERID INT,
	Q1 INT,
	Q2 INT,
	Q3 INT,
	Q4 INT,
	Q5 INT,
	Q6 INT,
	Q7 INT,
	Q8 INT,
	Q9 INT) DEFAULT CHARACTER SET = utf8";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	echo "Tábla 'mainq' elkészítve.<br>";
}
}
?>
</p>
</body>