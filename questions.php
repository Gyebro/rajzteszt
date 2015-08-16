<!DOCTYPE html>
<html>
<head>
	<title>Rajzteszt</title>
	<script src="bower_components/webcomponentsjs/webcomponents.min.js"></script>
	<link rel="import" href="bower_components/core-header-panel/core-header-panel.html">
	<link rel="import" href="bower_components/font-roboto/roboto.html">
	<link rel="import" href="bower_components/core-icons/core-icons.html">
	<link rel="import" href="bower_components/paper-input/paper-input.html">
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
	if(isset($_GET["user"])) {
		// Save in format: USERID, CONTYPE, CONYEARS, CONSATISF
		$sql = "SELECT * FROM questions";
		$result = mysqli_query($con,$sql) or die(mysqli_error($con));
		// Generate form
		echo '<form action="finish.php" method="POST">';
		echo '<input type="hidden" name="user" value="'.$_GET["user"].'"></input>';
		echo '<p>Kérlek, jelöld be, hogy milyen mértékben értesz egyet a felsorolt állításokkal!</p>';
		// Generate radios for questions
		while ($row = mysqli_fetch_array($result)) {
			$text = $row['TEXT'];
			$i = $row['ID'];
			echo "<p>".$i.". ".$text."</p>";
			if ($i == 10) {
				echo '<input type="radio" name="q'.$i.'" value="1" id="contype-a" checked/></input><label for="contype-a">Jelenleg nincs</label></br>';
				echo '<input type="radio" name="q'.$i.'" value="2" id="contype-b"/></input><label for="contype-b">Együtt járás</label></br>';
				echo '<input type="radio" name="q'.$i.'" value="3" id="contype-c"/></input><label for="contype-c">Élettársi kapcsolat</label></br>';
				echo '<input type="radio" name="q'.$i.'" value="4" id="contype-d"/></input><label for="contype-d">Házasság</label></br>';
			} else if ($i == 11) {
				echo '<paper-input-decorator label="Éve" floatingLabel><input type="number" name="conyears" id="conyears" value=0></input></paper-input-decorator>';
				echo '<paper-input-decorator label="és hónapja" floatingLabel><input type="number" name="conmonths" id="conmonths" value=0></input></paper-input-decorator>';
				echo '<input type="hidden" name="q'.$i.'" id="totalmonths" value="0"/>';
			} else {
				echo '<div style="display:none;"><input type="radio" name="q'.$i.'" value="0" checked/></div>';
				echo '<label for="r'.$i.'1" style="float:left; padding-right:8px">(egyáltalán nem értek egyet)</label>';
				echo '<input type="radio" name="q'.$i.'" value="1" id="r'.$i.'1"/></input><label for="r'.$i.'1">1</label>';
				echo '<input type="radio" name="q'.$i.'" value="2" id="r'.$i.'2"/></input><label for="r'.$i.'2">2</label>';
				echo '<input type="radio" name="q'.$i.'" value="3" id="r'.$i.'3"/></input><label for="r'.$i.'3">3</label>';
				echo '<input type="radio" name="q'.$i.'" value="4" id="r'.$i.'4"/></input><label for="r'.$i.'4">4</label>';
				echo '<input type="radio" name="q'.$i.'" value="5" id="r'.$i.'5"/></input><label for="r'.$i.'5">5</label>';
				echo '<input type="radio" name="q'.$i.'" value="6" id="r'.$i.'6"/></input><label for="r'.$i.'6">6</label>';
				echo '<input type="radio" name="q'.$i.'" value="7" id="r'.$i.'7"/></input><label for="r'.$i.'7">7 (teljesen egyetértek)</label></br>';
			}
		}
	} else {
		die("<p>A hozzáféréshez bejelentkezés szükséges!</p>");
	}
}
?>
		<!-- End of form -->
			</br>
<script>
var conyears = document.querySelector('#conyears');
var conmonths = document.querySelector('#conmonths');
var totalmonths = document.querySelector('#totalmonths');
var contypea = document.querySelector('#contype-a');
var contypeb = document.querySelector('#contype-b');
var contypec = document.querySelector('#contype-c');
var contyped = document.querySelector('#contype-d');
conyears.addEventListener("input", function(){
	//console.log("years: "+parseInt(conyears.value)+", months: "+parseInt(conmonths.value));
	totalmonths.value = parseInt(conyears.value)*12 + parseInt(conmonths.value);
}, false);
conmonths.addEventListener("input", function(){
	totalmonths.value = parseInt(conyears.value)*12 + parseInt(conmonths.value);
}, false);
// Disable questions 11-16 if contype-a is checked
function displayContextualOnly(pathid) {
	var dis = contypea.checked;
	conyears.disabled = dis;
	conmonths.disabled = dis;
	for (var i=12; i<=16; i++) {
		for(var j=1; j<=7; j++) {
			var radio = document.querySelector("#r"+i+j);
			radio.disabled = dis;
		}
	}
}
displayContextualOnly();
contypea.addEventListener("click", function(){displayContextualOnly();}, false);
contypeb.addEventListener("click", function(){displayContextualOnly();}, false);
contypec.addEventListener("click", function(){displayContextualOnly();}, false);
contyped.addEventListener("click", function(){displayContextualOnly();}, false);
</script>
			<button type="submit" is="paper-submit-button">Folytatás</button>
			</br></br>
		</form>
	</div>
  </core-header-panel>
</body>
</html>