﻿<!DOCTYPE html>
<html>
<head>
	<title>Generate-image</title>
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
	$sql = "SELECT * FROM questions";
	$result = mysqli_query($con,$sql) or die(mysqli_error($con));
	while($row = mysqli_fetch_array($result)) {
		$signs["Q".$row['ID']]=intval($row['SIGN']);
	}
	//var_dump($signs);
	$sql = "SELECT * FROM drawings";
	$result = mysqli_query($con,$sql) or die(mysqli_error($con));
	$generated = 0;
	$discarded = 0;
	$accepted = 0;
	$skip = 0;
	$skipped = 0;
	if(isset($_GET["skip"])) { $skip = $_GET["skip"]; }
	while($row = mysqli_fetch_array($result)) {
		if($skipped < $skip) {
			// Dont generate result for this row
			$skipped++;
		} else {
			// Generate image and results for this drawing
			$drawingID = $row['ID'];
			$d = json_decode($row['DATA'], true);
			$stage = $row['STAGE'];
			$userID = $row['USERID'];
			$self = $row['SEL1'];
			$annotated = $row['SEL2'];
			$label = "Erős én";
			// Get happiness of user
			$sqlh = "SELECT * FROM users WHERE ID = ".$userID;
			$rowh = mysqli_fetch_array(mysqli_query($con,$sqlh));
			$happy = $rowh["HAPPY"]; 
			$swls = $rowh["SWLS"];
			$shs = $rowh["SHS"];
			$invalid = $rowh["INVALID"];
			if ($stage > 5) {
				// This is a circle-test
				// Fit circles, first fit will be the self-circle
				$fit = multiFitCircle($d,$self,$annotated);
				//echo $drawingID."</br>";
				// Only save if fitting returned 2 circles!
				if (sizeof($fit)==2) {
					$accepted++;
					// Insert into database
					$dx = ($fit[0]["cx"]-$fit[1]["cx"]);
					$dy = ($fit[0]["cy"]-$fit[1]["cy"]);
					$r0 = $fit[0]["r"];
					$r1 = $fit[1]["r"];
					$rsum = $r0+$r1;
					$cdist = sqrt($dx*$dx+$dy*$dy);
					$pdist = $cdist-$rsum;
					$cdistrel = $cdist/$rsum;
					if ($r1 == 0) { $r1 = 0.1; }
					$rrat = $r0/$r1;
					$ints = 0;
					$maxr = max($r0,$r1);
					$minr = min($r0,$r1);
					// Calculate overlaps
					$overa = calculateOverlap($fit[0]["cx"],$fit[0]["cy"],$fit[0]["r"],
												$fit[1]["cx"],$fit[1]["cy"],$fit[1]["r"]);
					$selfcarea = $r0*$r0*pi();
					$overr = $overa / $selfcarea;
					$over2 = $overr/$rrat;
					// Distance tolerance
					$tol = 5;
					if ($pdist >= $tol) { $ints = 0; }
					elseif (($pdist < $tol) && ($pdist >= -$tol)) { $ints = 1; }
					elseif (($pdist < -$tol) && ($cdist >= $maxr-$minr+$tol)) { $ints = 2; }
					elseif (($pdist < -$tol) && ($cdist >= $maxr-$minr-$tol)) { $ints = 3; }
					elseif (($pdist < -$tol) && ($cdist < $maxr-$minr-$tol)) { $ints = 4; }
					$sql2 = "REPLACE INTO results (DRAWINGID, USERID, STAGE, SWLS, SHS, HAPPY, INVALID, ".
					"X0, Y0, R0, ERR0, X1, Y1, R1, ERR1, CDIST, PDIST, RRAT, INTS, CDISTREL, OVERA, OVERR, OVER2) ";
					$sql2.= "VALUES ('".$drawingID."','".$userID."','".$stage."','".$swls."','".$shs."','".$happy."','".$invalid."','".
					$fit[0]["cx"]."','".$fit[0]["cy"]."','".$r0."','".$fit[0]["err"]."','".
					$fit[1]["cx"]."','".$fit[1]["cy"]."','".$r1."','".$fit[1]["err"]."','".
					$cdist."','".$pdist."','".$rrat."','".$ints."','".$cdistrel."','".$overa."','".$overr."','".$over2."')";
					mysqli_query($con,$sql2);
				} else {
					// Also save to result record
					$discarded++;
					$sql2 = "REPLACE INTO results (DRAWINGID, USERID, STAGE, SWLS, SHS, HAPPY, INVALID) ";
					$sql2.= "VALUES ('".$drawingID."','".$userID."','".$stage."','".$swls."','".$shs."','".$happy."','".$invalid."')";
					mysqli_query($con,$sql2);
				}
				generateSVGc($drawingID,$d,$fit,$annotated,$label);
			} else {
				// This is a sketch-test
				$sql2 = "REPLACE INTO results (DRAWINGID, USERID, STAGE, SWLS, SHS, HAPPY, INVALID) ";
				$sql2.= "VALUES ('".$drawingID."','".$userID."','".$stage."','".$swls."','".$shs."','".$happy."','".$invalid."')";
				mysqli_query($con,$sql2);
				// Generate drawing
				generateSVG($drawingID,$d);
			} // end if stage check
			$generated++;
			if ($generated%500==0) {
				echo $generated."</br>";
				flush();
				ob_flush();
			}
		} // end if skipping
	}
	echo "Generated ".$generated." drawings</br>";
	echo "Accepted ".$accepted." circle-tests (".(100*$accepted/($accepted+$discarded))."%)</br>";
}
}
?>
</p>
</body>