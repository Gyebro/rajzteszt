<!DOCTYPE html>
<html>
<head>
	<title>Check tests</title>
</head>
<body>
<p>
<?php 
if (isset($_GET['key'])) if ($_GET['key']=="cupi") {
include 'database.inc.php';
$con = mysqli_connect($host, $dbuser, $dbpass, $db);
if (!$con) { die("Sikertelen csatlakozás: " . mysqli_error($con)); }
else {
	$sql = "SET NAMES 'utf8'";
	mysqli_query($con,$sql) or die(mysqli_error($con));
	$sql = "SELECT * FROM users";
	$result = mysqli_query($con,$sql) or die(mysqli_error($con));
	$validcount = 0;
	$usercount = 0;
	while($row = mysqli_fetch_array($result)) {
		$usercount++;
		$sqlrajz = "SELECT * FROM drawings WHERE USERID = ".$row[0];
		$rajzok = mysqli_num_rows(mysqli_query($con,$sqlrajz));
		$rajzvalid = False;
		if ($rajzok >= 24) { $rajzvalid = True; }
		$sqltest = "SELECT * FROM tests WHERE USERID = ".$row[0];
		$test = mysqli_fetch_array(mysqli_query($con,$sqltest));
		$testvalid = True;
		for($k = 1; $k <= 9; $k++) { 
			$lab = "Q".$k; if ($test[$lab] == 0) { $testvalid = False; }
		}
		for($k = 17; $k <= 57; $k++) { 
			$lab = "Q".$k; if ($test[$lab] == 0) { $testvalid = False; }
		}
		$swls = $test["Q1"]+$test["Q3"]+$test["Q5"]+$test["Q6"]+$test["Q7"];
		$shs = $test["Q2"]+$test["Q4"]+(8-$test["Q8"])+$test["Q9"];
		
		$swlspc = (int)(100.0*((double)($swls-7)/30.0));
		$shspc = (int)(100.0*((double)($shs-4)/24.0));
		$diffpercent = (int)abs($swlspc-$shspc);
		$happy = (int)((double)($swlspc + $shspc)/2.0);
		$sqlu = "UPDATE users SET ";
		if ($rajzvalid && $testvalid) {
			$validcount++;
		} else {
			$invalid = 1; // TODO: Indicate the cause if required
			$sqlu.= "INVALID=".$invalid.",";
		}
		$sqlu.= "SWLS=".$swlspc.",".
		"SHS=".$shspc.",".
		"HAPPY=".$happy.",".
		"DIFFPERCENT=".$diffpercent.
		" WHERE ID=".$row[0];
		echo $sqlu."</br>";
		mysqli_query($con,$sqlu) or die(mysqli_error($con));
	}
	echo "All tests: ".$usercount."</br>";
	echo "Valid tests: ".$validcount." (".(100.0*$validcount/$usercount)."%)</br>";
}
}
?>
</p>
</body>