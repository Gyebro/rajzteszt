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
	<link rel="stylesheet" href="css/style.css" />
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Rajzteszt</div>
    <div class="content">
<?php 
if(isset($_GET["id"])) {
	include 'database.inc.php';
	$con = mysqli_connect($host, $dbuser, $dbpass, $db);
	if (!$con) { die("Sikertelen csatlakozás: " . mysqli_error($con)); }
	else {
		mysql_select_db($db, $con);
		$sql = "SET NAMES 'utf8'";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		$sql = "SELECT * FROM drawings WHERE ID = ".$_GET["id"];
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		$row = mysqli_fetch_array($result);
		echo "<script>";
		echo "var drawingid=".$_GET["id"].";";
		echo "var drawing = JSON.parse('".$row['DATA']."');";
		echo "</script>";
		echo "<p>A(z) <b>".$_GET["id"]."</b> azonosítójú rajz megtekintése</p>";
	}
} else {
	die("<p>Nincs megadva a rajz azonosítója!</p>");
}
?>
		<div id="canvasholder">
			<canvas id="drawing"></canvas>
		</div>
		<div id="chartContainer" style="height: 300px; width: 100%;"></div>
		<script type="text/javascript" src="js/canvasjs.min.js"></script>
		<script src="js/view.js"></script>
	</div>
  </core-header-panel>
</body>
</html>