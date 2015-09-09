<!DOCTYPE html>
<html>
<head>
	<title>Rajzteszt</title>
    <script src="bower_components/webcomponentsjs/webcomponents.min.js"></script>
	<link rel="import" href="bower_components/core-header-panel/core-header-panel.html">
	<link rel="import" href="bower_components/font-roboto/roboto.html">
	<link rel="import" href="bower_components/core-icons/core-icons.html">
	<link rel="import" href="bower_components/paper-input/paper-input.html">
	<link rel="import" href="bower_components/paper-button/paper-button.html">
	<link rel="import" href="bower_components/paper-checkbox/paper-checkbox.html">
	<!-- Custom elements -->
	<link rel="import" href="elements/paper-submit-button.html">
	<link rel="import" href="elements/enabler-checkbox.html">
	<link rel="stylesheet" href="css/style.css" />
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Dashboard</div>
    <div class="content" style="width:84% !important;">
	<p><a href="generateall.php" target="_blank">Rajzok generálása</a></p>
<?php 
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakozás: " . mysqli_error($con));
}
else {
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	$sql = "SELECT * FROM users";
	$result = mysqli_query($con,$sql) or die(mysqli_error($con));
	echo "<table style='width:100%'>";
	$testcount = 0;
	$fullcount = 0;
	$boredcount = 0;
	$failedcount = 0;
	$drawingcount = 0;
	$boldogsagsum = 0;
	echo "<tr><th>Idő</th><th>ID</th><th>Név</th><th>Életkor</th><th>Psycho</th><th>Rajzok</th><th>Boldogság</th><th>Böngésző</th><th>Részletek</th></tr>";
	while ($row = mysqli_fetch_array($result)) {
		$testcount++;
		// Rajzok száma
		$sql2 = "SELECT * FROM drawings WHERE USERID = ".$row[0];
		$rajzok = mysqli_num_rows(mysqli_query($con,$sql2));
		$drawingcount += $rajzok;
		if ($rajzok >= 24) {
			echo '<tr style="background: rgb(200,255,200);">';
			$fullcount++;
		} else if ($rajzok > 0) {
			echo '<tr style="background: rgb(255,255,200);">';
			$boredcount++;
		} else {
			echo '<tr style="background: rgb(255,200,200);">';
			$failedcount++;
		}
		echo "<td>".$row[7]."</td><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[6]."</td>";
		echo "<td>".$rajzok."</td>";
		// Boldogságpont
		$sql3 = "SELECT * FROM mainq WHERE USERID = ".$row[0];
		$boldogsag = 0;
		/*if ($row3 = mysqli_fetch_array(mysqli_query($con,$sql3))) {
			for($k = 2; $k < 11; $k++) {
				if ($k == 9) {
					$boldogsag += -$row3[$k];
				} else {
					$boldogsag += $row3[$k];
				}
			}
		}*/
		echo "<td>".$boldogsag."</td>";
		// Böngésző
		echo "<td>".$row['BROWSERNAME']." ".$row['BROWSERVERSION']."</td>";
		echo "<td><a target='_blank' href='details.php?userid=".$row[0]."'>Részletek</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<table>";
	echo "<tr><td>Tesztek száma</td><td>".$testcount."</td></tr>";
	echo "<tr style='background: rgb(200,255,200);'><td>Teljes tesztek száma</td><td>".$fullcount."</td></tr>";
	echo "<tr style='background: rgb(255,255,200);'><td>Abbahagyott tesztek száma</td><td>".$boredcount."</td></tr>";
	echo "<tr style='background: rgb(255,200,200);'><td>Rajz nélküli tesztek száma</td><td>".$failedcount."</td></tr>";
	echo "<tr><td>Rajzok száma</td><td>".$drawingcount."</td></tr>";
	echo "</table>";
}
?>
    </div>
  </core-header-panel>
</body>
</html>