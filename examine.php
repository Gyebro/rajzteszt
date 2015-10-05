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
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("Sikertelen csatlakozás: " . mysqli_error($con));
}
else {
	// Form
	$drwnames = ["düh","magány","szomorúság","boldogság érzése","hangulatom az utóbbi időben","magadat majd párodat","magadat és párodat","legjobb barátodat","számodra ideális partnert","akivel konfliktusban állsz","legfontosabb problémádra","ugyanez a probléma egy év múlva","párodat öt év múlva","gyenge énedet és erős énedet","boldogságot","pénzt",  "szexualitást","munkádat",  "egészségedet","szomorúságot","szabadságot","hálát",  "spiritualitást","hangulatodat az utóbbi időben"];
	$indexnames = ["CDIST","PDIST","RRAT","INTS","CDISTREL","OVERA","OVERR","OVER2"];
	$indexlongnames = ["CDIST: középpontok távolsága","PDIST: körívek távolsága","RRAT: 'Én'/'Más' kör sugarainak aránya", "INTS: Metszési viszony", "CDIST: kp-ok relatív távolsága", "OVERA: Overlap absolute", "OVERR: Overlap relative", "OVER2 = OVERR/RRAT"];
	echo '<form action="examine.php" method="GET"><p>Rajz és index választás: </p><select name="stage">';
	for ($i=5; $i<sizeof($drwnames); $i++) {
		echo '<option id="st'.$i.'" value="'.($i+1).'">'.($i+1).": ".$drwnames[$i].'</option>';
	}
	echo '</select> <select name="index">';
	for ($i=0; $i<sizeof($indexnames); $i++) {
		echo '<option id="'.$indexnames[$i].'" value="'.$indexnames[$i].'">'.$indexlongnames[$i].'</option>';
	}
	echo '</select> <input type="submit" value="OK"></form>';
	if(isset($_GET["stage"]) && isset($_GET["index"])) {
		$stage = $_GET["stage"];
		$index = $_GET["index"];
		$min = -1000; $max = 1000;
		if (isset($_GET["min"])) { $min = $_GET["min"]; }
		if (isset($_GET["max"])) { $max = $_GET["max"]; }
		// Lekérés
		$sql = "SET NAMES 'utf8'";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		$sql = "SELECT * FROM results WHERE STAGE = ".$stage;
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		echo "\n";
		echo "<script>";
		echo "document.getElementById('st".($stage-1)."').selected = true;";
		echo "document.getElementById('".$index."').selected = true;";
		echo "var pts = [];";
		echo "var label = '".$index." a boldogság függvényében';";
		while ($row = mysqli_fetch_array($result)) {
			$h = $row["HAPPY"];
			$idx = $row[$index];
			$did = $row["DRAWINGID"];
			if (is_null($row[$index])) {
				// Skip point
			} else {
				if (($idx < $max) && ($idx > $min)) {
					echo "pts.push({x:".$h.",y:".$idx.",name: \"<img width='250px' src='rajz/".$did.".svg'></br> rajz: ".$did."\"});\n";
				}
			}
		}
		echo "var regression = []; var xa = 0; var ya = 0; var sx = 0; var sy = 0; var sxy = 0; var xmax=-1000;";
		echo "for (var i=0; i<pts.length; i++) { xa+=pts[i].x; ya+=pts[i].y; if(pts[i].x>xmax) {xmax = pts[i].x;} }";
		echo "xa /= pts.length; ya /= pts.length;";
		echo "for (var i=0; i<pts.length; i++) { sx+=Math.pow(pts[i].x-xa,2); ".
		"sy += Math.pow(pts[i].y-ya,2); sxy += (pts[i].x-xa)*(pts[i].y-ya); }";
		echo "var b = sxy/sx; var a=ya-b*xa;";
		echo "var rc = sxy/Math.sqrt(sx*sy);";
		echo "regression.push({x:0,y:a,name:'R = '+rc});";
		echo "regression.push({x:xmax,y:a+xmax*b,name:'R = '+rc});";
		echo "var avg = []; var counts = []; for(var i=0; i<10; i++) { avg.push(0); counts.push(0); }";
		echo "for(var i=0; i<pts.length; i++) { if (pts[i].x > 0) { avg[pts[i].x/10]+=pts[i].y; counts[pts[i].x/10]++;}  }";
		echo "var average = [];";
		echo "for(var i=0; i<10; i++) { if(counts[i]==0) { avg[i]=0; } else { avg[i]/= counts[i]; average.push({x:10*i+5,y:avg[i]}); } }"; 
		echo "console.log(average);";
		echo "</script>";
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