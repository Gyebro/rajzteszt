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
    <div class="content" id="printarea" style="width:90% !important;">
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
	$ordernames2 = ["Boldogtalanok","Boldogok"];
	$orderlongnames = ["Boldogtalanok (csökkenő sorrend)","Boldogok (csökkenő sorrend)"];
	$limits = ["5","10","20","27"];
	// Defaults
	$stage0 = 1;
	$drw0 = $drwnames[0];
	$index0 = "SWLS";
	$order0 = "ASC";
	$limit0 = "27";
	if(isset($_GET["stage"])) { $stage0 = $_GET["stage"]; }
	if(isset($_GET["index"])) { $index0 = $_GET["index"]; }
	if(isset($_GET["order"])) { $order0 = $_GET["order"]; }
	if(isset($_GET["limit"])) { $limit0 = $_GET["limit"]; }
	// Form
	echo '<form action="filter.php" method="GET"><select name="stage">';
	for ($i=0; $i<sizeof($drwnames); $i++) {
		echo '<option id="st'.$i.'" value="'.($i+1).'" ';
		if($i == $stage0-1) { echo 'selected'; $drw0 = $drwnames[$i]; }
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
		if($ordernames[$i] == $order0) { echo 'selected'; $ord0 = $ordernames2[$i]; }
		echo '>'.$orderlongnames[$i].'</option>';
	}
	echo '</select> <select name="limit">';
	for ($i=0; $i<sizeof($limits); $i++) {
		echo '<option id="'.$limits[$i].'" value="'.$limits[$i].'" ';
		if($limits[$i] == $limit0) { echo 'selected'; }
		echo '>'.$limits[$i].'</option>';
	}
	echo '</select> <input type="submit" value="OK">';
	echo '<input id="sizeslider" type="range" min="100" max="500" value="228">';
	echo '<input type="button" value="Mentés" onclick="PrintElem(\'#printarea\',\''.$stage0.'_'.$drw0.'_'.$index0.'_'.$ord0.'\')" />';
	echo '</form>';
	if(isset($_GET["stage"]) && isset($_GET["index"]) && isset($_GET["order"]) && isset($_GET["limit"])) {
		$stage = $_GET["stage"];
		$index = $_GET["index"];
		$order = $_GET["order"];
		$limit = $_GET["limit"];
		// Lekérés
		$sql = "SET NAMES 'utf8'";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		
		
		if($order == $ordernames[0]) {
			// Ascending (sad people)
			$sql = "SELECT * FROM ( ".
			"SELECT * FROM results WHERE STAGE = ".$stage." AND INVALID = 0 ORDER BY ".$index." ".$order." LIMIT ".$limit.
			") sub ORDER BY ".$index." DESC";
		} else {
			// Descending (happy people)
			$sql = "SELECT * FROM results WHERE STAGE = ".$stage." AND INVALID = 0 ORDER BY ".$index." ".$order." LIMIT ".$limit;
		}
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		while ($row = mysqli_fetch_array($result)) {
			$happy = $row["HAPPY"];
			$swls = $row["SWLS"];
			$shs = $row["SHS"];
			$did = $row["DRAWINGID"];
			$user = $row["USERID"];
			$sqlgender = "SELECT * FROM users WHERE ID = ".$user;
			$userrow = mysqli_fetch_array(mysqli_query($con,$sqlgender));
			$gender = $userrow["GENDER"];
			if ($gender == "male") { $gender = "ffi"; }
			if ($gender == "female") { $gender = "nő"; }
			$age = $userrow["AGE"];
			echo "<div class='filterbox'><img src='rajz/".$did.".svg'><p>SWLS=".$swls.", SHS=".$shs.", HAPPY=".$happy.",</br>U = ".$user." (".$gender.", ".$age.")</p></div>";
			//echo "SWLS = ".$row["SWLS"].", SHS = ".$row["SHS"].", HAPPY = ".$row["HAPPY"].", ID = ".$row["DRAWINGID"]."</br>";
		}
	} else {
		die("<p>Válassz rajzot és indexet, majd kattints az OK gombra!</p>");
	}
}
?>
		<script src="js/size.js"></script>
    </div>
  </core-header-panel>
</body>
</html>