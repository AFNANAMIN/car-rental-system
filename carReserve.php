<?php
session_start();
include 'sid_get_currentRental.php';
//calling database functions
?>
<link href="css/bootstrap.css" rel="stylesheet">
<?php
//get location information session variable

$location=$_SESSION['location'];

// get final start from session

$finalStart=$_SESSION['pickuptime'];

//get return date from session
$finalEnd=$_SESSION['returnTime'];
	$radioVal2=$_POST['radio2'];
	$radioVal3=$_POST['radio3'];
	$radioVal4=$_POST['radio4'];
	$radioVal5=$_POST['radio5'];
	$radioVal6=$_POST['radio6'];
	$radioVal7=$_POST['radio7'];
	$radioVal8=$_POST['radio8'];
	$radioVal9=$_POST['radio9'];
	$radioVal10=$_POST['radio10'];
	$radioVal11=$_POST['radio11'];
	$radioVal12=$_POST['radio12'];
	$radioVal13=$_POST['radio13'];
	$radioVal14=$_POST['radio14'];
	$radioVal15=$_POST['radio15'];
	$radioVal16=$_POST['radio16'];
	$radioVal17=$_POST['radio17'];
	$radioVal18=$_POST['radio18'];
	$radioVal19=$_POST['radio19'];
	$radioVal20=$_POST['radio20'];



	if(isset($radioVal2)){
		$a1=explode("_",$radioVal2);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal3)){
		$a1=explode("_",$radioVal3);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	
	elseif(isset($radioVal4)){
		$a1=explode("_",$radioVal4);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal5)){
		$a1=explode("_",$radioVal5);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal6)){
		$a1=explode("_",$radioVal6);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal7)){
		$a1=explode("_",$radioVal7);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 

	elseif(isset($radioVal8)){
		$a1=explode("_",$radioVal8);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal9)){
		$a1=explode("_",$radioVal9);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal10)){
		$a1=explode("_",$radioVal10);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
	elseif(isset($radioVal11)){
		$a1=explode("_",$radioVal11);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal12)){
		$a1=explode("_",$radioVal12);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal13)){
		$a1=explode("_",$radioVal13);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal14)){
		$a1=explode("_",$radioVal14);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal15)){
		$a1=explode("_",$radioVal15);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 

		elseif(isset($radioVal16)){
		$a1=explode("_",$radioVal16);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal17)){
		$a1=explode("_",$radioVal17);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal18)){
		$a1=explode("_",$radioVal18);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal19)){
		$a1=explode("_",$radioVal19);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 
		elseif(isset($radioVal20)){
		$a1=explode("_",$radioVal20);
$vehicleID=$a1[0];
$esticost=$a1[1];
	} 




//echo $radioVal;
//$a1=explode("_",$radioVal);
//echo $vehicleID;
//echo $esticost;

$username=$_SESSION['userName'];

//echo $vehicleID;
$status="out";
$lateFees="0";
	
		if(isset($username))
			{
			$a=insertIntoReservation($username,$finalStart,$finalEnd,$status,$esticost,$lateFees,$location,$vehicleID);
			}
		else
		{
			echo "<p> Please Login </p>";
		}
//	var_dump($a);
	//echo $a;
	if($a)
	{
	//echo "true";
	
	
	echo "</br>";
	echo "</br>";
	echo "<p align=\"center\"> your reservation is made.</br>Your Pickup time is ".$finalStart." and return time is ".$finalEnd."</p>";
	
	}
	else
	{
		
	echo "<p align=\"center\">Sorry your reservation could not be made</p>";
	}
?>
<a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'>Go to home page</a>
