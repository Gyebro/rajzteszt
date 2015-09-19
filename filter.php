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
<?php 
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakozás: " . mysqli_error($con));
}
else {
	// Form
	$drwnames = ["düh","magány","szomorúság","boldogság érzése","hangulatom az utóbbi időben","magadat majd párodat","magadat és párodat","legjobb barátodat","számodra ideális partnert","akivel konfliktusban állsz","legfontosabb problémádra","ugyanez a probléma egy év múlva","párodat öt év múlva","gyenge énedet és erős énedet","boldogságot","pénzt",  "szexualitást","munkádat",  "egészségedet","szomorúságot","szabadságot","hálát",  "spiritualitást","hangulatodat az utóbbi időben"];
	$indexnames = ["SWLS","SHS","HAPPY"];
	$indexlongnames = ["SWLS: Élettel való elégedettség","SHS: Szubjektív boldogság skála","HAPPY = SWLS + SHS"];
	$ordernames = ["ASC","DESC"];
	$orderlongnames = ["ASC: Növekvő (legalacsonyabbak)","DESC: Csökkenő (legmagasabbak)"];
	$limits = ["5","10","20","27"];
	// Defaults
	$stage0 = 6;
	$index0 = "SWLS";
	$order0 = "ASC";
	$limit0 = "27";
	if(isset($_GET["stage"])) { $stage0 = $_GET["stage"]; }
	if(isset($_GET["index"])) { $index0 = $_GET["index"]; }
	if(isset($_GET["order"])) { $order0 = $_GET["order"]; }
	if(isset($_GET["limit"])) { $limit0 = $_GET["limit"]; }
	// Form
	echo '<form action="filter.php" method="GET"><p>Rajz és index választás: </p><select name="stage">';
	for ($i=5; $i<sizeof($drwnames); $i++) {
		echo '<option id="st'.$i.'" value="'.($i+1).'" ';
		if($i == $stage0-1) { echo 'selected'; }
		echo '>'.($i+1).": ".$drwnames[$i].'</option>';
	}
	echo '</select> <select name="index">';
	for ($i=0; $i<sizeof($indexnames); $i++) {
		echo '<option id="'.$indexnames[$i].'" value="'.$indexnames[$i].'" ';
		if($indexnames[$i] == $index0) { echo 'selected'; }
		echo '>'.$indexlongnames[$i].'</option>';
	}
	echo '</select> <select name="order">';
	for ($i=0; $i<sizeof($ordernames); $i++) {
		echo '<option id="'.$ordernames[$i].'" value="'.$ordernames[$i].'" ';
		if($ordernames[$i] == $order0) { echo 'selected'; }
		echo '>'.$orderlongnames[$i].'</option>';
	}
	echo '</select> <select name="limit">';
	for ($i=0; $i<sizeof($limits); $i++) {
		echo '<option id="'.$limits[$i].'" value="'.$limits[$i].'" ';
		if($limits[$i] == $limit0) { echo 'selected'; }
		echo '>'.$limits[$i].'</option>';
	}
	echo '</select> <input type="submit" value="OK"></form>';
	if(isset($_GET["stage"]) && isset($_GET["index"]) && isset($_GET["order"]) && isset($_GET["limit"])) {
		$stage = $_GET["stage"];
		$index = $_GET["index"];
		$order = $_GET["order"];
		$limit = $_GET["limit"];
		// Lekérés
		$sql = "SET NAMES 'utf8'";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		$sql = "SELECT * FROM results WHERE STAGE = ".$stage." AND INVALID = 0 ORDER BY ".$index." ".$order." LIMIT ".$limit;
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		while ($row = mysqli_fetch_array($result)) {
			$h = $row["HAPPY"];
			$swls = $row["SWLS"];
			$shs = $row["SHS"];
			$happy = $shs + $swls;
			$did = $row["DRAWINGID"];
			$user = $row["USERID"];
			echo "<div class='filterbox'><img src='rajz/".$did.".svg'><p>SWLS=".$swls.", SHS=".$shs.", HAPPY=".$happy.", U = ".$user."</p></div>";
			//echo "SWLS = ".$row["SWLS"].", SHS = ".$row["SHS"].", HAPPY = ".$row["HAPPY"].", ID = ".$row["DRAWINGID"]."</br>";
		}
	} else {
		die("<p>Válassz rajzot és indexet, majd kattints az OK gombra!</p>");
	}
}
?>
		<div id="chartContainer" style="height: 500px; width: 100%;"></div>
		<script type="text/javascript" src="js/canvasjs.min.js"></script>
		<script src="js/examine.js"></script>
    </div>
  </core-header-panel>
</body>
</html>