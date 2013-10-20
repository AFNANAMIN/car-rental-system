<!DOCTYPE html>
<?php
session_start();
include 'database.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manage Cars &middot; GT Car Rental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RideAway">
    <meta name="author" content="Group 10:CS4400 Spring 2013">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-personalinfo {
        max-width: 400px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading{
        text-align: center;
      }
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      #wreck{
       margin-top:20px;
       float: left;
      }


      #reg-btn{
        margin-left: 60%;
      }

    </style>

   <?php
      $location = $_POST['location'];
      $car = $_POST['car'];
      $problemDescription = $_POST['problemDescription'];

//
// This page calls itself.  We are about to see if all three fields
// were input.  If so, we will insert the record in the
// maintenance_request table
//
      if (isset($location) && isset($car) && isset($problemDescription)) {
         $vehicleSno = getCarVehicleSnoForCarLocationAndType($location, $car);
         if ($vehicleSno >= 0) {
            $requestDateTime = date('Y-m-d H:i:s');
            $result = processMaintenanceRequest($vehicleSno, $requestDateTime, $_SESSION['userName'], $problemDescription); 
            if (!$result) {
               $statusMessage = "Request Failed. Try Again!";
            }
            else {
//
//  After processing, return to the Employee Homepage
//
               echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'</script>";
            }
         }
         else {
            $statusMessage = "There is no ".$car." at ".$location;
         }
//         echo "<p1>".$vehicleSno."</p1><br/>";
      }
   ?>

  </head>

  <body>
    <div class="container">

        <div id="wreck">
          <img src="img/wreck.gif" width="120" height="110" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="./LogIn.php">
          </map> 
        </div>

        <br>

        <h2 class="form-signin-heading">&nbsp;&nbsp;Maintenance Requests</h2>

<?php
//
//  If we have an abnormal status message show it
//
   if (isset($statusMessage)) {
      echo "<p style='font-size: 18px; height: auto; text-align: center; font-weight: bold; color: red;'> {$statusMessage} </p>";
   }
?>


        <br>:
<table>
<tr>
<td valign="top">
      <form name="maintenanceRequestForm" class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;?>">
        <h4 class="form-signin-heading">Maintenance Requests</h3>
        <label>Choose Location: 
           <select placeholder="Location" name="location" id="locationSelected" onchange="this.form.submit()">
<?php
//
//  Get the available locations
// 
$locationNames = getLocationNames();
   foreach ($locationNames as $loopLocationName) {
      echo "<option>".$loopLocationName."</option>";
   }
?>
           </select>
        </label>
<?php
//
//  If a locaiton is selected, get the
// avvailalb car types
//
   $selectedLocation = $_POST['location'];
   if (isset($selectedLocation)) {
      $carTypes = getCarTypesForCarLocation($selectedLocation);
   }
?>
        <label>Choose Car: 
           <select placeholder="Car" name="car" id="carSelected">
              <option>Convertible</option>
              <option>Hatchback</option>
              <option>Hybrid</option>
              <option>Sedan</option>
              <option>SUV</option>
           </select>
        </label>
        <label>Brief Description of Problem:</label>
        <textarea rows="6" name="problemDescription"></textarea>
        <span id ="reg-btn">
	 <button class="btn" type="button"><a  href="https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php">Home</a></button>
        <button class="btn btn-large" type="submit" name="submit" id="submit" value="Submit">Submit Request</button>
        </span>
      </form>
</td>
</tr>
</table>
    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
