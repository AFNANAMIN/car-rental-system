<?php session_start();//session start used to store the username and password throughout the time the user is logged in?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Personal Information &middot; GT Car Rental</title>
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
        max-width: 450px;
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
        margin-left: 23%;
      }
    </style>
    <?php
          include 'database.php';

//declare and initialize variables from the form values
          $firstName= $_POST['firstName'];
          $middleIni= $_POST['middleIni'];
          $lastName= $_POST['lastName'];
          $emailAddress= $_POST['emailAddress'];
          $phoneNumber= $_POST['phoneNumber'];
          $DrivingPlan= $_POST['DrivingPlan'];
          $nameOnCard= $_POST['nameOnCard'];
          $cardNumber= $_POST['cardNumber'];
          $cvv= $_POST['cvv'];
          $expDate= $_POST['expDate'];
          $billingAddress= $_POST['billingAddress']; 
	        $userName = $_SESSION['userName'];

//condition to check if all the form fields are filled in
    if (!empty($firstName) && !empty($lastName) && !empty($emailAddress) && !empty($phoneNumber) && isset($DrivingPlan) && !empty($nameOnCard) && !empty($cardNumber) && !empty($cvv) && isset($expDate) && !empty($billingAddress))
    {
 //call funtion to inser the form personal values into the database
      insertPersonalInformation($userName, $firstName, $middleIni, $lastName, $emailAddress, $phoneNumber, $DrivingPlan, $nameOnCard, $cardNumber, $cvv, $expDate, $billingAddress);
      $statusMessage = "Updated!";
    }

//check if all the form fields are filled in. Else it will print a Mission Information message.
    elseif(!empty($firstName) || !empty($lastName) || !empty($emailAddress) || !empty($phoneNumber) || isset($DrivingPlan) || !empty($nameOnCard) || !empty($cardNumber) || !empty($cvv) || isset($expDate) || !empty($billingAddress))
    {
      $statusMessage = "Missing Information";
    }
#    else
 #   {
#	 $statusMessage = "Missing Informations";
 #   }

?>

  </head>

  <body>
    <div class="container">

      <form class="form-personalinfo" method="post" action="<?php echo $PHP_SELF;//this php page calls itself and is used to store the credt card information?>">
        <div id="wreck">
          <img src="img/wreck.gif" width="120" height="110" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="log_in.html">
          </map> 
        </div>

       



        <h2 class="form-signin-heading">Personal Information</h2>

        <br>
<?php

//is there is an error message it gets printed
   if (isset($statusMessage)) {
     
      echo "<p style='font-size: 18px; height: auto; text-align: center; font-weight: bold; color: red;'> {$statusMessage} </p>";
   }

?> 
        <h4 class="form-signin-heading">General Information</h3>

<?php
//in the case the user has already filled out the personal information form, this function retrieces the information and autofills the form so that the user need not fill out the entire form in case of update
function getPersonalUserInfo($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername)) {
      $query = 'SELECT Username,FirstName, LastName, MiddleInit, PhoneNo, EmailAddress, DrivingPlan FROM `member` WHERE Username= ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($username,$firstname,$lastname,$middleInt, $PhoneNo, $EmailAdd, $drivingplan);
         while($preparedStatement->fetch()){
          $result=array($username,$firstname,$lastname,$middleInt, $PhoneNo, $EmailAdd, $drivingplan);
    }
                  $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//similar to the previous function, this function retrieves the creditcard information of the user whose details are already present in the database and autofills the forms
function getCreditCardInfo($inputUsername) {
   include 'dbinfo.php';
   $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $database);
   if ($mysqli->connect_error) {
      die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
   }

   $result=FALSE;
   if (isset($inputUsername)) {
      $query = 'SELECT CardNo, Name, CVV, ExpiryDate, BillingAdd FROM `creditcard` WHERE Username= ?';
      if ($preparedStatement = $mysqli->prepare($query)) {
         $preparedStatement->bind_param("s", $inputUsername);
         $preparedStatement->execute();
         $preparedStatement->bind_result($cardno,$name,$cvv,$expDate, $billingAdd);
         while($preparedStatement->fetch()){
          $result=array($cardno,$name,$cvv,$expDate, $billingAdd);
    }
                  $preparedStatement->close();
      }
   }
   $mysqli->close();
   return $result;
}

//checks if the username belongs to a member or not
if(validateMember($userName))
{
  
  $a = getPersonalUserInfo($userName);
  $b = getCreditCardInfo($userName);
}
//displays username information to show who is logged in
echo $_SESSION['userName'];
//this is where the autofill occurs
        if(isset($a))
        {
          echo "<input type='text' class='input-block-level' placeholder='First Name'  name='firstName' value='".$a[1]."' autofocus>";
        }
        else
        {
          echo "<input type='text' class='input-block-level' placeholder='First Name'  name='firstName' autofocus>";
        }

        if(isset($a))
        {
          echo "<input type='text' class='input-block-level' placeholder='Middle Initial' name='middleIni' value='".$a[3]."'>";#</input>";
        }
        else
        {
          echo "<input type='text' class='input-block-level' placeholder='Middle Initial' name='middleIni'>";
        }

        if(isset($a))
        {
          echo "<input type='text' class='input-block-level' placeholder='Last Name' name='lastName' value='".$a[2]."'>";#</input>";
        }
        else
        {
          echo "<input type='text' class='input-block-level' placeholder='Last Name' name='lastName'>";
        }
        
        if(isset($a))
        {
          echo "<input type='text' class='input-block-level' placeholder='Email Address' name='emailAddress' value='".$a[5]."'>";#</input>";
        }
        else
        {
          echo "<input type='text' class='input-block-level' placeholder='Email Address' name='emailAddress'>";
        }
        
        if(isset($a))
        {
          echo "<input type='text' class='input-block-level' placeholder='Phone Number' name='phoneNumber' value='".$a[4]."'>";#</input>";
        }
        else
        {
          echo "<input type='text' class='input-block-level' placeholder='Phone Number' name='phoneNumber'>";
        }
        
        

        echo "<br>";

        echo "<h4 class='form-signin-heading'>Membership Information</h3>";
        echo  "<h6 class='form-signin-heading'>Choose a Plan&nbsp;&nbsp;</h6>";

          echo "<label class='radio'>";

          if(isset($a))
          {
            if($a[6] =="Occasional Driving Plan")
            {
              echo "<input type='radio' name='DrivingPlan' id='optionsRadios1' value='Occasional Driving Plan' checked>";
            }
            else
            {
              echo "<input type='radio' name='DrivingPlan' id='optionsRadios1' value='Occasional Driving Plan'>";
            }
            
             echo "Occasional Driving Plan";
          echo "</label>";
          echo "<label class='radio'>";

            if($a[6] =="Frequent Driving Plan")
            {
              echo "<input type='radio' name='DrivingPlan' id='optionsRadios2' value='Frequent Driving Plan' checked>";
            }
            else
            {
              echo "<input type='radio' name='DrivingPlan' id='optionsRadios2' value='Frequent Driving Plan'>";
            }
            
            echo "Frequent Driving Plan";
          echo "</label>";
          echo "<label class='radio'>";
            if($a[6] =="Daily Driving Plan")
            {
              echo "<input type='radio' name='DrivingPlan' id='optionsRadios2' value='Daily Driving Plan' checked>";
            }
            else
            {
              echo "<input type='radio' name='DrivingPlan' id='optionsRadios2' value='Daily Driving Plan'>";
            }
            
            echo "Daily Driving Plan";
          }
          else
          {
            echo "<input type='radio' name='DrivingPlan' id='optionsRadios1' value='Occasional Driving Plan' checked>";
             echo "Occasional Driving Plan";
          echo "</label>";
          echo "<label class='radio'>";
            echo "<input type='radio' name='DrivingPlan' id='optionsRadios2' value='Frequent Driving Plan'>";
            echo "Frequent Driving Plan";
          echo "</label>";
          echo "<label class='radio'>";
            echo "<input type='radio' name='DrivingPlan' id='optionsRadios2' value='Daily Driving Plan'>";
            echo "Daily Driving Plan";
          }
        
?>
           <!-- below is where the link for the plan details page is given.-->
          </label>

          <h6><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/planDetails.php">View plan details</a></h6> 

        <h4 class="form-signin-heading">Payment Information</h3>

<?php
//if information exists autofill credit card information
          if(isset($b))
          {
              echo "<input type='text' class='input-block-level' placeholder='Name on Card' name='nameOnCard' value='".$b[1]."'>";
          echo "<input type='text' class='input-block-level' placeholder='Card Number' name='cardNumber' value='".$b[0]."'>";
          echo "<input type='text' class='input-block-level' placeholder='CVV' name='cvv' value='".$b[2]."'>";
          echo "<input type='text' pattern='\d{4}-\d{2}-\d{2}' class='input-block-level' placeholder='Expiry Date YYYY-MM-DD' name='expDate' value='".$b[3]."'>";
          echo "<input type='text' class='input-block-level' placeholder='Billing Address' name='billingAddress' value='".$b[4]."'>";
          }
          else
          {
            echo "<input type='text' class='input-block-level' placeholder='Name on Card' name='nameOnCard'>";
          echo "<input type='text' class='input-block-level' placeholder='Card Number' name='cardNumber'>";
          echo "<input type='text' class='input-block-level' placeholder='CVV' name='cvv'>";
          echo "<input type='text' pattern='\d{4}-\d{2}-\d{2}' class='input-block-level' placeholder='Expiry Date YYYY-MM-DD' name='expDate'>";
          echo "<input type='text' class='input-block-level' placeholder='Billing Address' name='billingAddress'>";
          }
          
?>
 
        <span id ="reg-btn">
<!-- Buttons to submit the page and go to the homepage-->
	<button class="btn" type="button"><a  href="https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php">Home</a></button>
        <button class="btn btn-large" type="submit">Submit Info</button>
        </span>
        
      </form>

    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
