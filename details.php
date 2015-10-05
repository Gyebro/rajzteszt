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
	<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js" > </script> 
	<script type="text/javascript">
		function PrintElem(elem,title)
		{
			Popup($(elem).html(),title);
		}
		function Popup(data,title) 
		{
			var mywindow = window.open('', 'my div', 'height=400,width=600');
			mywindow.document.write('<html><head><title>'+title+'</title>');
			/*optional stylesheet*/ 
			mywindow.document.write('<link rel="stylesheet" href="css/style.css" type="text/css" />');
			mywindow.document.write('</head><body>');
			mywindow.document.write(data);
			mywindow.document.write('</body></html>');

			mywindow.document.close(); // necessary for IE >= 10
			mywindow.focus(); // necessary for IE >= 10

			mywindow.print();
			mywindow.close();

			return true;
		}
	</script>
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Dashboard</div>
    <div class="content" style="width:90% !important;">
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
	$uid = $row["ID"];
	echo "<h2>Teszt összesítő</h2>";
	echo '<input id="sizeslider" type="range" min="100" max="500" value="228">';
	echo '<input type="button" value="Mentés" onclick="PrintElem(\'#printarea\',\''.$row["HAPPY"].'_teszt_osszesito_'.$row["ID"].'\')" />';
	echo '</div><div id="printarea" class="content" style="width:90% !important;"">';
	echo '<p>';
	echo '<b>Azonosító</b>: '.$row["ID"].', ';
	echo '<b>Nem</b>: '.$row["GENDER"].', ';
	echo '<b>Életkor</b>: '.$row["AGE"].', ';
	echo '<b>Végzettség</b>: '.$row["EDUCATION"].', ';
	echo '<b>Psycho</b>: '.$row["PSYCHO"].'</br>';
	echo '<b>SWLS</b>: '.$row["SWLS"].', ';
	echo '<b>SHS</b>: '.$row["SHS"].', ';
	echo '<b>HAPPY</b>: '.$row["HAPPY"].', ';
	echo '<b>Kitöltve</b>: '.$row["TIMESTAMP"].'.';
	echo '</p>';
	$drwnames = ["düh","magány","szomorúság","boldogság érzése","hangulatom az utóbbi időben","magadat majd párodat","magadat és párodat","legjobb barátodat","számodra ideális partnert","akivel konfliktusban állsz","legfontosabb problémádra","ugyanez a probléma egy év múlva","párodat öt év múlva","gyenge énedet és erős énedet","boldogságot","pénzt",  "szexualitást","munkádat",  "egészségedet","szomorúságot","szabadságot","hálát",  "spiritualitást","hangulatodat az utóbbi időben"];
	// Rajzok lekérése
	$sql = "SELECT * FROM drawings WHERE userid = ".$_GET["userid"];
	$rajzok = mysqli_num_rows(mysqli_query($con,$sql));
	if ($rajzok > 0) {
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		while ($row = mysqli_fetch_array($result)) {
			$drwid = $row["ID"];
			$drwstage = $row["STAGE"];
			$drwcomment = $row["COMMENT"];
			if (strlen($drwcomment)==0) {$drwcomment = "–"; }
			echo "<div class='filterbox'><img src='rajz/".$drwid.".svg'><p>".$drwnames[$drwstage-1]."</br>(Megj: ".$drwcomment.")</p></div>";
		}
	}
	echo '</div><div class="content" style="width:90% !important;"">';
	// Combo rajzok
	echo "<div class='filterbox'><img src='rajz/combo".$uid."a.svg'></div>";
	echo "<div class='filterbox'><img src='rajz/combo".$uid."b.svg'></div>";
	echo "<div class='filterbox'><img src='rajz/combo".$uid."c.svg'></div>";
	echo "<div class='filterbox'><img src='rajz/combo".$uid."d.svg'></div>";
	echo '</div><div class="content" style="width:90% !important;"">';
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
			echo "<tr><td>".$rowq['ID']."</td><td>".$rowq['TEXT']."</td><td>".$rowb["Q".$i]."</td></tr>"; 
			$i++;
		}
	}
	echo "</table>";
}
} else {
	echo "<p>Nincs megadva azonosító!</p>";
}
?>
	<script src="js/size.js"></script>
    </div>
  </core-header-panel>
</body>
</html>