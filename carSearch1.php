<?php
session_start();
include 'database.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Car search</title>
	
		<link href="css/bootstrap.css" rel="stylesheet">
		 <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

  <script type="text/javascript" src="jquery.timepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="jquery.timepicker.css" />

  <script type="text/javascript" src="base.js"></script>
  <link rel="stylesheet" type="text/css" href="base.css" />


		<style type="text/css">
		body{
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
		}
		.rentCar{
			max-width:500px;
			padding:19px 29px 29px;
			margin: 0 auto 20px;
			background-color: #fff;
			border:1px solid #e5e5e5;
		}
		.selectBlock{
			display: inline;
			width: 30px;
		}
		.input-block-level1{
			display: inline;
			  width: 30%;
			  min-height: 30px;
			  -webkit-box-sizing: border-box;
			  -moz-box-sizing: border-box;
			  box-sizing: border-box;
		}
		.btn
		{
			color: blue;
		}
		</style>
			


  
	</head>
	<body>
		<div class=container>
			
				
				<h2 align="center" >Rent a Car</h2>
				</br>
				
				
					<form class="rentCar" name="myForm1" method="post"   action="sid-car-trial.php">
						<div class="example">
						<!--<script src="datepair.js"></script> -->
				
						<p class="datepair" data-language="javascript">
							
							Pickup Time
							
							<input type='text' class="date  pattern='\d{4}-\d{2}-\d{2}' start" placeholder='YYYY-MM-DD' name='date-start' />
							
							<input type="text" class="time start" placeholder='12:00:00'name="time-start"/>
							</br>
							Return Time
							<input type="text" class="time end" placeholder='YYYY-MM-DD' name= "return-time"/>
							<input type="text" class="date end" placeholder='12:00:00' name="return-date"/>
						</p>
						
						
					</div>
			

        		</br>
				<h3>Search Car</h3>
				<h3>Location</h3>
				<select name="location" class="input-block-level">
			<option disable selected></option>
          		<?php
			$locationNames = getLocationNames();
			var_dump($locationNames );
  			foreach ($locationNames as $loopLocationName) {
     			echo "<option value='".$loopLocationName."'>".$loopLocationName."</option>";
  			}
 			 ?>
        		</select>

        		</br>	
				<h3>Car Type</h3>

				<select name="car-type" class="input-block-level">
			<option disable selected></option>
			<?php
			$locationNames = getCarTypes();
			//var_dump($locationNames );
  			foreach ($locationNames as $loopLocationName) {
     			echo "<option value='".$loopLocationName."'>".$loopLocationName."</option>";
  			}
			?>

        		</select>
        		<h3>Model Type</h3>

        		<select name="model-name" class="input-block-level">
			<option disable selected></option>
		<?php
		$locationNames = getCarModel();
		//var_dump($locationNames );
		foreach ($locationNames as $loopLocationName) {
		echo "<option value='".$loopLocationName."'>".$loopLocationName."</option>";
		}
		?>

	
		</select>

		<button type="submit" class="btn">Search</button>


		


<?php

if($_SESSION['usertype'] == "Member")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/MemberHomepage.php'>Go to home page</a>";
}
elseif($_SESSION['usertype'] == "Employee")
{
   echo " <a href='https://academic-php.cc.gatech.edu/groups/cs4400_Group_10/EmployeeHomepage.php'>Go to home page</a>";
}
?>

</form>
		</div>


	</body>
