<!DOCTYPE html>
<html>
<head>
	<title>Generate combined images</title>
</head>
<body>
<p>
<?php 
if (isset($_GET['key'])) if ($_GET['key']=="cupi") {
include 'database.inc.php';
include 'inc/fit.inc.php';
include 'inc/svg.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) { die("Sikertelen csatlakozás: " . mysqli_error($con)); }
else {
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	$sql = "SELECT * FROM users";
	$result = mysqli_query($con,$sql) or die(mysqli_error($con));
	$generated = 0;
	$discarded = 0;
	$accepted = 0;
	$skip = 0;
	$skipped = 0;
	if(isset($_GET["skip"])) { $skip = $_GET["skip"]; }
	//Labels and colors for combos
	$labels = ["düh","magány","szomorúság","boldogság érzése","hangulatom az utóbbi időben","majd pár","és pár","legjobb barát","ideális partner","akivel konfliktusban állsz","legfontosabb problémád","probléma egy év múlva","párodat öt év múlva","gyenge/erös én","boldogság","pénz", "szexualitás","munka", "egészség","szomorúság","szabadság","hála",  "spiritualitás","hangulat az utóbbi idöben"];
	$colors = ["crimson","blueviolet","darkgray","hotpink","teal","goldenrod","gold","limegreen","deeppink","crimson","darkslategray","darkseagreen","darkorange","green","hotpink", "saddlebrown", "red", "royalblue", "mediumspringgreen","darkgray","peachpuff","pink",  "skyblue","teal"];
	echo "Starting...</br>";
	flush();ob_flush();
	while($row = mysqli_fetch_array($result)) {
		if($skipped < $skip) {
			// Dont generate result for this row
			$skipped++;
		} else {
			// User data
			$userID = $row['ID'];
			// Get some circles from this user
			$sql2 = "SELECT * FROM drawings WHERE USERID = ".$userID;
			$extfitsa = array();$extfitsb = array();$extfitsc = array();$extfitsd = array();
			$result2 = mysqli_query($con,$sql2) or die(mysqli_error($con));
			$pagecx = 800.0/2.0; $pagecy = 565.0/2.0; $selfr = 100.0;
			$selffit = array(
							"cx"	=> $pagecx,
							"cy"	=> $pagecy,
							"r"		=> $selfr,
							"label" => "Én",
							"color" => "black"
						);
			array_push($extfitsa,$selffit);array_push($extfitsb,$selffit);
			array_push($extfitsc,$selffit);array_push($extfitsd,$selffit);
			while($row2 = mysqli_fetch_array($result2)) {
				$d = json_decode($row2['DATA'], true);
				$self = $row2['SEL1'];
				$annotated = $row2['SEL2'];
				$stage = $row2['STAGE'];
				if ($stage > 5) {
					$fit = multiFitCircle($d,$self,$annotated);
					if (sizeof($fit)==2) {
						$r = $fit[0]["r"];
						$cx = $fit[0]["cx"]; $cy = $fit[0]["cy"];
						$extfit = array(
							"cx"	=> ($fit[1]["cx"]-$cx)/$r*$selfr+$pagecx,
							"cy"	=> ($fit[1]["cy"]-$cy)/$r*$selfr+$pagecy,
							"r"		=> $fit[1]["r"]/$r*$selfr,
							"label" => $labels[$stage-1],
							"color" => $colors[$stage-1]
						);
						switch ($stage) {
							case 6: /* majd pár */
							case 7: /* és pár */
							case 9: /* id pár */
							case 13: /* pár 5 év */
								array_push($extfitsa,$extfit);
								break;
							case 10: /* konfliktus */
							case 11: /* probléma */
							case 12: /* probléma egy év múlva */
								array_push($extfitsb,$extfit);
								break;
							case 15: /* boldogság */
							case 20: /* szomorúság */
							case 24: /* hangulat */
								array_push($extfitsd,$extfit);
								break;
						}
						switch ($stage) {
							case 7: /* és pár */
							case 8: /* barát */
							case 15: /* boldogság */
							case 17: /* szex */
							case 18: /* munka */
							case 19: /* egészség */
							case 20: /* szomorúság */
							case 21: /* szabadság */
							case 22: /* hála */
							case 23: /* spirit */
							case 24: /* hangulat */
								array_push($extfitsc,$extfit);
								break;
						}
					}
				}
			}
			// Generate combo image
			//var_dump($extfits);
			generateSVGcombo("combo".$userID."a",$d,$extfitsa);
			generateSVGcombo("combo".$userID."b",$d,$extfitsb);
			generateSVGcombo("combo".$userID."c",$d,$extfitsc);
			generateSVGcombo("combo".$userID."d",$d,$extfitsd);
			echo "Generated combos for user ".$userID."</br>";
			flush();ob_flush();
		}
	} // end if skipping
	echo "Finished</br>";
}
}
?>
</p>
</body>