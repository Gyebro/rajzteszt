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
	echo "<h2>Boldogság</h2>";
	// Rajzok lekérése
	$sql = "SELECT * FROM mainq WHERE USERID = ".$_GET["userid"];
	$boldogsag = 0;
	$blist = ["Az életem a legtöbb tekintetben közel van az ideálishoz.",
			"Általában úgy tartom magamról, hogy nagyon boldog ember vagyok.",
			"Az életkörülményeim kitűnőek.",
			"Élvezem az életet, tekintet nélkül arra, hogy mi történik és mindenből a legjobbat hozom ki." ,
			"Elégedett vagyok az életemmel.",
			"Eddig minden fontosat megkaptam az életben, amit csak akartam.",
			"Ha újra leélhetném az életem, szinte semmin sem változtatnék.",
			"Általában nem vagyok túlságosan boldog. Bár nem vagyok depressziós, sohasem tűnök olyan boldognak, mint amilyen lehetnék.",
			"Úgy gondolom, hogy a legtöbb társamhoz képest Én boldogabb vagyok."];
	echo "<table>";
	if ($rowb = mysqli_fetch_array(mysqli_query($con,$sql))) {
		for($k = 2; $k < 11; $k++) {
			echo "<tr><td>".$blist[$k-2]."</td><td>".$rowb[$k]."</td></tr>"; 
			if ($k == 9) {
				$boldogsag += -$rowb[$k];
			} else {
				$boldogsag += $rowb[$k];
			}
		}
	}
	echo "<tr><td><b>Összesen</b></td><td>".$boldogsag."</td></tr>"; 
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