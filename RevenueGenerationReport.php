<!DOCTYPE html>
<?php
session_start();
include 'database.php';
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Revenue Generation Report &middot; GT Car Rental</title>
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

  </head>

  <body>
    <div class="container">

        <div id="wreck">
          <img src="img/wreck.gif" width="120" height="110" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="./LogIn.php">
          </map> 
        </div>
        <h2 class="form-signin-heading">Revenue Generation Report</h2>
        <br>
        <br>
<table border="1">
<thead>
<tr>
<th>Vehicle Sno</th>
<th>Type</th>
<th>Car Model</th>
<th>Reservation Revenue</th>
<th>Late Fees Revenue</th>
</tr>
</thead>
<?php
   $outputTable = calcRevenueGeneratedReal();
   setlocale(LC_MONETARY, 'en_US');
   foreach ($outputTable as $loopRow) {
      $reservationRevenue = money_format("%.2n", $loopRow[3]);
      $lateFees = money_format("%.2n", $loopRow[4]);
      echo "<tr><td>".$loopRow[0]."</td><td>".$loopRow[1]."</td><td>".$loopRow[2]."</td><td>".$reservationRevenue."</td><td>".$lateFees."</td></tr>\n";
   }
?>
</table>
    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
