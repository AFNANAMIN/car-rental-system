<!DOCTYPE html>
<?php
session_start();
include 'database.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Rental Change Request &middot; GT Car Rental</title>
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
var_dump($_POST);
   if (isset($_POST['checkMember'])) {

      $memberName = $_POST['memberName'];
      if (isset($memberName)) {
         $openRental = getOpenRentalInformation($memberName);
         if (count($openRental) > 0) {
         }
         else {
            $statusMessage = "No Open Reservations";
            echo "<h1>".$statusMessage."</h1><br/>";
         }
      }
   }
   else if (isset($_POST['updateReservation'])) {
      $resID = $_POST['resID'];
      $originalReturnDateTime = $_POST['originalReturnDateTime'];
      $vehicleSno = $_POST['vehicleSno'];
      $newReturnDateTime = $_POST['newReturnDateTime'];
      $originalReturnDateTimeInt = strtotime($originalReturnDateTime);
      $newReturnDateTimeInt = strtotime($newReturnDateTime);
      updateReservationReturnDateTimeForResID($resID, $newReturnDateTime);
      insertIntoReservationExtendedTime($resID, $originalReturnDateTime);
      $affectedUser = getAffectedUser($vehicleSno, $originalReturnDateTime, $newReturnDateTime);
      var_dump($affectedUser);
      if (isset($affectedUser) && count($affectedUser) > 0) {
         $delayHours = ($newReturnDateTimeInt - $originalReturnDateTimeInt)/3600;
         $lateFees = 0;
         if ($delayHours > 0) {
            $lateFees = $delayHours * 50;
            incrementLateFees($resID, $lateFees);
         }
         $affectedResID = $affectedUser[0][0];
         $affectedUsername = $affectedUser[0][1];
         $affectedPickUpDateTime = $affectedUser[0][2];
         $affectedReturnDateTime = $affectedUser[0][3];
         $affectedEmail = $affectedUser[0][4];
         $affectedPhone = $affectedUser[0][5];
      }
   }
   else if (isset($_POST['cancelReservation'])) {
      $resID = $_POST['affectedResID'];
      updateReservationReturnStatusForResID($resID, "Cancelled");
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'</script>";
   }
   else if (isset($_POST['showCarAvailability'])) {
      $resID = $_POST['affectedResID'];
      updateReservationReturnStatusForResID($resID, "Cancelled");
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/RentACar.php'</script>";
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

        <h2 class="form-signin-heading">&nbsp;&nbsp;Rental Change Request</h2>

        <br>:
<table>
<tr>
<td valign="top">
<?php
   if (isset($statusMessage)) {
      echo "<h1>".$statusMessage."</h1><br/>";
   }
?>     
      <form name="userNameForm" class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;?>">
            <input type="text" class="input-block-level" placeholder="Enter Username" name="memberName" autofocus />
        <span id ="reg-btn">
        <button class="btn btn-large" type="submit" name="checkMember" value="Check">Check</button>
        </span>

      </form>
      <div id="rentalInformationSection">
      <form name="updateReservationForm" class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;?>">
<?php
   if (isset($openRental) && count($openRental) > 0) {
      echo "<input type=\"hidden\" name=\"resID\" value=\"".$openRental[0][0]."\" />";
      echo "<input type=\"hidden\" name=\"vehicleSno\" value=\"".$openRental[0][5]."\" />";
      echo "<table>";
      echo "   <tr>";
      echo "      <td><h3>Rental Information</h3></td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Car Model</td><td>".$openRental[0][3]."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Location</td><td>".$openRental[0][4]."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "<input type=\"hidden\" name=\"originalReturnDateTime\" value=\"".$openRental[0][2]."\" />";
      $currentReturnDateString = $openRental[0][2];
      $dateTimePieces = explode(" ", $currentReturnDateString);
      $datePieces = explode("-", $dateTimePieces[0]);
      $timePieces = explode(":", $dateTimePieces[1]);
      echo "      <td>Original Return Time:</td><td>".$datePieces[1]."/".$datePieces[2]."/".$datePieces[0]."  ".$timePieces[0].":".$timePieces[1]."</td>";
      echo "   </tr>";
      $parsedDateInt = strtotime($currentReturnDateString);
      $loopDate = date("m/d/Y h:i A", $parsedDateInt);
      $secondDate = date("m/d/Y h:i A", $parsedDateInt + 1800);
      echo "   <tr>";
      echo "      <td>New Return Time:</td>";
      echo "      <td><select name=\"newReturnDateTime\">";
      for ($numHalfHours = 1; $numHalfHours < 13; $numHalfHours++) {
         $loopIncrementedTime = $parsedDateInt + $numHalfHours*1800;
         $loopMySQLDate = date("Y-m-d h:i:s", $loopIncrementedTime);
         $loopDisplayDate = date("m/d/Y h:i A", $loopIncrementedTime);
       
         echo "       <option value=\"".$loopMySQLDate."\">".$loopDisplayDate."</option>";
      }
      echo "          </select></td>";
      echo "   </tr>";
      echo "</table>";
      echo "<button class=\"btn btn-large\" type=\"submit\" name=\"updateReservation\" value=\"Update\">Update</button> </form>";
   }
?>
      </div>
</td>
<td valign="top">
      <div id="userAffectedSection">
      <form name="affectedUserForm" class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;?>">

<?php
   if (isset($affectedUsername)) {
      echo "<input type=\"hidden\" name=\"affectedResID\" value=\"".$affectedResID."\" />";
      echo "<table>";
      echo "   <tr>";
      echo "      <td><h3>User Affected</h3></td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Username: </td><td>".$affectedUsername."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Original Pick Up Time: </td><td>".$affectedPickUpDateTime."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Original Return Time: </td><td>".$affectedReturnDateTime."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Email Address: </td><td>".$affectedEmail."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>Phone No.: </td><td>".$affectedPhone."</td>";
      echo "   </tr>";
      echo "   <tr>";
      echo "      <td>";
      echo "<button class=\"btn btn-large\" type=\"submit\" name=\"cancelReservation\" value=\"CancelReservation\">Cancel Reservation</button>";
      echo "      </td><td>";
      echo "<button class=\"btn btn-large\" type=\"submit\" name=\"showCarAvailability\" value=\"ShowCarAvailability\">Show Car Availability</button>";
      echo "      </td>";
      echo "   </tr>";
      echo "</table>";

   }

?>
      </form>
      </div>
</td>
</tr>
</table>
    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
