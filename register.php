<!DOCTYPE html>
<html>
<head>
	<title>Rajzteszt</title>
	<script src="bower_components/webcomponentsjs/webcomponents.min.js"></script>
	<link rel="import" href="bower_components/core-header-panel/core-header-panel.html">
	<link rel="import" href="bower_components/font-roboto/roboto.html">
	<link rel="import" href="bower_components/core-icons/core-icons.html">
	<link rel="import" href="bower_components/paper-fab/paper-fab.html">
	<link rel="import" href="bower_components/paper-button/paper-button.html">
	<!-- Custom elements -->
	<link rel="import" href="paper-submit-button.html">
	<link rel="stylesheet" href="css/style.css" />
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Rajzteszt - Regisztráció</div>
    <div class="content">
<?php 
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("<p>Adatbázis hiba.</p>");
}
else {
	// Set UTF8
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	if(isset($_POST["name"]) && isset($_POST["gender"]) && isset($_POST["age"]) && isset($_POST["handedness"])) {
		// Get browsername and version if present
		$browsername = "Unknown"; $browserversion = "0";
		if(isset($_POST["browsername"])) 	{$browsername = $_POST["browsername"];}
		if(isset($_POST["browserversion"])) {$browserversion = $_POST["browserversion"];}
		$sql = "INSERT INTO users (NAME,AGE,GENDER,EDUCATION,HANDEDNESS,PSYCHO,BROWSERNAME,BROWSERVERSION) VALUES ('".$_POST["name"]."','".$_POST["age"].
			"','".$_POST["gender"]."','".$_POST["education"]."','".$_POST["handedness"]."','".$_POST["psycho"]."','".$browsername."','".$browserversion."')";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		$newid = mysqli_insert_id($con);
		echo "<p>Sikeres regisztráció!</p>";
		echo "<p>Az azonosítód: <b>".$newid."</b></br>Kérlek jegyezd fel az azonosítót, amennyiben később meg szeretnéd tekinteni rajzaid és eredményeid.</p>";
		echo "<p>A teszt 24 rövid rajzfeladatból valamint egy kérdéssorból áll. Az instrukciót olvasd el figyelmesen majd rajzolj, ahogy jól esik.</p>";
		echo "<paper-button raised><a href='test.php?user=".$newid."&stage=1'>A teszt indítása</a></paper-button>";
	} else {
		die("<p>Hibás adatokat adott meg!</p>");
	}
}
?>
	</div>
  </core-header-panel>
</body>
</html>