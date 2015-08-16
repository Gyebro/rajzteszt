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
	echo "'drawingtest' adatbázis kiválasztása<br>";
	echo "Tábla 'testq' törölve (ha létezik).<br>";
	if(mysqli_num_rows(mysqli_query($con,"SHOW TABLES LIKE 'testq'"))==1) {
		$sql = "DROP TABLE testq";
		mysqli_query($con,$sql) or die(mysqli_error($con));
	}
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	$sql = "CREATE TABLE testq
	(ID INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(ID),
	USERID INT,
	CONTYPE VARCHAR(20),
	CONYEARS INT,
	CONSATISF INT) DEFAULT CHARACTER SET = utf8";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	echo "Tábla 'testq' elkészítve.<br>";
}
}
?>
</p>
</body>