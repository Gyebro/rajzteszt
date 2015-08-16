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
	<link rel="stylesheet" href="css/style.css" />
</head>
<body unresolved>
  <core-header-panel>
    <div class="core-header">Rajzteszt</div>
    <div class="content">
<?php 
if(isset($_GET["user"]) && isset($_GET["stage"])) {
	//echo "<p>Az Ön azonosítója: <b>".$_GET["user"]."</b></p>";
	echo "<script>";
	echo "var userid=".$_GET["user"].";";
	echo "var stage=".$_GET["stage"].";";
	echo "</script>";
	// Debug controls
	//echo "</br>";
	//echo "<a href='test.php?user=".$_GET["user"]."&stage=".($_GET["stage"]-1)."'><paper-button raised>ELŐZŐ</paper-button></a>";
	//echo "<a href='test.php?user=".$_GET["user"]."&stage=".($_GET["stage"]+1)."'><paper-button raised>KÖVETKEZŐ</paper-button></a>";
} else {
	die("<p>A hozzáféréshez bejelentkezés szükséges!</p>");
}
?>
		<p id="instructions">Instrukciók: </p>
		<div id="canvasholder">
		<canvas id="drawing"></canvas>
		</div>
		<div><paper-button raised id="savebutton">Folytatás</paper-button><p id="errorbox" class="errorstart"></p></div>
		<div><paper-button id="addcomment">Megjegyzés hozzáadása</paper-button></div>
		<div id="commentfield" style="display:none;"><input type="text" id="comment" maxlength="400" style="width:100%;"></div>
		<script src="js/main.js"></script>
	</div>
  </core-header-panel>
</body>
</html>