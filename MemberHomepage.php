<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Home &middot; GT Car Rental</title>
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
include 'database.php';
$choice = $_POST['optionsRadios'];
if (isset($choice)) {
   if ($choice == "RentACar") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/carSearch1.php'</script>";
   }
   elseif ($choice == "PersonalInformation") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/raghu_personal_info.php'</script>";
   }
   elseif ($choice == "RentalInformation") {
      echo "<script>window.location = 'https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/sidRentalInfo1.php'</script>";
   }
}
?>
  </head>

  <body>
    <div class="container">

      <form class="form-home" method="post" action="<?php echo $PHP_SELF;?>">
        <div id="wreck">
          <img src="img/wreck.gif" width="120" height="110" alt="Logo_Wreck" title="Welcome to GTCR!" usemap="#wreck_map" />

          <map name="wreck_map">
            <area shape="rect" coords="0,0,240,220" alt="Rect_Area" href="log_in.html">
          </map> 
        </div>

        <br>

        <h2 class="form-signin-heading">&nbsp;&nbsp;Home</h2>

        <br><br><br>
        
        <label class="radio">
<?php
	if(validateMember($_SESSION['userName']))
	{
         	echo "<input type='radio' name='optionsRadios' id='optionsRadios1' value='RentACar' checked autofocus>";
	}
	else
	{
		echo "<input type='radio' name='optionsRadios' id='optionsRadios1' value='RentACar' disabled>";
	}
?>
          Rent a Car
        </label>
        <label class="radio">
          <input type="radio" name="optionsRadios" id="optionsRadios2" value="PersonalInformation">
          Enter/View Personal Information
        </label>
        <label class="radio">
<?php
        if(validateMember($_SESSION['userName']))
        {
                echo "<input type='radio' name='optionsRadios' id='optionsRadios2' value='RentalInformation'>";
	}
	else
	{
		echo "<input type='radio' name='optionsRadios' id='optionsRadios2' value='RentalInformation' disabled>";
	}
?>
          View Rental Information
        </label>

        <span id="btn-next">
        <button class="btn" type="submit">Next</button>
        </span>
        
      </form>

    </div> <!-- /container -->

   
    <script src="js/bootstrap.js"></script>
   

  </body>
</html>
