<?php
//
//  validateCredentials
//  
//  This function takes as input a username and password and
//  returns true if those credentials appear in the
//  user table and false otherwise
//
function validateCredentials($inputUsername, $inputPassword) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername) && isset($inputPassword)) {
      $query = 'select count(*) as usercount from user where Username = ? and Password = ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ss", $inputUsername, $inputPassword);
         $preparedStatement->execute();
         $preparedStatement->bind_result($count);
         $preparedStatement->fetch();
         if ($count > 0) {
            $result = TRUE;
         }
         $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//
//  validate Username
//
//  This function returns TRUE if the input username appears in
//  the user table and false otherwise
//
function validateUsername($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername)) {
      $query = 'select count(*) as usercount from user where Username = ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($count);
         $preparedStatement->fetch();
         if ($count > 0) {
            $result = TRUE;
         }
         $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//
//  validateMember
//
//  This function returns true if the input username
//  appears in the member table and false otherwise
//
function validateMember($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername)) {
      $query = 'select count(*) as usercount from member where Username = ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($count);
         $preparedStatement->fetch();
         if ($count > 0) {
            $result = TRUE;
         }
         $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//
// getUserType
//
//  This function returns the type of the user, Administrator,
//  Employee, Member
//
function getUserType($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result="None";
   if (isset($inputUsername)) {
      $query = 'select count(*) as usercount from Administrator where Username = ?'; 
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($count);
         $preparedStatement->fetch();
         $preparedStatement->close();
         if ($count > 0) {
            $result = "Administrator";
         }
         else {
            $query = 'select count(*) as usercount from GTCREmployee where Username = ?'; 
            if ($preparedStatement = $mysqli->prepare($query)) {
               $preparedStatement->bind_param("s", $inputUsername);
               $preparedStatement->execute();
               $preparedStatement->bind_result($count);
               $preparedStatement->fetch();
               $preparedStatement->close();
               if ($count > 0) {
                  $result = "Employee";
               }
               else {
                  $query = 'select count(*) as usercount from user where Username = ?'; 
                  if ($preparedStatement = $mysqli->prepare($query)) {
                     $preparedStatement->bind_param("s", $inputUsername);
                     $preparedStatement->execute();
                     $preparedStatement->bind_result($count);
                     $preparedStatement->fetch();
                     $preparedStatement->close();
                     if ($count > 0) {
                        $result = "Member";
                     }
                  }
               }
            }
         }
      }
   }
   $mysqli->close();
   return $result;
}

//
//  insertPersonalInformation
//
//  This function checks whether the input username is already in the
//  member table.  If not, it inserts the information; if it is, 
//  it calls the function that updates the data in the database
//
function insertPersonalInformation($userName, $firstName, $middleInitial, $lastName, $email, $phone, $drivingPlan, $ccName, $ccNum, $cvv, $expiry, $billingAddress) {
   include 'dbinfo.php';
   $result = FALSE;
   if (!validateMember($userName)) {
      $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
      if ($mysqli->connect_error) {
         die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
      }
      $query = 'insert into member(Username, FirstName, LastName, EmailAddress, PhoneNo, DrivingPlan) values (?, ?, ?, ?, ?, ?)';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $errorValue = $preparedStatement->bind_param("ssssss", $userName, $firstName, $lastName, $email, $phone, $drivingPlan);
         $errorValue = $preparedStatement->execute();
         $preparedStatement->close();
      }
      $mysqli->close();
      $result = insertIntoCreditCard($userName, $ccNum, $ccName, $cvv, $expiry, $billingAddress);
   }
   else {
      $result = updatePersonalInformation($userName, $firstName, $middleInitial, $lastName, $email, $phone, $drivingPlan, $ccName, $ccNum, $cvv, $expiry, $billingAddress);
   }

   return $result;
}

//
//  updatePersonalInformation
//
//  This method updates the input information in the member database
//
function updatePersonalInformation($userName, $firstName, $middleInitial, $lastName, $email, $phone, $drivingPlan, $ccName, $ccNum, $cvv, $expiry, $billingAddress) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'update member set FirstName = ?, LastName = ?, EmailAddress = ?, PhoneNo = ?, DrivingPlan = ? where Username = ?';
   if ($preparedStatement = $mysqli->prepare($query)) {
      $errorValue = $preparedStatement->bind_param("ssssss", $firstName, $lastName, $email, $phone, $drivingPlan, $userName);
      $errorValue = $preparedStatement->execute();
      $preparedStatement->close();
   }
   $mysqli->close();
   updateCreditCard($userName, $ccName, $ccNum, $cvv, $expiry, $billingAddress);
   return $result;
}

//
//  updateReservationReturnStatusForResID
//
//  This function updates the ReturnStatus in the reservation table for
//  the input resID
//
function updateReservationReturnStatusForResID($resID, $newReturnStatus) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'update reservation set ReturnStatus = ? where ResID = ?';
   if ($preparedStatement = $mysqli->prepare($query)) {
      $errorValue = $preparedStatement->bind_param("ss", $newReturnStatus, $resID);
      $errorValue = $preparedStatement->execute();
      $preparedStatement->close();
   }
   $mysqli->close();
   return $result;
}

//
//  createUser
//
//  This method inserts the input information into the user table.
//  If the user is an Employee it updates the GTCREmployee table
//  with that information as well
//
function createUser($inputUsername, $inputPassword, $inputUserType) {
   include 'dbinfo.php';
   $result = FALSE;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   if (isset($inputUsername) && isset($inputPassword)) {
      $query = 'insert into user(username, password) values (?, ?)';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ss", $inputUsername, $inputPassword);
         $preparedStatement->execute();
         $preparedStatement->bind_result($count);
         $preparedStatement->fetch();
         if ($count > 0) {
            $result = TRUE;
         }
         $preparedStatement->close();
         echo "<H1>User Type is ".$inputUserType."</h1><br/>";
         echo "<H1>Result is ".$result."</h1><br/>";
         echo "<H1>User Type is ".$inputUserType."</h1><br/>";
         if ($inputUserType == "Employee") {
            insertIntoGTCREmployee($inputUsername);
         }
      }
   }

   $mysqli->close();
   return $result;
}


//
// calcRevenueGeneratedReal
//
//  This function calculates the data to be displayed on the 
//  Revenue Generated Report
//
function calcRevenueGeneratedReal() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'Select C.VehicleSno as vsno, C.Type as vtype, C.CarModel as cmodel, SUM(R.EstimatedCost) as sumrevenue, SUM(R.LateFees) as sumlatefees From car AS C, reservation As R WHERE R.PickUpDateTime >= CURDATE() - INTERVAL 3 MONTH AND C.VehicleSno = R.VehicleSno group by vsno, vtype, cmodel HAVING sumrevenue > 0.0 order by C.Type, SUMREVENUE desc';
   if ($preparedStatement = $mysqli->prepare($query)) {
      $preparedStatement->execute();
      $preparedStatement->bind_result($vehicleSno, $type, $modelName, $estimatedCost, $lateFees);
      while ($preparedStatement->fetch()) {
         if (!isset($lateFees)) {
            $lateFees = 0;
         }
         $result[] = array($vehicleSno, $type, $modelName, $estimatedCost, $lateFees);
      }
   }
   $preparedStatement->close();
   $mysqli->close();

   return $result;
}

//
//  calcLocationPreference
//
//  This function calculates the information to be displayed on the
//  Location Preference Report
//
function calcLocationPreference() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT MONTHNAME(PickUpDateTime) as monthname, ReservationLocation as rlocation, count(*) as countmonth, SUM(TIMESTAMPDIFF(HOUR,PickUpDateTime, ReturnDateTime)) as summonth FROM reservation WHERE PickUpDateTime >= CURDATE() - INTERVAL 3 MONTH GROUP BY MONTHNAME(PickUpDateTime), ReservationLocation order by summonth desc';
   if ($preparedStatement = $mysqli->prepare($query)) {
      $preparedStatement->execute();
      $preparedStatement->bind_result($monthName, $rLocation, $countMonth, $sumMonth);
      while ($preparedStatement->fetch()) {
         if (!isset($lateFees)) {
            $lateFees = 0;
         }
         $result[] = array($monthName, $rLocation, $countMonth, $sumMonth);
      }
   }
   $preparedStatement->close();
   $mysqli->close();

   return $result;
}

//
//  calcFrequentUsers
//
//  This function calculates the data to be displayed on the
//  Frequent Users Report
//
function calcFrequentUsers() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT r.Username, u.DrivingPlan, COUNT( DISTINCT r.PickUpDateTime, r.ReturnDateTime, r.VehicleSno ) / 3.0 AS usercount FROM reservation AS r, member AS u WHERE r.Username = u.Username AND r.PickUpDateTime >= CURDATE( ) - INTERVAL 3 MONTH  GROUP BY r.Username, u.DrivingPlan ORDER BY usercount DESC LIMIT 5';
   if ($preparedStatement = $mysqli->prepare($query)) {
      $preparedStatement->execute();
      $preparedStatement->bind_result($userName, $drivingPlan, $countReservations);
      while ($preparedStatement->fetch()) {
         $result[] = array($userName, $drivingPlan, $countReservations);
      }
   }
   $preparedStatement->close();
   $mysqli->close();

   return $result;
}


//
//  calcMaintenanceHistory
//
//  This function calculates the information to be displayed on the
//  Maintenance History Report
//
function calcMaintenanceHistory() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select a.CarModel, b.RequestDateTime, b.Username, c.Problem, d.NumProbs from car a, maintenance_request b, maintenance_request_problems c, (select VehicleSno , count(*) as NumProbs from maintenance_request_problems group by VehicleSno) d where a.VehicleSno = b.VehicleSno and a.VehicleSno = c.VehicleSno and a.VehicleSno = d.VehicleSno and b.RequestDateTime = c.RequestDateTime order by d.NumProbs desc';
   if ($preparedStatement = $mysqli->prepare($query)) {
      $preparedStatement->execute();
      $preparedStatement->bind_result($carModel, $requestDateTime, $userName, $problem, $numProblems);
      while ($preparedStatement->fetch()) {
         $result[] = array($carModel, $requestDateTime, $userName, $problem);
      }
   }
   $preparedStatement->close();
   $mysqli->close();

   return $result;
}


//
//  getCurrentRentalInformation
//
//  This function calculates the current and future reservation information
//  for the input username
//
function getCurrentRentalInformation($inputUsername) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select R.PickupDateTime as f1, R.ReturnDateTime as f2, C.ModelName as f3, C.LocationName as f4, R.EstimatedCost as f5 from Reservation as R, Car as C where R.VehicleSno = C.VehicleSno and R.Username=? and R.ReturnDateTime >= CURRENT_TIMESTAMP order by R.PickupDateTime desc';
   if (isset($inputUsername)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($pickupDateTime, $returnDateTime, $modelName, $locationName, $estimatedCost);
         while ($preparedStatement->fetch()) {
            $result[] = array($pickupDateTime, $returnDateTime, $modelName, $locationName, $estimatedCost);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}


//
//  getAvailableLocationsForTYpe
//
//  This funcation takes as input a car location, a car type, and pick up and return times.  
//  It finds all cars with the input time that are available during the times
//  and are NOT at the input location
//
function getAvailableLocationsForType($inputCarLocation, $inputType, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select distinct CarLocation from car where Type = ? and CarLocation <> ? and VehicleSno not in (select VehicleSno from reservation where ReturnStatus <> "Cancelled" and (ReturnDateTime <= ? or PickUpDateTIme >= ?)) order by CarLocation';
   if (isset($inputType) && isset($inputCarLocation) && isset($inputPickUpDateTime) && isset($inputReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ssss", $inputType, $inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime);
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

//
//  getAvailableLocationsForTYpe
//
//  This funcation takes as input a car location, a car type, and pick up and return times.  
//  It finds all cars with the input time that are available during the times
//  and are NOT at the input location
//
function getAvailableLocationsForTypeAndLocation($inputType, $inputCarLocation) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select distinct CarLocation from car where Type = ? and CarLocation <> ? order by  CarLocation';
   if (isset($inputType) && isset($inputCarLocation)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ss", $inputType, $inputCarLocation);
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

//
//  getAvailableCarsForLocationPickUpTimeAndReturnTime
//
//  This function returns the full information for any car at the input location
//  that is available for rent during the input time interval.
//
function getAvailableCarsForLocationPickUpTimeAndReturnTime($inputCarLocation, $inputPickUpDateTime, $inputReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select VehicleSno, AuxiliaryCable, TransmissionType, SeatingCapacity, BluetoothConnectivity, DailyRate, HourlyRate, Color, Type, CarModel, CarLocation from car where CarLocation = ? and UnderMaintenanceFlag = 0 and VehicleSno not in (select VehicleSno from reservation where ReturnStatus <> "Cancelled" and ((PickUpDateTime <= ? and ReturnDateTime >= ?) or (PickUpDateTime >= ? and ReturnDateTime <= ?) or (PickUpDateTime <= ? and ReturnDateTime >= ?)))';
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

//
//  getAvailableCarsNotAtLocationPickUpTimeAndReturnTime
//
// This function returns basic information for cars that are
//  available during the input time interval and
// are not at the input location
// 
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

//
//  getCarTypeForCarLocation
//
//  This function gets all car types available at the input location
//
function getCarTypeForCarLocation($inputCarLocation) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select distinct Type from car where CarLocation = ? order by Type';
   if (isset($inputCarLocation)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputCarLocation);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopType);
         while ($preparedStatement->fetch()) {
            $result[] = $loopType;
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

//
//  getCarInforForCarLocationAndType
//
//  This function returns display information for the car that is
//  located at the input location and is of the 
//  input type
//
function getCarInfoForCarLocationAndType($inputCarLocation, $inputType) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select Type, Color, SeatingCapacity, TransmissionType from car where CarLocation = ? and Type = ?';
   if (isset($inputCarLocation)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ss", $inputCarLocation, $inputType);
         $preparedStatement->execute();
         $preparedStatement->bind_result($loopType, $loopColor, $loopSeatingCapacity, $loopTransmissionType);
         while ($preparedStatement->fetch()) {
            $result[] = array($loopType, $loopColor, $loopSeatingCapacity, $loopTransmissionType);
         }
         $preparedStatement->close();
      }
   }
   $mysqli->close();

   return $result;
}


//
//  This function returns the data for a users
//  whose previous reservation is bumped because
//  of a late reservation
//
function getAffectedUser($vehicleSno, $oldReturnDateTime, $newReturnDateTime) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'SELECT R.ResID, R.Username, R.PickUpDateTime, R.ReturnDateTime, M.EmailAddress, M.PhoneNo from reservation R, member M where R.VehicleSno = ? and R.PickUpDateTime >= ? and R.PickUpDateTime < ? and R.Username = M.Username order by R.PickUpDateTime';
   if (isset($vehicleSno) && isset($oldReturnDateTime) && isset($newReturnDateTime)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("sss", $vehicleSno, $oldReturnDateTime, $newReturnDateTime);
         $preparedStatement->execute();
         $preparedStatement->bind_result($resID, $userName, $pickUpDateTime, $returnDateTime, $email, $phone);
         while ($preparedStatement->fetch()) {
            $result[] = array($resID, $userName, $pickUpDateTime, $returnDateTime, $email, $phone);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}


//
//  getPreviousRentalInformation
//
//  This function returns past rental information for the input user
//
function getPreviousRentalInformation($inputUsername) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select R.PickupDateTime as f1, R.ReturnDateTime as f2, C.ModelName as f3, C.LocationName as f4, R.EstimatedCost as f5, R.ReturnStatus as f6 from Reservation as R, Car as C where R.VehicleSno = C.VehicleSno and R.Username=? and R.ReturnDateTime < CURRENT_TIMESTAMP order by R.PickupDateTime desc';
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

//
//  This function returns information on rentals where the car is currently
//  in use for the input username
//
function getOpenRentalInformation($inputUsername) {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select R.ResID as f0, R.PickupDateTime as f1, R.ReturnDateTime as f2, C.CarModel as f3, C.CarLocation as f4, R.VehicleSno as f5 from reservation as R, car as C where R.VehicleSno = C.VehicleSno and R.Username=? and R.ReturnStatus in ("Out", "Late")  order by R.PickupDateTime desc';
   if (isset($inputUsername)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($resID, $pickupDateTime, $returnDateTime, $carModel, $carLocation, $vehicleSno);
         while ($preparedStatement->fetch()) {
            $result[] = array($resID, $pickupDateTime, $returnDateTime, $carModel, $carLocation, $vehicleSno);
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

//
//  
//
//  This function takes the input information and inserts it
//  into the maintenance_request and maintenance_request tables
//
function processMaintenanceRequest($vehicleSno, $requestDateTime, $userName, $problemDescription) {
   include 'dbinfo.php';

//   echo "<h1>".$vehicleSno."|".$requestDateTime."|".$userName."|".$problemDescription."</h1><br/>";
   $result = insertIntoMaintenanceRequest($vehicleSno, $requestDateTime, $userName);
   if ($result) {
      $result = insertIntoMaintenanceRequestProblems($vehicleSno, $requestDateTime, $problemDescription);
      updateCarUnderMaintenanceFlagForVehicleSno($vehicleSno, "1");
   }
   return $result;
}

//
// insertIntoMaintenanceRequest
//
//  This function inserts the input information into the
//  maintenance_request table
//
function insertIntoMaintenanceRequest($vehicleSno, $requestDateTime, $userName) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'insert into maintenance_request(VehicleSno, RequestDateTime, Username) values (?, ?, ?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("sss", $vehicleSno, $requestDateTime, $userName);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}


//
//  insertIntoGTCREmployee
//
//  This function inserts the input username into the GTCREmployee table
//
function insertIntoGTCREmployee($userName) {
     include 'dbinfo.php';
     echo "<h1>In Insert</h1><br/>";
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'insert into GTCREmployee(Username) values (?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("s", $userName);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}

//
//  insertIntoMember
//
//  This function inserts the input username into the
//  member table
//
function insertIntoMember($userName) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'insert into member(Username) values (?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("s", $userName);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}


//
//  insertIntoReservationExtendedTime
//
//  This function inserts the input information into the reservation_extended_time table
//
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


//
//  updateReservationReturnDateTimeForResID
//
//  This function updates the ReturnDateTime in the
//  reservation table for the input resID
//
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

//
//  updateCarUnderMaintenanceFlagForVehicleSno
//
//  This function updates the UnderMaintenanceFlag for the
//  input vehicleSno
//
function updateCarUnderMaintenanceFlagForVehicleSno($vehicleSno, $underMaintenanceFlag) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'update car set UnderMaintenanceFlag = ? where VehicleSno = ?';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ss", $underMaintenanceFlag, $vehicleSno);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}

//
//  updateCarCarLocationForVehicleSno
//
//  This function updates the CarLocation for the
//  input vehicleSno
//
function updateCarCarLocationForVehicleSno($vehicleSno, $carLocation) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'update car set CarLocation = ? where VehicleSno = ?';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ss", $carLocation, $vehicleSno);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}

//
//  insertIntoMaintenanceRequestProblems
//
//  This functions inserts the input information into the
//  maintenance_request_problems table
//
function insertIntoMaintenanceRequestProblems($vehicleSno, $requestDateTime, $problem) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'insert into maintenance_request_problems(VehicleSno, RequestDateTime, Problem) values (?, ?, ?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("sss", $vehicleSno, $requestDateTime, $problem);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}

//
//  incrementLateFees
//
//  This function increments the LateFees field in reservation by
//  the indicated amount for the input resID
//
function incrementLateFees($resID, $additionalLateFees) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'update reservation set LateFees = LateFees + ? where ResID = ? ';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ds", $additionalLateFees, $resID);
        $result = $preparedStatement->execute();
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}

//
//  getCarVehicleSnoForCarLocationAndType
//
//  This function get the VehicleSno for the input CarLocation and Type
//
function getCarVehicleSnoForCarLocationAndType($carLocation, $type) {
   include 'dbinfo.php';
   $result = -1;
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'select VehicleSno from car where CarLocation = ? and Type = ?';
   if (isset($carLocation) && isset($type)) {
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("ss", $carLocation, $type);
         $preparedStatement->execute();
         $preparedStatement->bind_result($vehicleSno);
         while ($preparedStatement->fetch()) {
            $result = $vehicleSno;
         }
      }
      $preparedStatement->close();
   }
   $mysqli->close();

   return $result;
}

//
//  
//
//  This function gets all the LocationName in the location table
//
function getLocationNames() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'Select distinct LocationName from location order by LocationName';
   if ($resultSet = $mysqli->query($query)) {
      while ($row = mysqli_fetch_array($resultSet)) {
         $result[] = $row['LocationName'];
      }
      $resultSet->close();
   }  

   $mysqli->close();

   return $result;
}

//
//  insertIntoCreditCard
//
//  This function inserts the input information into the
//  creditcard table
//
function insertIntoCreditCard($userName, $ccNum, $ccName, $cvv, $expiry, $billingAddress) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'insert into creditcard(UserName, CardNo, Name, CVV, ExpiryDate, BillingAdd) values (?, ?, ?, ?, ?, ?)';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ssssss", $userName, $ccNum, $ccName, $cvv, $expiry, $billingAddress);
        $preparedStatement->execute();
//        echo "Just inserted ".$userName."|".$ccName."|".$ccNum."|".$cvv."|".$expiry."|".$billingAddress;
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}

//
//  updateCreditCard
//
//  This function updates the creditcard table with the input information
//
function updateCreditCard($userName, $ccName, $ccNum, $cvv, $expiry, $billingAddress) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'update creditcard set CardNo=?, Name = ?, CVV = ?, ExpiryDate = ?, BillingAdd = ? where Username = ?';
     if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ssssss", $ccNum, $ccName, $cvv, $expiry, $billingAddress, $userName);
        $preparedStatement->execute();
//        echo "Just updated ".$userName."|".$ccName."|".$ccNum."|".$cvv."|".$expiry."|".$billingAddress;
        $preparedStatement->close();
     }
     $mysqli->close();
     return $result;
}


//
//  insertIntoCar
//
//  This function inserts into the car table the input information
//
function insertIntoCar($vehicleSno, $carModel, $carType, $location, $color, $hourlyRate, $dailyRate, $seatingCapacity, $transmissionType, $bluetoothConnectivity, $auxiliaryCable) {
     include 'dbinfo.php';
     $result = FALSE;
     $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
     if ($mysqli->connect_error) {
        die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
     }
     $query = 'insert into car(VehicleSno, AuxiliaryCable, TransmissionType, SeatingCapacity, BluetoothConnectivity, DailyRate, HourlyRate, Color, Type, CarModel, UnderMaintenanceFlag, CarLocation) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        if ($preparedStatement = $mysqli->prepare($query)) {
        $preparedStatement->bind_param("ssssssssssss", $vehicleSno, $auxiliaryCable, $transmissionType, $seatingCapacity, $bluetoothConnectivity, $dailyRate, $hourlyRate, $color, $carType, $carModel, 0, $location);
	   
	   $preparedStatement->execute();
           $preparedStatement->close();
        }
   
     $mysqli->close();
     return $result;
}


//
//  getCarTypes
//
//  This function gets the distinct Types from the car table
//
function getCarTypes() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'Select distinct Type from car order by Type';
   if ($resultSet = $mysqli->query($query)) {
      while ($row = mysqli_fetch_array($resultSet)) {
         $result[] = $row['Type'];
      }
      $resultSet->close();
   }  

   $mysqli->close();

   return $result;
}


//
//  getCarModel
//
//  This funcation gets the distinct CarModel from the car table
//
function getCarModel() {
   include 'dbinfo.php';
   $result = array();
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }
   $query = 'Select distinct CarModel from car order by CarModel';
   if ($resultSet = $mysqli->query($query)) {
      while ($row = mysqli_fetch_array($resultSet)) {
         $result[] = $row['CarModel'];
      }
      $resultSet->close();
   }  

   $mysqli->close();

   return $result;
}


?>
