<!DOCTYPE html>
<html>
<head>
	<title>Reset</title>
</head>
<body>
<p>
<?php 
if (isset($_GET['key'])) if ($_GET['key']=="cupi") {
echo "PHP verzi�: ".phpversion()."<br>";
echo "Csatlakoz�s MySQL kiszolg�l�hoz<br>";
include '../database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakoz�s: " . mysqli_error($con));
}
else {
	echo "Sikeres csatlakoz�s<br>";
	echo "'drawingtest' adatb�zis kiv�laszt�sa<br>";
	echo "T�bla 'testq' t�r�lve (ha l�tezik).<br>";
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
	echo "T�bla 'testq' elk�sz�tve.<br>";
}
}
?>
</p>
</body>