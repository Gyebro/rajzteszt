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
	<link rel="import" href="elements/paper-submit-button.html">
	<link rel="stylesheet" href="css/style.css" />
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Rajzteszt - Utóteszt</div>
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
	if(isset($_POST["user"]) && isset($_POST["contype"]) && isset($_POST["conyears"]) && isset($_POST["consatisf"])) {
		// Save in format: USERID, CONTYPE, CONYEARS, CONSATISF
		$sql = "INSERT INTO testq (USERID, CONTYPE, CONYEARS, CONSATISF)".
			"VALUES ('".$_POST["user"]."','".$_POST["contype"]."','".$_POST["conyears"]."','".$_POST["consatisf"]."')";
		mysqli_query($con,$sql) or die(mysqli_error($con));
		// Generate form
		$questions = array(
			"Az életem a legtöbb tekintetben közel van az ideálishoz.",
			"Általában úgy tartom magamról, hogy nagyon boldog ember vagyok.",
			"Az életkörülményeim kitûnõek.",
			"Élvezem az életet, tekintet nélkül arra, hogy mi történik és mindenbõl a legjobbat hozom ki." ,
			"Elégedett vagyok az életemmel.",
			"Eddig minden fontosat megkaptam az életben, amit csak akartam.",
			"Ha újra leélhetném az életem, szinte semmin sem változtatnék.",
			"Általában nem vagyok túlságosan boldog. Bár nem vagyok depressziós, sohasem tûnök olyan boldognak, mint amilyen lehetnék.",
			"Úgy gondolom, hogy a legtöbb társamhoz képest Én boldogabb vagyok."
		);
		echo '<form action="finish.php" method="POST">';
		echo '<input type="hidden" name="user" value="'.$_POST["user"].'"></input>';
		echo '<p>Kérlek, jelöld be, hogy milyen mértékben értesz egyet a felsorolt állításokkal!</p>';
		for ($i=1; $i<10; $i++) {
			echo "<p>".$i.". ".$questions[$i-1]."</p>";
			echo '<label for="r'.$i.'1" style="float:left; padding-right:8px">(egyáltalán nem értek egyet)</label>';
			echo '<input type="radio" name="q'.$i.'" value="1" id="r'.$i.'1"/></input><label for="r'.$i.'1">1</label>';
			echo '<input type="radio" name="q'.$i.'" value="2" id="r'.$i.'2"/></input><label for="r'.$i.'2">2</label>';
			echo '<input type="radio" name="q'.$i.'" value="3" id="r'.$i.'3"/></input><label for="r'.$i.'3">3</label>';
			echo '<input type="radio" name="q'.$i.'" value="4" id="r'.$i.'4" checked/></input><label for="r'.$i.'4">4</label>';
			echo '<input type="radio" name="q'.$i.'" value="5" id="r'.$i.'5"/></input><label for="r'.$i.'5">5</label>';
			echo '<input type="radio" name="q'.$i.'" value="6" id="r'.$i.'6"/></input><label for="r'.$i.'6">6</label>';
			echo '<input type="radio" name="q'.$i.'" value="7" id="r'.$i.'7"/></input><label for="r'.$i.'7">7 (teljesen egyetértek)</label></br>';
		}
	} else {
		die("<p>A hozzáféréshez bejelentkezés szükséges!</p>");
	}
}
?>
		<!-- End of form -->
			</br>
			<button type="submit" is="paper-submit-button">Folytatás</button>
			</br></br>
		</form>
	</div>
  </core-header-panel>
</body>
</html>