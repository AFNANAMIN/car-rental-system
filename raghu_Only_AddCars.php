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

</head>
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
			 
			 $vehicleSno = $_POST['vehicleSno'];
             $carModel = $_POST['carModel'];
             $carType = $_POST['carType'];
             $location = $_POST['location'];
             $color = $_POST['color'];
             $hourlyRate = $_POST['hourlyRate'];
             $dailyRate = $_POST['dailyRate'];
             $seatingCapacity = $_POST['seatingCapacity'];
             $transmissionType = $_POST['transmissionType'];
             $bluetoothConnectivity = $_POST['bluetoothConnectivity'];
             $auxiliaryCable = $_POST['auxiliaryCable'];

             if(!empty($vehicleSno) && !empty($carModel) && !empty($color) && !empty($hourlyRate) && !empty($dailyRate) && !empty($seatingCapacity) && isset($auxiliaryCable) && isset($transmissionType) && isset($bluetoothConnectivity) )
             {
             		insertIntoCar($vehicleSno, $carModel, $carType, $location, $color, $hourlyRate, $dailyRate, $seatingCapacity, $transmissionType, $bluetoothConnectivity, $auxiliaryCable);
             }
             elseif(!empty($vehicleSno) || !empty($carModel) || !empty($color) || !empty($hourlyRate) || !empty($dailyRate) || !empty($seatingCapacity) || isset($auxiliaryCable) || isset($transmissionType) || isset($bluetoothConnectivity) )
             {
             	$statusMessage = "Missing Information!";
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

        <h2 class="form-signin-heading">&nbsp;&nbsp;Add Cars</h2>

<?php
   if (isset($statusMessage)) {
     
      echo "<p style='font-size: 18px; height: auto; text-align: center; font-weight: bold; color: red;'> {$statusMessage} </p>";
   }

?>         
        
      <form name="addCarForm" class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;?>">
        <h4 class="form-signin-heading">Add Car</h3>

        <input type="text" class="input-block-level" placeholder="Vehicle Sno" name="vehicleSno" autofocus>
        <input type="text" class="input-block-level" placeholder="Car Model" name="carModel">
        <label>Car Type: 
           <select placeholder="Car Type" name="carType">
	      <option disabled selected>--Select Car Type--</option>
              <option value='Convertible'>Convertible</option>
              <option value='Hatchback'>Hatchback</option>
              <option value='Hybrid'>Hybrid</option>
              <option value='Sedan'>Sedan</option>
              <option value='SUV'>SUV</option>
           </select>
        </label>
        <label>Location: 
           <select placeholder="Location" name="location">
              <option disabled selected>--Select Location--</option>

<?php
 $locationNames = getLocationNames();
   foreach ($locationNames as $loopLocationName) {
      echo "<option value='".$loopLocationName."'>".$loopLocationName."</option>";
   }
?>
           </select>
        </label>
        <input type="text" class="input-block-level" placeholder="Color" name="color">
        <input type="text" class="input-block-level" placeholder="Hourly Rate" name="hourlyRate">
        <input type="text" class="input-block-level" placeholder="Daily Rate" name="dailyRate">
        <input type="text" class="input-block-level" placeholder="Seating Capacity" name="seatingCapacity">
        <label>Transmission Type: 
           <select placeholder="Transmission Type" name="transmissionType">
              <option disabled selected>--Select Transmission Type--</option>
              <option value='1'>Automatic</option>
              <option value='0'>Manual</option>
           </select>
        </label>
        <label>Bluetooth Connectivity: 
           <select placeholder="Bluetooth Connectivity" name="bluetoothConnectivity">
              <option disabled selected>--Select Bluetooth Connectivity--</option>
              <option value='1'>Yes</option>
              <option value='0'>No</option>
           </select>
        </label>
        <label>Auxiliary Cable: 
           <select placeholder="Auxiliary Cable" name="auxiliaryCable">
              <option disabled selected>--Select Auxiliary Cable--</option>
              <option value='1'>Yes</option>
              <option value='0'>No</option>
           </select>
        </label>
        <span id ="reg-btn">
        <button class="btn btn-large" type="submit">Add</button>
        </span>
       

<?php

if($_SESSION['usertype'] == "Member")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'>Go to home page</a>";
}
elseif($_SESSION['usertype'] == "Employee")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'>Go to home page</a>";
}
?>
 
      </form>

        <script src="js/bootstrap.js"></script>
   

  </body>
</html>
