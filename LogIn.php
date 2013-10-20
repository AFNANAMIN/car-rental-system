<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in &middot; GT Car Rental</title>
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

      .form-signin {
        max-width: 300px;
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
       margin-left:28px;
      }

      #btn-new{
        margin-left:  20%;
      }

    </style>

<?php
include 'database.php';
$userName = $_POST['userName'];
$password = $_POST['password'];

//Condition to check for login credentials and return the appropriate error

if (!empty($userName) && !empty($password)) {
   if (validateCredentials($userName, $password)) {
      $_SESSION['userName'] = $userName;
      $userType = getUserType($userName);
      $_SESSION['usertype'] = $userType;
      if ($userType == "Member") {
         echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'</script>";
      }
      elseif ($userType == "Employee") {
         echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'</script>";
      }
      elseif ($userType == "Administrator") {
         echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/RevenueGenerationReport.php'</script>";
     }
     else {
      $statusMessage = "Invalid Username or Password";
     }
   }
   else {
      $statusMessage = "Invalid Username or Password";
   }
}
elseif (!empty($userName) || !empty($password)) {
   $statusMessage = "Missing Username or Password";
}
//$statusMessage contain the error message which is displayed
?>

  </head>

  <body>
    <div class="container">

      <form class="form-signin" method="post" action="<?php echo $PHP_SELF; //calls itself?>">
        <div id="wreck">
          <img src="img/wreck.gif" width="240" height="220" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" class="img-polaroid" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="LogIn.php">
          </map> 
        </div>

        <h2 class="form-signin-heading">GT Car Rental</h2>
<?php //conditional statement to print $statusMessage in case of error or update
   if (isset($statusMessage)) {
      echo "<p style='font-size: 18px; height: auto; text-align: center; font-weight: bold; color: red;'> {$statusMessage} </p>";
   }
?>
        <input type="text" class="input-block-level" placeholder="Username" name="userName" autofocus>
        <input type="password" class="input-block-level" placeholder="Password" name="password">
        
        <span id="btn-new">
        <button class="btn btn-link" type="button"><a href="https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/raghu_create_account.php">New User?</a></button>
        </span>
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
      </form>
    </div> <!-- /container -->
    <script src="js/bootstrap.js"></script>
  </body>
</html>
