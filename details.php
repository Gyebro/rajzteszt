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
    <div class="content">
<?php 
if(isset($_GET["userid"])) {
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakozás: " . mysqli_error($con));
}
else {
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	$sql = "SELECT * FROM users WHERE id = ".$_GET["userid"];
	$row = mysqli_fetch_array(mysqli_query($con,$sql));
	echo "<h2>Személyes adatok</h2>";
	echo "<table>";
	echo "<tr><td>Név</td><td>".$row["NAME"]."</td></tr>";
	echo "<tr><td>Nem</td><td>".$row["GENDER"]."</td></tr>";
	echo "<tr><td>Életkor</td><td>".$row["AGE"]."</td></tr>";
	echo "<tr><td>Végzettség</td><td>".$row["EDUCATION"]."</td></tr>";
	echo "<tr><td>Kezesség</td><td>".$row["HANDEDNESS"]."</td></tr>";
	echo "<tr><td>Psycho</td><td>".$row["PSYCHO"]."</td></tr>";
	echo "<tr><td>Kitöltés ideje</td><td>".$row["TIMESTAMP"]."</td></tr>";
	echo "</table>";
	$drwnames = ["düh","magány","szomorúság","boldogság érzése","hangulatom az utóbbi időben","magadat majd párodat","magadat és párodat","legjobb barátodat","számodra ideális partnert","akivel konfliktusban állsz","legfontosabb problémádra","ugyanez a probléma egy év múlva","párodat öt év múlva","gyenge énedet és erős énedet","boldogságot","pénzt",  "szexualitást","munkádat",  "egészségedet","szomorúságot","szabadságot","hálát",  "spiritualitást","hangulatodat az utóbbi időben"];
	echo "<h2>Rajzok</h2>";
	// Rajzok lekérése
	$sql = "SELECT * FROM drawings WHERE userid = ".$_GET["userid"];
	$rajzok = mysqli_num_rows(mysqli_query($con,$sql));
	echo "<p>Rajzok száma: ".$rajzok."</p>";
	if ($rajzok > 0) {
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		while ($row = mysqli_fetch_array($result)) {
			$drwid = $row["ID"];
			$drwstage = $row["STAGE"];
			echo "<div class='tile'>";
			echo "<p>".$drwnames[$drwstage-1]."</p>";
			echo "<img src='rajz/".$drwid.".svg' class='thumb'>";
			echo "</div>";
		}
	}
	echo "<h2>Kérdőív</h2>";
	// Teszteredmény
	$sql = "SELECT * FROM tests WHERE USERID = ".$_GET["userid"];
	$sqlQ = "SELECT * FROM questions";
	$questions = mysqli_query($con,$sqlQ) or die(mysqli_error($con));
	echo "<table>";
	$i = 1;
	if ($rowb = mysqli_fetch_array(mysqli_query($con,$sql))) {
		// $rowb holds the answers
		while($rowq = mysqli_fetch_array($questions)) {
			echo "<tr><td>".$rowq['TEXT']."</td><td>".$rowb["Q".$i]."</td></tr>"; 
			$i++;
		}
	}
	echo "</table>";
}
} else {
	echo "<p>Nincs megadva azonosító!</p>";
}
?>
    </div>
  </core-header-panel>
</body>
</html>