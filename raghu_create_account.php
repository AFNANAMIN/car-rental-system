<?php session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Create Account &middot; GT Car Rental</title>
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

      .form-createacc {
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

      #cancel-btn{
        margin-left: 25%;
      }

      #reg-btn{
        margin-left: 0px;
      }

    </style>

    <?php

//add the database functions php file 
      include 'database.php';

//store the form values in variables via POST
      $userName = $_POST['userName'];
      $password = $_POST['password'];
      $cpassword = $_POST['cpassword'];
      $userType = $_POST['userType'];

//condition to check if all form entries are filled

    if(!empty($userName) && !empty($password) && !empty($cpassword) && isset($userType))
    {

// function validates username from the user table to avoid duplicates
        if(validateUsername($userName))
        {
          $statusMessage = "Username exists. Please choose another username";
        }
//checks if the password typed matched in both occurances
        elseif($password == $cpassword)
        {
	   $_SESSION['usertype'] = $userType;
	   $_SESSION['userName'] = $userName;
           createUser($userName,$password,$userType);   

//from $_SESSION if the user is a member the page gets redirected to the Members Hompage else it goes to the Employees Homepage.

	    if ($userType == "Member") {
        	 echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'</script>";
      		}
	    elseif ($userType == "Employee") {
        	 echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'</script>";
	        }	
        }
//if passwords do not match error message is stored
        elseif ($password != $cpassword) 
        {
            $statusMessage = "Passwords don't match";  
        }
    }
//condition to check for any missing form values
    elseif(!empty($userName) || !empty($password) || !empty($cpassword) || isset($userType))
    {
      $statusMessage = "Missing Information";
    }

    ?>

  </head>

  <body>
    <div class="container">

      <form class="form-createacc" method="post" action="<?php echo $PHP_SELF;//php page calls itself?>">
        <div id="wreck">
          <img src="img/wreck.gif" width="120" height="110" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="log_in.html">
          </map> 
        </div>

        <br>

        <h2 class="form-signin-heading">&nbsp;&nbsp;Create Account</h2>

        <br><br>
<?php
//prints out the status message if any is stored
   if (isset($statusMessage)) {
      echo "<p style='font-size: 18px; height: auto; text-align: center; font-weight: bold; color: red;'> {$statusMessage} </p>";
   }
?>
        <br>

        <input type="text" class="input-block-level" placeholder="Enter Username"  name="userName" autofocus>
        <input type="password" class="input-block-level" placeholder="Password" name="password">
        <input type="password" class="input-block-level" placeholder="Confirm Password" name="cpassword">
        
        <select class="input-block-level" name="userType">
          <option disabled selected>--Select Type of User--</option>
          <option value="Member">Georgia Tech students/faculty</option>
          <option value="Employee">GTCR employees</option>
        </select>

<!-- Button to cancel account creation and go to login page-->
        <span id="cancel-btn">
        <button class="btn btn-danger" type="button"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/LogIn.php">Cancel</a></button>
        </span>
        
        <span id ="reg-btn">
        <button class="btn btn-large btn-success" type="submit">Register</button>
        </span>
        
      </form>

    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
