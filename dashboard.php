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
	<p><a href="check.php?key=" target="_blank">Tesztek ellenőrzése</a></p>
	<p><a href="generateall.php?key=" target="_blank">Rajzok generálása</a></p>
<?php 
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakozás: " . mysqli_error($con));
}
else {
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	if (isset($_GET["valid"])) {
		$sql = "SELECT * FROM users WHERE INVALID = 0 ORDER BY HAPPY DESC";
	} else {
		$sql = "SELECT * FROM users";
	}
	$result = mysqli_query($con,$sql) or die(mysqli_error($con));
	echo "<table style='width:100%'>";
	$testcount = 0;
	$fullcount = 0;
	$boredcount = 0;
	$failedcount = 0;
	$drawingcount = 0;
	$boldogsagsum = 0;
	echo "<tr><th>Idő</th><th>ID</th><th>Név</th><th>Nem</th><th>Életkor</th><th>Isk.</th><th>Kapcs.</th><th>Psycho</th><th>Rajzok</th><th>SWLS</th><th>SHS</th><th>D%</th><th>Happy</th><th>Részletek</th>".
	/*"<th>Invalidate</th>".*/
	"</tr>";
	while ($row = mysqli_fetch_array($result)) {
		// Rajzok száma
		$sql2 = "SELECT * FROM drawings WHERE USERID = ".$row[0];
		$rajzok = mysqli_num_rows(mysqli_query($con,$sql2));
		if($rajzok <= 3) {
			// Skip
		} else {
			$testcount++;
			$drawingcount += $rajzok;
			$invalid = $row["INVALID"];
			if ($invalid == 0) {
				echo '<tr style="background: rgb(200,255,200);">';
				$fullcount++;
			} else {
				echo '<tr style="background: rgb(220,220,220);">';
				$failedcount++;
			}
			$sql3 = "SELECT * FROM tests WHERE USERID = ".$row[0];
			$kapcstipus = mysqli_fetch_array(mysqli_query($con,$sql3))["Q10"];
			echo "<td>".$row["TIMESTAMP"]."</td><td>".$row["ID"]."</td><td>".$row["NAME"]."</td><td>".$row["GENDER"]."</td><td>".$row["AGE"]."</td><td>".$row["EDUCATION"]."</td><td>".$kapcstipus."</td><td>".$row["PSYCHO"]."</td>";
			echo "<td>".$rajzok."</td>";
			echo "<td>".$row["SWLS"]."</td><td>".$row["SHS"]."</td><td>".$row["DIFFPERCENT"]."</td><td>".$row["HAPPY"]."</td>";
			//echo "<td>".$row['BROWSERNAME']." ".$row['BROWSERVERSION']."</td>";
			echo "<td><a target='_blank' href='details.php?userid=".$row[0]."'>Részletek</a></td>";
			//echo "<td><a target='_blank' href='invalidate.php?key=cupi&userid=".$row[0]."'>Invalid</a></td>";
			echo "</tr>";
		}
	}
	echo "</table>";
	echo "<table>";
	echo "<tr><td>Tesztek száma</td><td>".$testcount."</td></tr>";
	echo "<tr style='background: rgb(200,255,200);'><td>Teljes tesztek száma</td><td>".$fullcount."</td></tr>";
	echo "<tr style='background: rgb(255,200,200);'><td>Félkész tesztek száma</td><td>".$failedcount."</td></tr>";
	echo "<tr><td>Rajzok száma</td><td>".$drawingcount."</td></tr>";
	echo "</table>";
}
?>
    </div>
  </core-header-panel>
</body>
</html>