<?php
require_once 'jama/Matrix.php';
/*
* @author Gergely Gyebroszki
*
* Function to fit a circle to a series of x-y data points
*  using least squares.
*
* @param $X array x values
* @param $Y array y values
* @returns array $fit of centerx, centery and radius
*/

function fitcircle($X, $Y) {
  $points = sizeof($X);
  if ($points < 3) {
	return array(
    "cx"  => 0,
    "cy" => 0,
    "r" => 0,
	"err" => 100
	);
  }
  // Mean of points
  $meanX = 0; $meanY = 0;
  for ($i = 0; $i < sizeof($X); $i++) {
	$meanX+=$X[$i]; $meanY+=$Y[$i];
  }
  $meanX /= $points;
  $meanY /= $points;
  // Auxiliary means
  $mXx = 0; $mXy = 0; $mYy = 0;
  $mRx = 0; $mRy = 0;
  for ($i = 0; $i < sizeof($X); $i++) {
	$x=$X[$i]; $y=$Y[$i]; $r=$x*$x+$y*$y;
	$mXx += ($x*($x - $meanX));
    $mXy += ($x*($y - $meanY));
    $mYy += ($y*($y - $meanY));
    $mRx += ($r*($x - $meanX));
    $mRy += ($r*($y - $meanY));
  }
  $mXx /= $points; $mXy /= $points; $mYy /= $points;
  $mRx /= $points; $mRy /= $points;
  // Assemble matrix 
  $A[0][0]=$mXx; $A[0][1]=2.0*$mXy;
  $A[1][0]=0.0; $A[1][1]=$mYy;
  $matrixA = new Matrix($A);
  // A=A+transpose(A) 
  $matrixA = $matrixA->plus($matrixA->transpose());
  // Assemble B vector 
  $b[0][0] = $mRx;
  $b[1][0] = $mRy;
  $matrixB = new Matrix($b);
  // Calculate center Solve A*x=b for x,then center=x 
  $LU = new LUDecomposition($matrixA);
  if($LU->isNonsingular()) {
	  $C = $matrixA->solve($matrixB);
	  $cx = $C->get(0,0);
	  $cy = $C->get(1,0);
	  // Calculate radius
	  $meanR2 = 0;
	  $maxR2 = 0;
	  $minR2 = 10000;
	  for ($i = 0; $i < sizeof($X); $i++) {
		$x = $X[$i]; $y = $Y[$i];
		$r2 = (($x - $cx)*($x - $cx)) + (($y - $cy)*($y - $cy));
		if ($r2 > $maxR2) { $maxR2 = $r2; }
		if ($r2 < $minR2) { $minR2 = $r2; }
		$meanR2 += $r2;
	  }
	  $meanR2 /= $points;
	  $radius = sqrt($meanR2);
	  // Calculate error
	  $err = sqrt($maxR2)/sqrt($minR2);
	  // Return
	  return array(
		"cx"  => $cx,
		"cy" => $cy,
		"r" => $radius,
		"err" => $err);
  } else {
	return array(
    "cx"  => 0,
    "cy" => 0,
    "r" => 0,
	"err" => 100);
  }
}
/*
Example of usage
	$X = array(4.63282, 4.13199, 3.03219, 1.95807, 0.813337, -0.452033, -1.57151, 
	-2.2397, -2.77403, -2.94986, -2.67172, -1.96034, -1.03413, 0.0245649, 
	1.34893, 2.5072, 3.62178, 4.3547, 4.90587, 4.98903, 4.65491);
	$Y = array(0.775732, 1.25284, 1.4886, 1.3824, 0.914325, 0.184571, -0.701402, 
	-1.83895, -2.81099, -3.82871, -4.57587, -4.91531, -5.15211, -5.12696, 
	-4.66508, -3.90325, -2.94352, -1.93244, -0.863952, 0.116316, 0.75224);
	$result = fitcircle($X, $Y);
	echo $result["r"]; // Should be 3.705
*/

function getDataPoints($d,$i) {
	$X = array($d["paths"][$i]["points"][0]["x"]);
	$Y = array($d["paths"][$i]["points"][0]["y"]);
	for($j=1;$j<count($d["paths"][$i]["points"]);$j++){
		$X[$j]=$d["paths"][$i]["points"][$j]["x"];
		$Y[$j]=$d["paths"][$i]["points"][$j]["y"];
	}
	return array("x" => $X, "y" => $Y);
}

/* Fits two circles on paths in json data
 *
 */
function multiFitCircle($d,$self) {
	$result = array();
	// Get self-circle path points
	$ppts = getDataPoints($d,$self);
	$fit = fitCircle($ppts["x"],$ppts["y"]);
	array_push($result,$fit);
	// Get other circle paths and fits
	for($i=0;$i<count($d["paths"]);$i++) {
		if ($i != $self) {
			$ppts = getDataPoints($d,$i);
			$fit = fitCircle($ppts["x"],$ppts["y"]);
			array_push($result,$fit);
		}
	}
	return $result;
}

?> 
