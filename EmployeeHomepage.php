<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Employee Home &middot; GT Car Rental</title>
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

      .form-home {
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

      #btn-next{
        margin-left: 80%;
      }
      
    </style>
<?php
$optionsRadios = $_POST['optionsRadios'];
if (isset($optionsRadios)) {
   if ($optionsRadios == "AddCars") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/raghu_Only_AddCars.php'</script>";
   }
   elseif ($optionsRadios == "ChangeCarLocation") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/raghu_Only_ChangeCars.php'</script>";
   }

   elseif ($optionsRadios == "MaintenanceRequests") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MaintenanceRequests.php'</script>";
   }
   elseif ($optionsRadios == "RentalChangeRequest") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/RentalChangeRequest.php'</script>";
   }
   elseif ($optionsRadios == "ViewReports") {
      $reportType = $_POST['reportType'];
      if ($reportType == "Location Preference") {
         echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/LocationPreferenceReport.php'</script>";
      }
      elseif ($reportType == "Frequent Users") {
         echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/FrequentUsersReport.php'</script>";
      }
      elseif ($reportType == "Maintenance History") {
         echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MaintenanceHistoryReport.php'</script>";
      }
   }
}
?>

  </head>

  <body>
    <div class="container">

      <form class="form-home"  method="post" action="<?php echo $PHP_SELF;?>">
        <div id="wreck">
          <img src="img/wreck.gif" width="120" height="110" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="log_in.html">
          </map> 
        </div>

        <br>

        <h2 class="form-signin-heading">&nbsp;&nbsp;Employee Home</h2>

        <br><br><br>
        
        <label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios1" value="AddCars" checked autofocus>
          Add Cars
        </label>
	<label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios1" value="ChangeCarLocation" checked autofocus>
          Change Car Location
        </label>

        <label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios2" value="MaintenanceRequests">
          Maintenance Requests
        </label>
        <label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios3" value="RentalChangeRequest">
          Rental Change Request
        </label>
        <label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios4" value="ViewReports">
          View Reports
          <select name="reportType">
             <option selected="selected">Location Preference</option>
             <option>Frequent Users</option>
             <option>Maintenance History</option>
          </select>
        </label>

        <span id="btn-next">
        <button class="btn" type="submit">Next</button>
        </span>
        
      </form>

    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
