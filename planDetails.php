<?php
session_start();
//calls a php page which has function to execute commands on the database
include 'sid_get_currentRental.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Plan Details</title>
  
    <link href="css/bootstrap.css" rel="stylesheet">

    <style type="text/css">
    body{
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #f5f5f5;
    }
    .plan{
      text-align: center;
    }
    .table-border
    {
      background-color: #fff;
      border:1px solid #e5e5e5;
    }
    .link
    {text-align: right;
      color: black;}
    </style>
  </head>
  <body>
    <div class="container">
      <h2 class="plan">Driving Plans</h2>
      <div class="table-border">
  <table class="table table-striped">  
        <thead>  
          <tr>  
            <th>Driving Plan</th>  
            <th>Monthly payment</th>  
            <th>Discount</th>  
            <th>Annual Fees</th>  
          </tr>  
        </thead>  
        <tbody>  
        <?php 
//this function goes to the driving plans page and fetches details on the plans available
        $drivingPlans = getDrivingPlans();
           $loopIdx = 1;
    foreach ($drivingPlans as $loopRow) {
           $loopIdx++;
	    if($loopRow[1]==0)
		{
		 $monthlyPayment="-NA-";
		}
	  else
		{
		$monthlyPayment="$".$loopRow[1]."";
		}
		
	   	if($loopRow[2]==0)
		{
		 $discount="-NA-";
		}
	  else
		{
		$discount=$loopRow[2]."%";
		}

	    if($loopRow[3]==0)
		{
		 $annualfees="-NA-";
		}
	  else
		{
		$annualfees="$".$loopRow[3]."";
		}
           echo "<tr>";
     //a table is poplulated with fee details
	    
           echo "<td>".$loopRow[0]."</td>";
	    echo "<td>".$monthlyPayment."</td>";
	    echo "<td>".$discount."</td>";
	    echo "<td>".$annualfees."</td>";


           
          
           echo "</tr>";
    }

        ?>



 <!--         <tr>  
            <td>Occasional driving</td>  
            <td>--NA--</td>  
            <td>--NA--</td>  
            <td>$50</td>  
          </tr>  
          <tr>  
            <td>Frequent Driving</td>  
            <td>$60</td>  
            <td>10%</td>  
            <td>--NA--</td>  
          </tr>  
          <tr>  
            <td>Daily driving</td>  
            <td>$100</td>  
            <td>15%</td>  
            <td>--NA--</td>  
          </tr>  -->
        </tbody>  
      </table>  

    </div>
    <div class="link">
<?php 
//create a button to take the user back to the homepage
if($_SESSION['usertype'] == "Member")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'>Go to home page</a>";
}
elseif($_SESSION['usertype'] == "Employee")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'>Go to home page</a>";	
}
?>
  </div>
  </div>
  </body>
