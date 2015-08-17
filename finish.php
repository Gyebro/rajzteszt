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
    <div class="core-header">Rajzteszt - Sikeres kitöltés</div>
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
	if(isset($_POST["user"]) && isset($_POST["q1"]) && isset($_POST["q2"]) && isset($_POST["q3"]) &&
		isset($_POST["q4"]) && isset($_POST["q5"]) && isset($_POST["q6"]) && isset($_POST["q7"]) && 
		isset($_POST["q8"]) && isset($_POST["q9"]) && isset($_POST["q10"])) {
		$P = $_POST;
		if ($_POST["q10"] == 1) {
			//echo "<p>Nincs kapcsolat</p>";
			$P["q11"] = 0;
			$P["q12"] = 0;
			$P["q13"] = 0;
			$P["q14"] = 0;
			$P["q15"] = 0;
			$P["q16"] = 0;
		}
		// Save in format: USERID, Q1, Q2, Q3, Q4, Q5, Q6, Q7, Q8, Q9 ... Q57
		$sql = "INSERT INTO tests (USERID, ".
			" Q1,  Q2,  Q3,  Q4,  Q5,  Q6,  Q7,  Q8,  Q9, Q10,".
			"Q11, Q12, Q13, Q14, Q15, Q16, Q17, Q18, Q19, Q20,".
			"Q21, Q22, Q23, Q24, Q25, Q26, Q27, Q28, Q29, Q30,".
			"Q31, Q32, Q33, Q34, Q35, Q36, Q37, Q38, Q39, Q40,".
			"Q41, Q42, Q43, Q44, Q45, Q46, Q47, Q48, Q49, Q50,".
			"Q51, Q52, Q53, Q54, Q55, Q56, Q57)".
			"VALUES ('".$P["user"]."','".$P["q1"]."','".$P["q2"]."','".$P["q3"]."','".$P["q4"]."','".$P["q5"]."','".$P["q6"]."','".$P["q7"]."','".$P["q8"]."','".$P["q9"]."','".
			$P["q10"]."','".$P["q11"]."','".$P["q12"]."','".$P["q13"]."','".$P["q14"]."','".$P["q15"]."','".$P["q16"]."','".$P["q17"]."','".$P["q18"]."','".$P["q19"]."','".
			$P["q20"]."','".$P["q21"]."','".$P["q22"]."','".$P["q23"]."','".$P["q24"]."','".$P["q25"]."','".$P["q26"]."','".$P["q27"]."','".$P["q28"]."','".$P["q29"]."','".
			$P["q30"]."','".$P["q31"]."','".$P["q32"]."','".$P["q33"]."','".$P["q34"]."','".$P["q35"]."','".$P["q36"]."','".$P["q37"]."','".$P["q38"]."','".$P["q39"]."','".
			$P["q40"]."','".$P["q41"]."','".$P["q42"]."','".$P["q43"]."','".$P["q44"]."','".$P["q45"]."','".$P["q46"]."','".$P["q47"]."','".$P["q48"]."','".$P["q49"]."','".
			$P["q50"]."','".$P["q51"]."','".$P["q52"]."','".$P["q53"]."','".$P["q54"]."','".$P["q55"]."','".$P["q56"]."','".$P["q57"]."')";
		mysqli_query($con,$sql) or die(mysqli_error($con));
	} else {
		die("<p>A hozzáféréshez bejelentkezés szükséges!</p>");
	}
}
?>
		<p>Köszönöm szépen a közremûködésedet!</br>A rajztesztek kiértékelésével kapcsolatban további információt Dr. Vass Zoltán honlapján, az alábbi oldalakon találsz:</br>
		<a href="http://www.rajzelemzes.hu/rajzelemzes/A_Hatvan_Masodperces_Rajzteszt.html" target="_blank">A Hatvan Másodperces Rajzteszt</a></br>
		<a href="http://www.rajzelemzes.hu/rajzelemzes/Spontan_firkak_es_firkatesztek_vizsgalati_modszerei.html" target="_blank">Spontán firkák és firkatesztek vizsgálati módszerei</a>
		</p>
	</div>
  </core-header-panel>
</body>
</html>