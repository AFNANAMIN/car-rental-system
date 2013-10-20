<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>GT Car Rental - Group 10</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
<?php
   include 'database.php';
?>
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-revenuegeneration {
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
        margin-left: 73%;
      }

    </style>

  </head>

  <body>
    <form class="form-personalinfo">
    <table class="table">
      <tr>
        <td>
          <table class="table">
            <tr>
              <td><img src="http://sird.ce.gatech.edu/Images/gatechbuzz.gif" width="50" height="50"/></td>
              <td><h2>Maintenance History Report</h2></td>
           </tr>
         </table>
       </td>
     </tr>
     <tr>
       <table class="table" border="1">
         <thead>
           <tr>
             <th>Car</th>
             <th>Date-Time</th>
             <th>Employee</th>
             <th>Problem</th>
           </tr>
         </thead>
<?php
   $outputTable = calcMaintenanceHistory();

   foreach ($outputTable as $loopRow) {
      echo "<tr><td>".$loopRow[0]."</td><td>".$loopRow[1]."</td><td>".$loopRow[2]."</td><td>".$loopRow[3]."</td></tr>\n";
   }
?>
       </table>
     </tr>
     <tr>
        <td><a href="./EmployeeHomepage.php">Back</a></td>
     </tr>
   </table>
   </form>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>

  </body>
</html>
