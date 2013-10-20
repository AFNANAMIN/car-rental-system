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

<script type="text/javascript">
function processLocationChange(locationSelection) {
   document.forms['changeCarLocationForm'].submit();
}
</script>
<?php

   $changeCarLocation = $_POST['ChangeCarLocation'];
   $changeCarType = $_POST['ChangeCarType'];
   $newLocation = $_POST['newLocation'];
   $buttonName = $_POST['buttonName'];
   if (isset($buttonName) && $buttonName == "Change" && isset($changeCarLocation) && isset($changeCarType) && isset($newLocation)) {
      $vehicleSno = getCarVehicleSnoForCarLocationAndType($changeCarLocation, $changeCarType);
      if (isset($vehicleSno)) {
         updateCarCarLocationForVehicleSno($vehicleSno, $newLocation);
      }      
   }

?>

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
       $buttonName = $_POST['buttonName'];
//echo $buttonName;
       if (isset($buttonName)) {
  
          if ($buttonName == "Change") {
          }
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

        <h2 class="form-signin-heading">&nbsp;&nbsp;Manage Cars</h2>

        <br>:
<table>
<tr>
<td valign="top">
      
</td>
<td valign="top">
      <form name="changeCarLocationForm" class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;?>">
        <h4 class="form-signin-heading">Change Car Location</h3>
        <label>Choose Current Location: 
           <select placeholder="Location" name="ChangeCarLocation" onchange="processLocationChange(this);">
<?php
   $changeCarLocation = $_POST['ChangeCarLocation'];
   $locationNames = getLocationNames();
   foreach ($locationNames as $loopLocationName) {
      $selected = "";
      if (isset($changeCarLocation) && $changeCarLocation == $loopLocationName) {
         $selected = " selected";
      }
      echo "<option".$selected.">".$loopLocationName."</option>";
   }
?>
           </select>
        </label>
        <div id="ChangeCarTypeSection">
<?php
 $changeCarLocation = $_POST['ChangeCarLocation'];
     $changeCarType = $_POST['ChangeCarType'];
   if (isset($changeCarLocation)) {
      $types = getCarTypeForCarLocation($changeCarLocation);
      if (count($types) > 0) {
         echo "<label>Choose Car: <select placeholder=\"Type\" name=\"ChangeCarType\" onchange=\"processLocationChange(this);\">";
         foreach ($types as $loopType) {

            $selected = "";
            if (isset($changeCarType) && ($changeCarType == $loopType)){
              $selected = " selected ";
            }
            echo "<option value='".$loopType."'".$selected.">".$loopType."</option>";
         }
         echo "</select></label>";
      }
      else {
//         echo "<h3>No Cars At That Location</h3>";
      }
   }
?>


        </div>
        <div id="ChangeCarBriefDescriptionSection">

<?php
   $changeCarLocation = $_POST['ChangeCarLocation'];
   $changeCarType = $_POST['ChangeCarType'];

if (isset($changeCarLocation) && isset($changeCarType)) {
   $carInfo = getCarInfoForCarLocationAndType($changeCarLocation, $changeCarType);
   if (isset($carInfo)) {
       echo "<h3>Brief Description</h3>";
       echo "<label>Car Type: ".trim($carInfo[0][0])."</label>";
       echo "<label>Color: ".$carInfo[0][1]."</label>";
       echo "<label>Seating Capacity: ".$carInfo[0][2]."</label>";
       echo "<label>Transmission Type: ".$carInfo[0][3]."</label></br>";
   }

}


?>
        </div>
        <label>Choose New Location: 
           <select placeholder="Location" name="newLocation">
<?php
   $changeCarLocation = $_POST['ChangeCarLocation'];
   $changeCarType = $_POST['ChangeCarType'];
if (isset($changeCarLocation) && isset($changeCarType)) {
      $locationNames = getAvailableLocationsForTypeAndLocation($changeCarType, $changeCarLocation);
      foreach ($locationNames as $loopLocationName) {
         echo "<option>".$loopLocationName."</option>";
      }
   }
?>
           </select>
        </label>
        <span id ="reg-btn">
        <button class="btn btn-large" type="submit" name="buttonName" value="Change">Submit Changes</button>
        </span>
      </form>
</td>
</tr>
</table>
    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
