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
	<link rel="stylesheet" href="css/style.css" />
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Rajzteszt - Utóteszt
		<!--<paper-fab class="sourceButton" icon="arrow-forward" title="arrow-forward"></paper-fab>-->
	</div>
    <div class="content">
		<form action="mainq.php" method="POST">
<?php 
if(isset($_GET["user"])) {
	echo '<input type="hidden" name="user" value="'.$_GET["user"].'"></input>';
} else {
	die("<p>A hozzáféréshez bejelentkezés szükséges!</p>");
}
?>
			<p>1. Jelenlegi párkapcsolatod típusa, amire kitöltötted a tesztet: </p>
			<input type="radio" name="contype" value="none"   id="contype-a" checked/></input><label for="contype-a">Jelenleg nincs</label></br>
			<input type="radio" name="contype" value="dating" id="contype-b"/></input><label for="contype-b">Együtt járás</label></br>
			<input type="radio" name="contype" value="living" id="contype-c"/></input><label for="contype-c">Élettársi kapcsolat</label></br>
			<input type="radio" name="contype" value="marriage" id="contype-d"/></input><label for="contype-d">Házasság</label></br>
			<p>2. Mennyi ideje tart:</p>
			<paper-input-decorator label="Években (kerekíts felfelé)" floatingLabel>
				<input type="number" name="conyears" value=0></input>
			</paper-input-decorator>
			<p>3. Mennyire vagy elégedett a partnereddel: </p>
			<label for="radio-s1" style="float:left; padding-right:8px">(egyáltalán nem)</label>
			<input type="radio" name="consatisf" value="1" id="radio-s1"/></input><label for="radio-s1">1</label>
			<input type="radio" name="consatisf" value="2" id="radio-s2"/></input><label for="radio-s2">2</label>
			<input type="radio" name="consatisf" value="3" id="radio-s3"/></input><label for="radio-s3">3</label>
			<input type="radio" name="consatisf" value="4" id="radio-s4" checked/></input><label for="radio-s4">4</label>
			<input type="radio" name="consatisf" value="5" id="radio-s5"/></input><label for="radio-s5">5</label>
			<input type="radio" name="consatisf" value="6" id="radio-s6"/></input><label for="radio-s6">6</label>
			<input type="radio" name="consatisf" value="7" id="radio-s7"/></input><label for="radio-s7">7 (teljesen)</label></br>
			</br>
			<button type="submit" is="paper-submit-button">Folytatás</button>
			</br></br>
		</form>
	</div>
  </core-header-panel>
</body>
</html>