<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<?php
   include 'sid_get_currentRental.php';
//calls php script with functions to retrieve information from databases
?>

    <title>Rent A car</title>
  
    <link href="css/bootstrap.css" rel="stylesheet">

    <style type="text/css">
    body{
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #f5f5f5;
    }
    .rental-info-head{
      text-align: center;
      border-bottom: 1px red solid;
    }
    .rental-info-head1{
      text-align: center;
    }
    .table-border
    {
      background-color: #fff;
      border:1px solid #e5e5e5;
    }
    .selectBlock
    {
      display: inline;
    }
  
    
    </style>
  </head>
  <body>
   <div class="container">
    <h2 class="rental-info-head">Rental Information</h2>
  </br>
    <h3 class="rental-info-head1">Current Reservations</h3>
    <div class="current-reservation">


<?php

	$username=$_SESSION['userName'];
	echo $username;

?>

<?php
var_dump($_POST);

//echo $_POST['radio1'];
//echo $_POST['extendedReturnDate'];
//echo $_POST['extendedReturnTime'];

//store form values into variables

$resID=$POST['radio1'];
$extendedDateTime= $_POST['extendedReturnDate']." ".$_POST['extendedReturnTime'];

//echo $extendedDateTime;
if(isset($_POST['radio1']))
{
if(isset($_POST['extendedReturnDate'])){
	if(isset($_POST['extendedReturnTime'])){

echo $_POST['radio1'];
echo $_POST['extendedReturnDate'];
echo $_POST['extendedReturnTime'];
echo $resID;

//calls function to insert values into reservation table in case time gets extending


insertIntoReservationExtendedTime($_POST['radio1'], $extendedDateTime);

//calls function to ipdate reservation table for a given reservation ID
updateReservationReturnDateTimeForResID($_POST['radio1'], $extendedDateTime);

echo "your reservation has been extended";
   }
  }
}

?>

      <form name="currResrvationUpdate" method="post" action="<?php echo $PHP_SELF;//php page calls itself?>">
        <table class="table table-striped">  
        <thead>  
          <tr>  
            <th>Pick Up Time</th>  
            <th>Return Time</th>  
            <th>car</th>  
            <th>Location</th> 
            <th>Amount</th>   
            <th>Extend</th>  
          </tr>  
        </thead>  
        <tbody>  


        <?php

//calls function to get the current rental information

   	$rentalInformation = getCurrentRentalInformation($username);
        $loopIdx = 1;
   	foreach ($rentalInformation as $loopRow) {
           $loopIdx++;
      	   echo "<tr>";
      	   
              echo "<td>".$loopRow[1]."</td>";
		echo "<td>".$loopRow[2]."</td>";
		echo "<td>".$loopRow[3]."</td>";
		echo "<td>".$loopRow[4]."</td>";
		echo "<td>".$loopRow[5]."</td>";
		echo "<td>".$loopRow[0]."</td>";
      	   
          echo "<td><input type=\"radio\" name=\"radio1\" value=\"".$loopRow[0]."\"></td>";
	echo "</tr>";
		
      	   
  	}
	?>  

          
      	  </tbody>  
     	 </table>  

     	 <h3 class="selectBlock" >Choose Return time</h3>
    	    <select name="extendedReturnDate" class="input-block-level1">
		<option value=""disabled selected>--select return date--</option>
              <option value="2013-04-23" >2013-04-23</option>
      		<option value="2013-04-23">2013-04-23</option>
     	       <option value="2013-04-23">2013-04-23</option>
            </select>

            <select class="input-block-level1" name="extendedReturnTime">
		<option value=""disabled selected>--select return date--</option>
              <option value="12:00:00">12:00:00</option>
            <option value="13:00:00">13:00:00</option>
            <option value="14:00:00">14:00:00</option>
            </select>
          </br>
            <button type="submit" name="submit" class="btn">Update</button>
      </form>
    </div>

    <div>
      <h3 class="rental-info-head1">Previous reservations</h3>

    </div>
      <table class="table table-striped">  
        <thead>  
          <tr>  
            <th>Pick Up Time</th>  
            <th>Return Time</th>  
            <th>car</th>  
            <th>Location</th> 
            <th>Amount</th>   
            <th>Return Status</th>  
          </tr>  
        </thead>  
        <tbody>  
<?php

//calls function to get rental history

$rentalInformation = getPreviousRentalInformation($username);
$loopIdx = 1;
foreach ($rentalInformation as $loopRow) {
   $loopIdx++;
   echo "<tr>";
   foreach ($loopRow as $rowElement) {
      echo "<td>".$rowElement."</td>";
   }
   echo "</tr>";
}
?>  

  
</tbody>  
</table>

<?php

//button to go back to homepage

if($_SESSION['usertype'] == "Member")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'>Go to home page</a>";
}
elseif($_SESSION['usertype'] == "Employee")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'>Go to home page</a>";
}
?>



<br>




   </div>

  </body>
</html>
