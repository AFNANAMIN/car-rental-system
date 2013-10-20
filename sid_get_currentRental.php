<?php
function getCurrentRentalInformation($inputUsername) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT R.resID as f1,R.PickupDateTime AS f2, R.ReturnDateTime AS f3, C.CarModel AS f4, C.CarLocation AS f5, R.EstimatedCost AS f6
FROM reservation AS R, car AS C
WHERE R.VehicleSno = C.VehicleSno
AND R.Username =?
AND R.ReturnDateTime >= 
CURRENT_TIMESTAMP ORDER BY R.PickupDateTime DESC';
   if (isset($inputUsername)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($resID,$pickupDateTime, $returnDateTime, $modelName, $locationName, $estimatedCost);
         while ($preparedStatement->fetch()) {
            $result[] = array($resID,$pickupDateTime, $returnDateTime, $modelName, $locationName, $estimatedCost);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function getPreviousRentalInformation($inputUsername) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT R.PickupDateTime AS f1, R.ReturnDateTime AS f2, C.CarModel AS f3, C.CarLocation AS f4, R.EstimatedCost AS f5, R.ReturnStatus AS f6
FROM reservation AS R, car AS C
WHERE R.VehicleSno = C.VehicleSno
AND R.Username =  ?
AND R.ReturnDateTime < 
CURRENT_TIMESTAMP ORDER BY R.PickupDateTime DESC';
   if (isset($inputUsername)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($pickupDateTime, $returnDateTime, $modelName, $locationName, $estimatedCost, $returnStatus);
         while ($preparedStatement->fetch()) {
            $result[] = array($pickupDateTime, $returnDateTime, $modelName, $locationName, $estimatedCost, $returnStatus);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function getDrivingPlans() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT * FROM  drivingplan';
  if ($preparedStatement = $mysqli->prepare($query)) {
      $preparedStatement->execute();
      $preparedStatement->bind_result($carModel, $requestDateTime, $userName, $problem);
      while ($preparedStatement->fetch()) {
         $result[] = array($carModel, $requestDateTime, $userName, $problem);
      }
   }
   $preparedStatement->close();
   $mysqli->close();

   return $result;
}

function carSearch($carLocation,$carType, $pickUpDateTime, $returnDateTime) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT * FROM car
	WHERE CarLocation= ? AND Type= ? AND VehicleSno not in
	(
  	 Select VehicleSno From reservation where 
  	 (
      (
      PickUpDateTime >= ? AND ReturnDateTime <= ?    
      )
     )
	)';

   if ($preparedStatement = $mysqli->prepare($query)) {
      $errorValue = $preparedStatement->bind_param("ssss", $carLocation,$carType, $pickUpDateTime, $returnDateTime);
      $errorValue = $preparedStatement->execute();
	 $preparedStatement->bind_result($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	while($preparedStatement->fetch())
	{
	$result[] = array($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	};
      $preparedStatement->close();
   }
   $mysqli->close();
   return $result;
}



function carSearchByModel($carLocation,$carModel, $pickUpDateTime, $returnDateTime) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT * FROM car
	WHERE CarLocation= ? AND CarModel= ? AND VehicleSno not in
	(
  	 Select VehicleSno From reservation where 
  	 (
      (
      PickUpDateTime >= ? AND ReturnDateTime <= ?    
      )
     )
	)';

   if ($preparedStatement = $mysqli->prepare($query)) {
      $errorValue = $preparedStatement->bind_param("ssss", $carLocation,$carModel, $pickUpDateTime, $returnDateTime);
      $errorValue = $preparedStatement->execute();
	 $preparedStatement->bind_result($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	while($preparedStatement->fetch())
	{
	$result[] = array($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	};
      $preparedStatement->close();
   }
   $mysqli->close();
   return $result;
}

function carSearchBasic($carLocation, $pickUpDateTime, $returnDateTime) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT * FROM car
	WHERE CarLocation= ?  AND VehicleSno not in
	(
  	 Select VehicleSno From reservation where 
  	 (
      (
      PickUpDateTime >= ? AND ReturnDateTime <= ?    
      )
     )
	)';

   if ($preparedStatement = $mysqli->prepare($query)) {
      $errorValue = $preparedStatement->bind_param("sss", $carLocation,$pickUpDateTime, $returnDateTime);
      $errorValue = $preparedStatement->execute();
	 $preparedStatement->bind_result($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	while($preparedStatement->fetch())
	{
	$result[] = array($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	};
      $preparedStatement->close();
   }
   $mysqli->close();
   return $result;
}


function getAvailableLocationsForType($inputCarLocation, $inputType, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT * from car where CarLocation <> ? 
and Type = ? 
and VehicleSno not in (
		select VehicleSno 
		from reservation 
			where ReturnStatus <> "Cancelled" 
			and (PickUpDateTIme >= ? or ReturnDateTime <= ?)) order by CarLocation';
	echo $query;
   if (isset($inputType) && isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ssss",$inputCarLocation, $inputType, $inputPickUpDateTime, $inputReturnDateTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopCarLocation);
         while ($preparedStatement->fetch()) {
            $result[] = $loopCarLocation;
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function carSearch1($carLocation,$carType, $pickUpDateTime, $returnDateTime) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT * FROM car
	WHERE CarLocation <> ? AND Type= ? AND VehicleSno not in
	(
  	 Select VehicleSno From reservation where 
  	 (
      (
      PickUpDateTime >= ? AND ReturnDateTime <= ?    
      )
     )
	)';

   if ($preparedStatement = $mysqli->prepare($query)) {
      $errorValue = $preparedStatement->bind_param("ssss", $carLocation,$carType, $pickUpDateTime, $returnDateTime);
      $errorValue = $preparedStatement->execute();
	 $preparedStatement->bind_result($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	while($preparedStatement->fetch())
	{
	$result[] = array($vehicleSno,$AuxCable, $transType,$seatingCap,$bluetoothConnectivity, $dailyRate, $hourlyRate,$color, $type,$carModel,$undermaintenanceFlag,$carLoc);
	};
      $preparedStatement->close();
   }
   $mysqli->close();
   return $result;
}

function insertIntoReservationExtendedTime($resID, $extendedTime) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     echo "<h1>".$resID."</h1><br/>";
     echo "<h1>".$extendedTime."</h1><br/>";
     $query = 'insert into reservation_extended_time(ResID, ExtendedTime) values (?, ?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ss", $resID, $extendedTime);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}


function updateReservationReturnDateTimeForResID($resID, $returnDateTime) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'update reservation set ReturnDateTime = ? where ResID = ?';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ss", $returnDateTime, $resID);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}
//updateReservationReturnDateTimeForResID("2", "2013-04-23 10:00:00 ");

function getAvailableCarsForLocationPickUpTimeAndReturnTime($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select VehicleSno, AuxiliaryCable, 
TransmissionType, SeatingCapacity, BluetoothConnectivity, 
DailyRate, HourlyRate, Color, Type, CarModel, CarLocation from car where CarLocation = ? 
and UnderMaintenanceFlag = 0 and VehicleSno not in (select VehicleSno from reservation 
where ReturnStatus <> "Cancelled" and 
((PickUpDateTime <= ? and ReturnDateTime >= ?) or (PickUpDateTime >= ? and ReturnDateTime <= ?) or (PickUpDateTime <= ? and ReturnDateTime >= ?)))';
   if (isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("sssssss", $inputCarLocation, $inputPickUpDateTime, $inputPickUpDateTime, $inputPickUpDateTime, $inputReturnDateTime, $inputReturnDateTime, $inputReturnDateTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}
//$a=getAvailableCarsForLocationPickUpTimeAndReturnTime("Klaus","2013-04-01 12:00:00","2013-04-24 12:00:00");
//var_dump($a);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   final function
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getAvailableCarsNotAtLocationPickUpTimeAndReturnTime($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select VehicleSno, AuxiliaryCable, TransmissionType, SeatingCapacity, BluetoothConnectivity, DailyRate, HourlyRate, Color, Type, CarModel, CarLocation from car where CarLocation <> ? and UnderMaintenanceFlag = 0 and VehicleSno not in (select VehicleSno from reservation where ReturnStatus <> "Cancelled" and ((PickUpDateTime <= ? and ReturnDateTime >= ?) or (PickUpDateTime >= ? and ReturnDateTime <= ?) or (PickUpDateTime <= ? and ReturnDateTime >= ?)))';
   if (isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("sssssss", $inputCarLocation, $inputPickUpDateTime, $inputPickUpDateTime, $inputPickUpDateTime, $inputReturnDateTime, $inputReturnDateTime, $inputReturnDateTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}
///////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

function getAvailableCarsNotAtLocationPickUpTimeAndReturnTime1($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime, $currentTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT C.VehicleSno, C.AuxiliaryCable, C.TransmissionType, C.SeatingCapacity, C.BluetoothConnectivity, C.DailyRate, C.HourlyRate, C.Color, C.Type, C.CarModel, C.CarLocation, R.PickUpDateTime FROM car AS C INNER JOIN reservation AS R ON R.VehicleSno = C.VehicleSno WHERE ( (CarLocation <> ? ) AND (C.UnderMaintenanceFlag = 0) AND ((( R.PickUpDateTime <  ? AND R.ReturnDateTime <  ? ) OR ( R.PickUpDateTime >  ? AND R.ReturnDateTime >  ? ))) AND  R.PickUpDateTime > ? )';

   if (isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ssssss", $inputCarLocation, $inputPickUpDateTime,  $inputReturnDateTime, $inputPickUpDateTime,$inputReturnDateTime,$currentTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function getAvailableCarsAtLocationPickUpTimeAndReturnTime1($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime, $currentTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT C.VehicleSno, C.AuxiliaryCable, C.TransmissionType, C.SeatingCapacity, C.BluetoothConnectivity, C.DailyRate, C.HourlyRate, C.Color, C.Type, C.CarModel, C.CarLocation, R.PickUpDateTime FROM car AS C INNER JOIN reservation AS R ON R.VehicleSno = C.VehicleSno WHERE ( (CarLocation = ? ) AND (C.UnderMaintenanceFlag = 0) AND ((( R.PickUpDateTime <  ? AND R.ReturnDateTime <  ? ) OR ( R.PickUpDateTime >  ? AND R.ReturnDateTime >  ? ))) AND  R.PickUpDateTime > ? )';

   if (isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ssssss", $inputCarLocation, $inputPickUpDateTime,  $inputReturnDateTime, $inputPickUpDateTime,$inputReturnDateTime, $currentTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function getAvailableCarsByTypePickUpTimeAndReturnTime1($inputCarType, $inputPickUpDateTime, $inputReturnDateTime,  $currentTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT C.VehicleSno, C.AuxiliaryCable, C.TransmissionType, C.SeatingCapacity, C.BluetoothConnectivity, C.DailyRate, C.HourlyRate, C.Color, C.Type, C.CarModel, C.CarLocation, R.PickUpDateTime FROM car AS C INNER JOIN reservation AS R ON R.VehicleSno = C.VehicleSno WHERE ( (C.Type = ? ) AND (C.UnderMaintenanceFlag = 0) AND ((( R.PickUpDateTime <  ? AND R.ReturnDateTime <  ? ) OR ( R.PickUpDateTime >  ? AND R.ReturnDateTime >  ? ))) AND  R.PickUpDateTime > ? )';

   if (isset($inputCarType) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ssssss", $inputCarType, $inputPickUpDateTime,  $inputReturnDateTime, $inputPickUpDateTime,$inputReturnDateTime, $currentTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function getAvailableCarsByModelPickUpTimeAndReturnTime1($inputCarModel, $inputPickUpDateTime, $inputReturnDateTime, $currentTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT C.VehicleSno, C.AuxiliaryCable, C.TransmissionType, C.SeatingCapacity, C.BluetoothConnectivity, C.DailyRate, C.HourlyRate, C.Color, C.Type, C.CarModel, C.CarLocation, R.PickUpDateTime FROM car AS C INNER JOIN reservation AS R ON R.VehicleSno = C.VehicleSno WHERE ( (C.CarModel = ? ) AND (C.UnderMaintenanceFlag = 0) AND ((( R.PickUpDateTime <  ? AND R.ReturnDateTime <  ? ) OR ( R.PickUpDateTime >  ? AND R.ReturnDateTime >  ? ))) AND  R.PickUpDateTime > ? )';

   if (isset($inputCarModel) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ssssss", $inputCarModel, $inputPickUpDateTime,  $inputReturnDateTime, $inputPickUpDateTime,$inputReturnDateTime,  $currentTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation,$nextPickUpTime);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

function getMemberDrvingPlan($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername)) {
      $query = 'SELECT DrivingPlan FROM `member` WHERE Username= ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($drivingplan);
         while($preparedStatement->fetch()){
        	$result[]=array($drivingplan);
		}
                  $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//////////////////////////////////////////////////////////////////////////////////////////

function getPersonalUserInfo($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername)) {
      $query = 'SELECT Username,FirstName, LastName, MiddleInit, PhoneNo, EmailAddress, DrivingPlan FROM `member` WHERE Username= ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($username,$firstname,$lastname,$middleInt, $PhoneNo, $EmailAdd, $drivingplan);
         while($preparedStatement->fetch()){
        	$result[]=array($username,$firstname,$lastname,$middleInt, $PhoneNo, $EmailAdd, $drivingplan);
		}
                  $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//echo hi;



function insertIntoReservation($username,$Pickupdatetime,$returndatetime,$returnStatus,$estimatedCost,$location, $latefees,$vehicleSno) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     //echo "<h1>".$username."</h1><br/>";
     //echo "<h1>".$Pickupdatetime."</h1><br/>";
     $query = 'insert into reservation(Username,PickUpDateTime,ReturnDateTime,ReturnStatus,EstimatedCost,LateFees, ReservationLocation,VehicleSno) values (?, ?, ?, ?,?,?,?,?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ssssssss", $username,$Pickupdatetime,$returndatetime,$returnStatus,$estimatedCost,$location, $latefees,$vehicleSno);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}


function getAvailableCarsForTypePickUpTimeAndReturnTime($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select VehicleSno, AuxiliaryCable, 
TransmissionType, SeatingCapacity, BluetoothConnectivity, 
DailyRate, HourlyRate, Color, Type, CarModel, CarLocation from car where Type = ? 
and UnderMaintenanceFlag = 0 and VehicleSno not in (select VehicleSno from reservation 
where ReturnStatus <> "Cancelled" and 
((PickUpDateTime <= ? and ReturnDateTime >= ?) or (PickUpDateTime >= ? and ReturnDateTime <= ?) or (PickUpDateTime <= ? and ReturnDateTime >= ?)))';
   if (isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("sssssss", $inputCarLocation, $inputPickUpDateTime, $inputPickUpDateTime, $inputPickUpDateTime, $inputReturnDateTime, $inputReturnDateTime, $inputReturnDateTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}


function getAvailableCarsForModelPickUpTimeAndReturnTime($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select VehicleSno, AuxiliaryCable, 
TransmissionType, SeatingCapacity, BluetoothConnectivity, 
DailyRate, HourlyRate, Color, Type, CarModel, CarLocation from car where CarModel = ? 
and UnderMaintenanceFlag = 0 and VehicleSno not in (select VehicleSno from reservation 
where ReturnStatus <> "Cancelled" and 
((PickUpDateTime <= ? and ReturnDateTime >= ?) or (PickUpDateTime >= ? and ReturnDateTime <= ?) or (PickUpDateTime <= ? and ReturnDateTime >= ?)))';
   if (isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("sssssss", $inputCarLocation, $inputPickUpDateTime, $inputPickUpDateTime, $inputPickUpDateTime, $inputReturnDateTime, $inputReturnDateTime, $inputReturnDateTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopVehicleSno, $loopAuxiliaryCable, $loopTransmissionType, $loopSeatingCapacity, $loopBluetoothConnectivity, $loopDailyRate, $loopHourlyRate, $loopColor, $loopType, $loopCarModel, $loopCarLocation);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}


//$a= getAvailableCarsForModelPickUpTimeAndReturnTime("Honda Civic","2013-4-24 12:00:00", "2013-04-25 04:00:00");
//var_dump($a);

function getAvailableDate($vehicleSno) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT MIN(PickUpDateTime) FROM reservation WHERE VehicleSno = ? AND ReturnStatus <> "Cancelled" AND PickUpDateTime > NOW( ) ';



   if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $vehicleSno);
         $preparedStatement->execute();
         $preparedStatement->bind_result($time);
         while($preparedStatement->fetch()){
         $result[]=array($time);
      }
                  $preparedStatement->close();
      }

   $mysqli->close();
   return $result;
}

//$a=getAvailableDate(2);
//var_dump($a);

//$a=insertIntoCar1(52, 1, 1, 4, 1, 90,9 , "black", "SUV", "Ford Focus", 1, "Klaus");
//$a= getAvailableCarsByModelPickUpTimeAndReturnTime1("Audi A4","2013-04-20 12:00:00","2013-04-22 12:00:00");


//var_dump($a);


?>
