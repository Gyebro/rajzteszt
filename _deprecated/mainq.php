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
    <div class="core-header">Rajzteszt - Ut�teszt</div>
    <div class="content">
<?php 
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) {
	die("<p>Adatb�zis hiba.</p>");
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
			"Az �letem a legt�bb tekintetben k�zel van az ide�lishoz.",
			"�ltal�ban �gy tartom magamr�l, hogy nagyon boldog ember vagyok.",
			"Az �letk�r�lm�nyeim kit�n�ek.",
			"�lvezem az �letet, tekintet n�lk�l arra, hogy mi t�rt�nik �s mindenb�l a legjobbat hozom ki." ,
			"El�gedett vagyok az �letemmel.",
			"Eddig minden fontosat megkaptam az �letben, amit csak akartam.",
			"Ha �jra le�lhetn�m az �letem, szinte semmin sem v�ltoztatn�k.",
			"�ltal�ban nem vagyok t�ls�gosan boldog. B�r nem vagyok depresszi�s, sohasem t�n�k olyan boldognak, mint amilyen lehetn�k.",
			"�gy gondolom, hogy a legt�bb t�rsamhoz k�pest �n boldogabb vagyok."
		);
		echo '<form action="finish.php" method="POST">';
		echo '<input type="hidden" name="user" value="'.$_POST["user"].'"></input>';
		echo '<p>K�rlek, jel�ld be, hogy milyen m�rt�kben �rtesz egyet a felsorolt �ll�t�sokkal!</p>';
		for ($i=1; $i<10; $i++) {
			echo "<p>".$i.". ".$questions[$i-1]."</p>";
			echo '<label for="r'.$i.'1" style="float:left; padding-right:8px">(egy�ltal�n nem �rtek egyet)</label>';
			echo '<input type="radio" name="q'.$i.'" value="1" id="r'.$i.'1"/></input><label for="r'.$i.'1">1</label>';
			echo '<input type="radio" name="q'.$i.'" value="2" id="r'.$i.'2"/></input><label for="r'.$i.'2">2</label>';
			echo '<input type="radio" name="q'.$i.'" value="3" id="r'.$i.'3"/></input><label for="r'.$i.'3">3</label>';
			echo '<input type="radio" name="q'.$i.'" value="4" id="r'.$i.'4" checked/></input><label for="r'.$i.'4">4</label>';
			echo '<input type="radio" name="q'.$i.'" value="5" id="r'.$i.'5"/></input><label for="r'.$i.'5">5</label>';
			echo '<input type="radio" name="q'.$i.'" value="6" id="r'.$i.'6"/></input><label for="r'.$i.'6">6</label>';
			echo '<input type="radio" name="q'.$i.'" value="7" id="r'.$i.'7"/></input><label for="r'.$i.'7">7 (teljesen egyet�rtek)</label></br>';
		}
	} else {
		die("<p>A hozz�f�r�shez bejelentkez�s sz�ks�ges!</p>");
	}
}
?>
		<!-- End of form -->
			</br>
			<button type="submit" is="paper-submit-button">Folytat�s</button>
			</br></br>
		</form>
	</div>
  </core-header-panel>
</body>
</html>