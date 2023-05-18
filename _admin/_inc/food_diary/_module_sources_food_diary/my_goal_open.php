<?php
/**
*
* File: food_diary/my_goals_open.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables --------------------------------------------------------------------------- */
include("_tables.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food_diary/ts_my_goal_new.php");
include("$root/_admin/_translations/site/$l/food_diary/ts_my_goal.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if (isset($_GET['goal_id'])) {
	$goal_id = $_GET['goal_id'];
	$goal_id = stripslashes(strip_tags($goal_id));
}
else{
	$goal_id = "";
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_goal - $l_food_diary";
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_my_user_measurement, $get_my_user_dob) = $row;


	$goal_id_mysql = quote_smart($link, $goal_id);
	$query = "SELECT goal_id, goal_user_id, goal_current_weight, goal_current_fat_percentage, goal_target_weight, goal_target_fat_percentage, goal_i_want_to, goal_weekly_goal, goal_date, goal_activity_level, goal_current_bmi, goal_target_bmi, goal_current_bmr_calories, goal_current_bmr_fat, goal_current_bmr_carbs, goal_current_bmr_proteins, goal_current_sedentary_calories, goal_current_sedentary_fat, goal_current_sedentary_carbs, goal_current_sedentary_proteins, goal_current_with_activity_calories, goal_current_with_activity_fat, goal_current_with_activity_carbs, goal_current_with_activity_proteins, goal_target_bmr_calories, goal_target_bmr_fat, goal_target_bmr_carbs, goal_target_bmr_proteins, goal_target_sedentary_calories, goal_target_sedentary_fat, goal_target_sedentary_carbs, goal_target_sedentary_proteins, goal_target_with_activity_calories, goal_target_with_activity_fat, goal_target_with_activity_carbs, goal_target_with_activity_proteins, goal_synchronized, goal_notes FROM $t_food_diary_goals WHERE goal_id=$goal_id_mysql AND goal_user_id='$get_my_user_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_goal_id, $get_current_goal_user_id, $get_current_goal_current_weight, $get_current_goal_current_fat_percentage, $get_current_goal_target_weight, $get_current_goal_target_fat_percentage, $get_current_goal_i_want_to, $get_current_goal_weekly_goal, $get_current_goal_date, $get_current_goal_activity_level, $get_current_goal_current_bmi, $get_current_goal_target_bmi, $get_current_goal_current_bmr_calories, $get_current_goal_current_bmr_fat, $get_current_goal_current_bmr_carbs, $get_current_goal_current_bmr_proteins, $get_current_goal_current_sedentary_calories, $get_current_goal_current_sedentary_fat, $get_current_goal_current_sedentary_carbs, $get_current_goal_current_sedentary_proteins, $get_current_goal_current_with_activity_calories, $get_current_goal_current_with_activity_fat, $get_current_goal_current_with_activity_carbs, $get_current_goal_current_with_activity_proteins, $get_current_goal_target_bmr_calories, $get_current_goal_target_bmr_fat, $get_current_goal_target_bmr_carbs, $get_current_goal_target_bmr_proteins, $get_current_goal_target_sedentary_calories, $get_current_goal_target_sedentary_fat, $get_current_goal_target_sedentary_carbs, $get_current_goal_target_sedentary_proteins, $get_current_goal_target_with_activity_calories, $get_current_goal_target_with_activity_fat, $get_current_goal_target_with_activity_carbs, $get_current_goal_target_with_activity_proteins, $get_current_goal_synchronized, $get_current_goal_notes) = $row;
	if($get_current_goal_id == ""){
		echo"
		<div id=\"error\"><p>Goal not found.</p></div>
		";
		die;
	}
	
	// Date
	$year = substr($get_current_goal_date, 0, 4);
	$month = substr($get_current_goal_date, 5, 2);
	$day = substr($get_current_goal_date, 8, 2);

	if($day < 10){
		$day = substr($day, 1, 1);
	}
	
	if($month == "01"){
		$month_saying = $l_january;
	}
	elseif($month == "02"){
		$month_saying = $l_february;
	}
	elseif($month == "03"){
		$month_saying = $l_march;
	}
	elseif($month == "04"){
		$month_saying = $l_april;
	}
	elseif($month == "05"){
		$month_saying = $l_may;
	}
	elseif($month == "06"){
		$month_saying = $l_june;
	}
	elseif($month == "07"){
		$month_saying = $l_july;
	}
	elseif($month == "08"){
		$month_saying = $l_august;
	}
	elseif($month == "09"){
		$month_saying = $l_september;
	}
	elseif($month == "10"){
		$month_saying = $l_october;
	}
	elseif($month == "11"){
		$month_saying = $l_november;
	}
	else{
		$month_saying = $l_december;
	}


	
	// Change measurment
	if(isset($_GET['measurement'])){

		$measurement = $_GET['measurement'];
		$measurement = stripslashes(strip_tags($measurement));
		$measurement_mysql = quote_smart($link, $measurement);
		
		$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$measurement_mysql WHERE user_id=$my_user_id_mysql");

		$get_my_user_measurement = "$measurement";
	}



	if($process != "1"){
	echo"
	<h1>$l_my_goal</h1>


	
	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- You are here -->
		<p><b>$l_you_are_here</b><br />
		<a href=\"index.php?l=$l\">$l_food_diary</a>
		&gt;
		<a href=\"my_goal.php?l=$l\">$l_my_goal</a>
		&gt;
		<a href=\"my_goals_open.php?goal_id=$goal_id&amp;l=$l\">$day $month_saying $year</a>
		</p>
	<!-- //You are here -->



	<!-- Goal menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"my_goal_open.php?goal_id=$goal_id&amp;l=$l\""; if($action == ""){ echo" class=\"selected\""; } echo">$l_summary</a></li>
				<li><a href=\"my_goal_open.php?action=edit&amp;goal_id=$goal_id&amp;l=$l\""; if($action == "edit"){ echo" class=\"selected\""; } echo">$l_edit</a></li>
				<li><a href=\"my_goal_open.php?action=delete&amp;goal_id=$goal_id&amp;l=$l\""; if($action == "delete"){ echo" class=\"selected\""; } echo">$l_delete</a></li>
			</ul>
		</div>
		<div style=\"clear:both;height:20px;\"></div>
	<!-- //Goal menu -->
	";
	} // process != 1

	if($action == ""){
		if($process == "1"){
			$inp_goal_current_bmr_calories = $_POST['inp_goal_current_bmr_calories'];
			$inp_goal_current_bmr_calories = output_html($inp_goal_current_bmr_calories);
			$inp_goal_current_bmr_calories_mysql = quote_smart($link, $inp_goal_current_bmr_calories);

			$inp_goal_current_bmr_fat = $_POST['inp_goal_current_bmr_fat'];
			$inp_goal_current_bmr_fat = output_html($inp_goal_current_bmr_fat);
			$inp_goal_current_bmr_fat_mysql = quote_smart($link, $inp_goal_current_bmr_fat);

			$inp_goal_current_bmr_carbs = $_POST['inp_goal_current_bmr_carbs'];
			$inp_goal_current_bmr_carbs = output_html($inp_goal_current_bmr_carbs);
			$inp_goal_current_bmr_carbs_mysql = quote_smart($link, $inp_goal_current_bmr_carbs);

			$inp_goal_current_bmr_proteins = $_POST['inp_goal_current_bmr_proteins'];
			$inp_goal_current_bmr_proteins = output_html($inp_goal_current_bmr_proteins);
			$inp_goal_current_bmr_proteins_mysql = quote_smart($link, $inp_goal_current_bmr_proteins);


			$inp_goal_target_bmr_calories = $_POST['inp_goal_target_bmr_calories'];
			$inp_goal_target_bmr_calories = output_html($inp_goal_target_bmr_calories);
			$inp_goal_target_bmr_calories_mysql = quote_smart($link, $inp_goal_target_bmr_calories);

			$inp_goal_target_bmr_fat = $_POST['inp_goal_target_bmr_fat'];
			$inp_goal_target_bmr_fat = output_html($inp_goal_target_bmr_fat);
			$inp_goal_target_bmr_fat_mysql = quote_smart($link, $inp_goal_target_bmr_fat);

			$inp_goal_target_bmr_carbs = $_POST['inp_goal_target_bmr_carbs'];
			$inp_goal_target_bmr_carbs = output_html($inp_goal_target_bmr_carbs);
			$inp_goal_target_bmr_carbs_mysql = quote_smart($link, $inp_goal_target_bmr_carbs);

			$inp_goal_target_bmr_proteins = $_POST['inp_goal_target_bmr_proteins'];
			$inp_goal_target_bmr_proteins = output_html($inp_goal_target_bmr_proteins);
			$inp_goal_target_bmr_proteins_mysql = quote_smart($link, $inp_goal_target_bmr_proteins);


			$inp_goal_current_sedentary_calories = $_POST['inp_goal_current_sedentary_calories'];
			$inp_goal_current_sedentary_calories = output_html($inp_goal_current_sedentary_calories);
			$inp_goal_current_sedentary_calories_mysql = quote_smart($link, $inp_goal_current_sedentary_calories);

			$inp_goal_current_sedentary_fat = $_POST['inp_goal_current_sedentary_fat'];
			$inp_goal_current_sedentary_fat = output_html($inp_goal_current_sedentary_fat);
			$inp_goal_current_sedentary_fat_mysql = quote_smart($link, $inp_goal_current_sedentary_fat);

			$inp_goal_current_sedentary_carbs = $_POST['inp_goal_current_sedentary_carbs'];
			$inp_goal_current_sedentary_carbs = output_html($inp_goal_current_sedentary_carbs);
			$inp_goal_current_sedentary_carbs_mysql = quote_smart($link, $inp_goal_current_sedentary_carbs);

			$inp_goal_current_sedentary_proteins = $_POST['inp_goal_current_sedentary_proteins'];
			$inp_goal_current_sedentary_proteins = output_html($inp_goal_current_sedentary_proteins);
			$inp_goal_current_sedentary_proteins_mysql = quote_smart($link, $inp_goal_current_sedentary_proteins);



			$inp_goal_target_sedentary_calories = $_POST['inp_goal_target_sedentary_calories'];
			$inp_goal_target_sedentary_calories = output_html($inp_goal_target_sedentary_calories);
			$inp_goal_target_sedentary_calories_mysql = quote_smart($link, $inp_goal_target_sedentary_calories);

			$inp_goal_target_sedentary_fat = $_POST['inp_goal_target_sedentary_fat'];
			$inp_goal_target_sedentary_fat = output_html($inp_goal_target_sedentary_fat);
			$inp_goal_target_sedentary_fat_mysql = quote_smart($link, $inp_goal_target_sedentary_fat);

			$inp_goal_target_sedentary_carbs = $_POST['inp_goal_target_sedentary_carbs'];
			$inp_goal_target_sedentary_carbs = output_html($inp_goal_target_sedentary_carbs);
			$inp_goal_target_sedentary_carbs_mysql = quote_smart($link, $inp_goal_target_sedentary_carbs);

			$inp_goal_target_sedentary_proteins = $_POST['inp_goal_target_sedentary_proteins'];
			$inp_goal_target_sedentary_proteins = output_html($inp_goal_target_sedentary_proteins);
			$inp_goal_target_sedentary_proteins_mysql = quote_smart($link, $inp_goal_target_sedentary_proteins);



			$inp_goal_current_with_activity_calories = $_POST['inp_goal_current_with_activity_calories'];
			$inp_goal_current_with_activity_calories = output_html($inp_goal_current_with_activity_calories);
			$inp_goal_current_with_activity_calories_mysql = quote_smart($link, $inp_goal_current_with_activity_calories);

			$inp_goal_current_with_activity_fat = $_POST['inp_goal_current_with_activity_fat'];
			$inp_goal_current_with_activity_fat = output_html($inp_goal_current_with_activity_fat);
			$inp_goal_current_with_activity_fat_mysql = quote_smart($link, $inp_goal_current_with_activity_fat);

			$inp_goal_current_with_activity_carbs = $_POST['inp_goal_current_with_activity_carbs'];
			$inp_goal_current_with_activity_carbs = output_html($inp_goal_current_with_activity_carbs);
			$inp_goal_current_with_activity_carbs_mysql = quote_smart($link, $inp_goal_current_with_activity_carbs);

			$inp_goal_current_with_activity_proteins = $_POST['inp_goal_current_with_activity_proteins'];
			$inp_goal_current_with_activity_proteins = output_html($inp_goal_current_with_activity_proteins);
			$inp_goal_current_with_activity_proteins_mysql = quote_smart($link, $inp_goal_current_with_activity_proteins);


			$inp_goal_target_with_activity_calories = $_POST['inp_goal_target_with_activity_calories'];
			$inp_goal_target_with_activity_calories = output_html($inp_goal_target_with_activity_calories);
			$inp_goal_target_with_activity_calories_mysql = quote_smart($link, $inp_goal_target_with_activity_calories);

			$inp_goal_target_with_activity_fat = $_POST['inp_goal_target_with_activity_fat'];
			$inp_goal_target_with_activity_fat = output_html($inp_goal_target_with_activity_fat);
			$inp_goal_target_with_activity_fat_mysql = quote_smart($link, $inp_goal_target_with_activity_fat);

			$inp_goal_target_with_activity_carbs = $_POST['inp_goal_target_with_activity_carbs'];
			$inp_goal_target_with_activity_carbs = output_html($inp_goal_target_with_activity_carbs);
			$inp_goal_target_with_activity_carbs_mysql = quote_smart($link, $inp_goal_target_with_activity_carbs);

			$inp_goal_target_with_activity_proteins = $_POST['inp_goal_target_with_activity_proteins'];
			$inp_goal_target_with_activity_proteins = output_html($inp_goal_target_with_activity_proteins);
			$inp_goal_target_with_activity_proteins_mysql = quote_smart($link, $inp_goal_target_with_activity_proteins);

			// Update

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_current_bmr_calories=$inp_goal_current_bmr_calories_mysql, 
			goal_current_bmr_fat=$inp_goal_current_bmr_fat_mysql, 
			goal_current_bmr_carbs=$inp_goal_current_bmr_carbs_mysql, 
			goal_current_bmr_proteins=$inp_goal_current_bmr_proteins_mysql, 
			goal_current_sedentary_calories=$inp_goal_current_sedentary_calories_mysql, 
			goal_current_sedentary_fat=$inp_goal_current_sedentary_fat_mysql, 
			goal_current_sedentary_carbs=$inp_goal_current_sedentary_carbs_mysql, 
			goal_current_sedentary_proteins=$inp_goal_current_sedentary_proteins_mysql, 
			goal_current_with_activity_calories=$inp_goal_current_with_activity_calories_mysql,
			goal_current_with_activity_fat=$inp_goal_current_with_activity_fat_mysql, 
			goal_current_with_activity_carbs=$inp_goal_current_with_activity_carbs_mysql, 
			goal_current_with_activity_proteins=$inp_goal_current_with_activity_proteins_mysql, 
			goal_target_bmr_calories=$inp_goal_target_bmr_calories_mysql, 
			goal_target_bmr_fat=$inp_goal_target_bmr_fat_mysql, 
			goal_target_bmr_carbs=$inp_goal_target_bmr_carbs_mysql, 
			goal_target_bmr_proteins=$inp_goal_target_bmr_proteins_mysql, 
			goal_target_sedentary_calories=$inp_goal_target_sedentary_calories_mysql, 
			goal_target_sedentary_fat=$inp_goal_target_sedentary_fat_mysql, 
			goal_target_sedentary_carbs=$inp_goal_target_sedentary_carbs_mysql, 
			goal_target_sedentary_proteins=$inp_goal_target_sedentary_proteins_mysql, 
			goal_target_with_activity_calories=$inp_goal_target_with_activity_calories_mysql,
			goal_target_with_activity_fat=$inp_goal_target_with_activity_fat_mysql, 
			goal_target_with_activity_carbs=$inp_goal_target_with_activity_carbs_mysql, 
			goal_target_with_activity_proteins=$inp_goal_target_with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$url = "my_goal_open.php?goal_id=$goal_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h2>$l_summary</h2>
		<!-- Easy summary -->
			<ul>
			";
			if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
				// Weight from to
				echo"<li><p>$l_from ";

				if($get_my_user_measurement == "imperial"){
					$get_current_goal_current_weight = round($get_current_goal_current_weight/0.45359237,2);
					echo"$get_current_goal_current_weight ";
				}
				else{
					echo"$get_current_goal_current_weight ";
				}

				echo" $l_to_lowercase ";

				if($get_my_user_measurement == "imperial"){
					$get_current_goal_target_weight = round($get_current_goal_target_weight/0.45359237,2);
					echo"$get_current_goal_target_weight $l_lbs_lowercase";
				}
				else{
					echo"$get_current_goal_target_weight $l_kg_lowercase";
				}
				echo"
				</p></li>


				";

				// Calories
				echo"
				<li><p>
				
				$l_to_keep_your_current_weight_you_should_eat
				$get_current_goal_current_sedentary_calories $l_calories_lowercase
				$l_on_days_without_activity_lowercase 
				$l_with_activity_you_should_eat
				$get_current_goal_current_with_activity_calories
				 $l_calories_lowercase.
				</p></li>
				";
				if($get_current_goal_activity_level != "1.2"){
				
					echo"
					<li><p>
					";
					if($get_my_user_measurement == "imperial"){
						echo"$l_to_loose_one_point_one_lbs_a_week";
					}
					else{
						echo"$l_to_loose_zero_point_five_kg_a_week";
					}
					echo"
					$l_your_body_should_have_lowercase 
					$get_current_goal_target_sedentary_calories
					$l_calories_lowercase $l_without_activity_lowercase.
					$l_thats
					$get_current_goal_target_with_activity_calories
					$l_calories_lowercase 
					$l_with_activity_lowerase</p></li>\n";

				}
			}
			else{
				echo"<li><p>$l_keep_my_weight_at ";
				if($get_my_user_measurement == "imperial"){
					$get_current_goal_current_weight = round($get_current_goal_current_weight/0.45359237,2);
					echo"$get_current_goal_current_weight $lbs_lowercase.";
				}
				else{
					echo"$get_current_goal_current_weight $l_kg_lowercase. ";
				}
				echo"</p></li>";
			}

			echo"
			</ul>
		<!-- //Easy summary -->

		<!-- Detailed summary -->
			<h2>$l_detailed_summary</h2>

			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_goal_current_bmr_calories\"]').focus();
				});
			</script>
			<form method=\"post\" action=\"my_goal_open.php?goal_id=$goal_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
			   </th>
			   <th scope=\"col\">
				<span><b>$l_calories</b></span>
			   </th>
			   <th scope=\"col\">
				<span><b>$l_fat</b></span>
			   </th>
			   <th scope=\"col\">
				<span><b>$l_carbs</b></span>
			   </th>
			   <th scope=\"col\">
				<span><b>$l_proteins</b></span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			  <tr>
			   <td colspan=\"5\">
				<span><b>BMR</b></span>
			   </td>
			  </tr>
			  <tr>
			   <td>
				<span>$l_to_keep_your_weight</span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_bmr_calories\" value=\"$get_current_goal_current_bmr_calories\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_bmr_fat\" value=\"$get_current_goal_current_bmr_fat\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
			 	<span><input type=\"text\" name=\"inp_goal_current_bmr_carbs\" value=\"$get_current_goal_current_bmr_carbs\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_bmr_proteins\" value=\"$get_current_goal_current_bmr_proteins\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			  </tr>";
			if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
				 echo"
				 <tr>
				   <td>
					<span>"; 
					if($get_current_goal_i_want_to == "loose_weight"){
						if($get_my_user_measurement == "imperial"){
							echo"$l_to_loose_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_loose_zero_point_five_kg_a_week";
						}
					}
					else{
						if($get_my_user_measurement == "imperial"){
							echo"$l_to_gain_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_gain_zero_point_five_kg_a_week";
						}
					}

					echo"</span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_bmr_calories\" value=\"$get_current_goal_target_bmr_calories\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_bmr_fat\" value=\"$get_current_goal_target_bmr_fat\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				   <td>
			 		<span><input type=\"text\" name=\"inp_goal_target_bmr_carbs\" value=\"$get_current_goal_target_bmr_carbs\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
			 	  <td>
					<span><input type=\"text\" name=\"inp_goal_target_bmr_proteins\" value=\"$get_current_goal_target_bmr_proteins\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				  </tr>
				";
			}
			echo"
			  <tr>
			   <td colspan=\"5\">
				<span><b>$l_sedentary</b></span>
			   </td>
			  </tr>
			  <tr>
			   <td>
				<span>$l_to_keep_your_weight</span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_sedentary_calories\" value=\"$get_current_goal_current_sedentary_calories\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_sedentary_fat\" value=\"$get_current_goal_current_sedentary_fat\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
			 	<span><input type=\"text\" name=\"inp_goal_current_sedentary_carbs\" value=\"$get_current_goal_current_sedentary_carbs\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_sedentary_proteins\" value=\"$get_current_goal_current_sedentary_proteins\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			  </tr>";
			if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
				 echo"
				 <tr>
				   <td>
					<span>"; 
					if($get_current_goal_i_want_to == "loose_weight"){
						if($get_my_user_measurement == "imperial"){
							echo"$l_to_loose_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_loose_zero_point_five_kg_a_week";
						}
					}
					else{
						if($get_my_user_measurement == "imperial"){
							echo"$l_to_gain_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_gain_zero_point_five_kg_a_week";
						}
					}

					echo"</span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_sedentary_calories\" value=\"$get_current_goal_target_sedentary_calories\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_sedentary_fat\" value=\"$get_current_goal_target_sedentary_fat\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				   <td>
			 		<span><input type=\"text\" name=\"inp_goal_target_sedentary_carbs\" value=\"$get_current_goal_target_sedentary_carbs\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
			 	  <td>
					<span><input type=\"text\" name=\"inp_goal_target_sedentary_proteins\" value=\"$get_current_goal_target_sedentary_proteins\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				  </tr>
				";
			}
			if($get_current_goal_activity_level != "1.2"){
				if($get_current_goal_activity_level == "1.375"){
					$activity_level_saying = "$l_lightly_active";
				}
				elseif($get_current_goal_activity_level == "1.55"){
					$activity_level_saying = "$l_moderatetely_active";
				}
				elseif($get_current_goal_activity_level == "1.72"){
					$activity_level_saying = "$l_very_active";
				}
				else{
					$activity_level_saying = "$l_extra_active";
				}

				echo"
				  <tr>
				   <td colspan=\"5\">
					<span><b>$activity_level_saying</b></span>
				   </td>
				  </tr>
				  <tr>
				   <td>
					<span>$l_to_keep_your_weight</span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_current_with_activity_calories\" value=\"$get_current_goal_current_with_activity_calories\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_current_with_activity_fat\" value=\"$get_current_goal_current_with_activity_fat\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
				 	<span><input type=\"text\" name=\"inp_goal_current_with_activity_carbs\" value=\"$get_current_goal_current_with_activity_carbs\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_current_with_activity_proteins\" value=\"$get_current_goal_current_with_activity_proteins\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				  </tr>";
					if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
						 echo"
						 <tr>
						   <td>
							<span>"; 
							if($get_current_goal_i_want_to == "loose_weight"){
								if($get_my_user_measurement == "imperial"){
									echo"$l_to_loose_one_point_one_lbs_a_week";
								}
								else{
									echo"$l_to_loose_zero_point_five_kg_a_week";
								}
							}
							else{
								if($get_my_user_measurement == "imperial"){
									echo"$l_to_gain_one_point_one_lbs_a_week";
								}
								else{
									echo"$l_to_gain_zero_point_five_kg_a_week";
								}
							}
	
						echo"</span>
					   </td>
					   <td>
						<span><input type=\"text\" name=\"inp_goal_target_with_activity_calories\" value=\"$get_current_goal_target_with_activity_calories\" size=\"4\" class=\"no_frame\" /></span>
					   </td>
					   <td>
						<span><input type=\"text\" name=\"inp_goal_target_with_activity_fat\" value=\"$get_current_goal_target_with_activity_fat\" size=\"4\" class=\"no_frame\" /></span>
			 		  </td>
					   <td>
			 			<span><input type=\"text\" name=\"inp_goal_target_with_activity_carbs\" value=\"$get_current_goal_target_with_activity_carbs\" size=\"4\" class=\"no_frame\" /></span>
			 		  </td>
			 		  <td>
						<span><input type=\"text\" name=\"inp_goal_target_with_activity_proteins\" value=\"$get_current_goal_target_with_activity_proteins\" size=\"4\" class=\"no_frame\" /></span>
			 		  </td>
					  </tr>
					";
				}
			} // activity_level_over_1.2
			echo"
			 </tbody>
			</table>
			<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn_default\" /></p>
		<!-- //Detailed summary -->
		";
	}
	elseif($action == "edit"){
		if($process == "1"){
			 // Current Weight
			$inp_current_weight = $_POST['inp_current_weight'];
			$inp_current_weight = output_html($inp_current_weight);
			$inp_current_weight = str_replace(",", ".", $inp_current_weight);

			if($get_my_user_measurement == "imperial"){
				// Convert to kg
				$inp_current_weight = round($inp_current_weight*0.45359237,0);
			}

			$inp_current_weight_mysql = quote_smart($link, $inp_current_weight);

			// Current BMI
			// BMI = m/h^2
			$height_meter = $get_my_user_height/100;
			$height_squared = $height_meter*$height_meter;
			$current_bmi = round($inp_current_weight/$height_squared,1);
			$current_bmi_mysql = quote_smart($link, $current_bmi);
			
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_current_weight=$inp_current_weight_mysql, goal_current_bmi=$current_bmi_mysql WHERE goal_user_id='$get_my_user_id'");

		
			// Target weight
			$inp_target_weight = $_POST['inp_target_weight'];
			$inp_target_weight = output_html($inp_target_weight);
			$inp_target_weight = str_replace(",", ".", $inp_target_weight);

			if($get_my_user_measurement == "imperial"){
				// Convert to kg
				$inp_target_weight = round($inp_target_weight*0.45359237,0);
			}

			$inp_target_weight_mysql = quote_smart($link, $inp_target_weight);
			

			// Gain or loose?
			$weight_diff = $get_current_goal_current_weight - $inp_target_weight;
			if($weight_diff > 0){
				$inp_goal_i_want_to = "loose_weight";
				$inp_goal_weekly_goal = "0.5";
			}
			elseif($weight_diff < 0){
				$inp_goal_i_want_to = "gain_weight";
				$inp_goal_weekly_goal = "0.5";
			}
			else{
				$inp_goal_i_want_to = "keep_my_weight";
				$inp_goal_weekly_goal = "0";
			}

			// Target BMI
			// BMI = m/h^2
			$height_meter = $get_my_user_height/100;
			$height_squared = $height_meter*$height_meter;
			$target_bmi = round($inp_target_weight/$height_squared,1);
			$target_bmi_mysql = quote_smart($link, $target_bmi);
			
			
			// Update target weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_target_weight=$inp_target_weight_mysql, goal_i_want_to='$inp_goal_i_want_to', goal_weekly_goal='$inp_goal_weekly_goal', goal_target_bmi=$target_bmi_mysql WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");
		

			
			$inp_activity_level = $_POST['inp_activity_level'];
			$inp_activity_level = output_html($inp_activity_level);
			$inp_activity_level_mysql = quote_smart($link, $inp_activity_level);
			
			$age = date('Y') - substr($get_my_user_dob, 0, 4);
			if (strtotime(date('Y-m-d')) - strtotime(date('Y') . substr($get_my_user_dob, 4, 6)) < 0){
				$age--;
			}

			/* BMR */
			if($get_my_user_gender == "male"){
				// BMR = 66.5 + (13.75 x kg body weight) + (5.003 x height in cm) - (6.755 x age)
				$bmr_calories = 66.5+(13.75*$get_current_goal_current_weight)+(5.003*$get_my_user_height)-(6.755*$age);
				$bmr_calories = round($bmr_calories, 0);
			}
			else{
				// BMR = 55.1 + (9.563 x kg body weight) + (1.850 x height in cm) - (4.676 x age)
				$bmr_calories = 655+(9.563*$get_current_goal_current_weight)+(1.850*$get_my_user_height)-(4.676*$age);
				$bmr_calories = round($bmr_calories, 0);
			}
			$bmr_calories_mysql = quote_smart($link, $bmr_calories);
			
			$bmr_fat            = round(($bmr_calories*13)/100,0); // 13 % fat
			$bmr_fat_mysql      = quote_smart($link, $bmr_fat);
			$bmr_carbs          = round(($bmr_calories*44)/100,0); // 44 % carbs
			$bmr_carbs_mysql    = quote_smart($link, $bmr_carbs);
			$bmr_proteins       = round(($bmr_calories*43)/100,0); // 13 % proteins
			$bmr_proteins_mysql = quote_smart($link, $bmr_proteins);
		

			/* Sedentary */
			$sedentary_calories = $bmr_calories*1.2;
			$sedentary_calories = round($sedentary_calories, 0);
			$sedentary_calories_mysql = quote_smart($link, $sedentary_calories);

			$sedentary_fat            = round(($sedentary_calories*13)/100,0); // 13 % fat
			$sedentary_fat_mysql      = quote_smart($link, $bmr_fat);
			$sedentary_carbs          = round(($sedentary_calories*44)/100,0); // 44 % carbs
			$sedentary_carbs_mysql    = round(($bmr_calories*44)/100,0);
			$sedentary_proteins       = round(($sedentary_calories*43)/100,0); // 13 % proteins
			$sedentary_proteins_mysql = quote_smart($link, $bmr_proteins);
			
			
			/* My activity level */
			$with_activity_calories = $bmr_calories*$inp_activity_level;
			$with_activity_calories = round($with_activity_calories, 0);
			$with_activity_calories_mysql = quote_smart($link, $with_activity_calories);

			$with_activity_fat            = round(($with_activity_calories*13)/100,0); // 13 % fat
			$with_activity_fat_mysql      = quote_smart($link, $with_activity_fat);
			$with_activity_carbs          = round(($with_activity_calories*44)/100,0); // 44 % carbs
			$with_activity_carbs_mysql    = round(($with_activity_calories*44)/100,0);
			$with_activity_proteins       = round(($with_activity_calories*43)/100,0); // 13 % proteins
			$with_activity_proteins_mysql = quote_smart($link, $with_activity_proteins);


			// Update current weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_activity_level=$inp_activity_level_mysql,
			goal_current_bmr_calories=$bmr_calories_mysql,
			goal_current_bmr_fat=$bmr_fat_mysql,
			goal_current_bmr_carbs=$bmr_carbs_mysql,
			goal_current_bmr_proteins=$bmr_proteins_mysql,
			goal_current_sedentary_calories=$sedentary_calories_mysql,
			goal_current_sedentary_fat=$sedentary_fat_mysql,
			goal_current_sedentary_carbs=$sedentary_carbs_mysql,
			goal_current_sedentary_proteins=$sedentary_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_current_with_activity_calories=$with_activity_calories_mysql,
			goal_current_with_activity_fat=$with_activity_fat_mysql,
			goal_current_with_activity_carbs=$with_activity_carbs_mysql,
			goal_current_with_activity_proteins=$with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");



			/* Loose or gain weight? */
			if($get_current_goal_i_want_to == "loose_weight"){
				$kcal_per_week = 7700*0.5;
				$kcal_per_day  = $kcal_per_week/7;
				$target_bmr_calories = round($bmr_calories - $kcal_per_day, 0);
				$target_bmr_calories_mysql = quote_smart($link, $target_bmr_calories);

				$target_sedentary_calories = round($sedentary_calories - $kcal_per_day, 0);
				$target_sedentary_calories_mysql = quote_smart($link, $target_sedentary_calories);

				$target_with_activity_calories = round($with_activity_calories - $kcal_per_day, 0);
				$target_with_activity_calories_mysql = quote_smart($link, $target_with_activity_calories);

			}
			elseif($get_current_goal_i_want_to == "gain_weight"){
				$kcal = 7700*0.5;
				$kcal_per_day  = $kcal_per_week/7;
				$target_bmr_calories = round($sedentary_calories + $kcal_per_day, 0);
				$target_bmr_calories_mysql = quote_smart($link, $target_bmr_calories);

				$target_sedentary_calories = round($sedentary_calories + $kcal_per_day, 0);
				$target_sedentary_calories_mysql = quote_smart($link, $target_sedentary_calories);

				$target_with_activity_calories = round($with_activity_calories + $kcal_per_day, 0);
				$target_with_activity_calories_mysql = quote_smart($link, $target_with_activity_calories);
			}
			else{
				$target_bmr_calories = "0";
				$target_bmr_calories_mysql = quote_smart($link, $target_bmr_calories);

			}
			
			$target_bmr_fat            = round(($target_bmr_calories*13)/100,0); // 13 % fat
			$target_bmr_fat_mysql      = quote_smart($link, $target_bmr_fat);
			$target_bmr_carbs          = round(($target_bmr_calories*44)/100,0); // 44 % carbs
			$target_bmr_carbs_mysql    = quote_smart($link, $target_bmr_carbs);
			$target_bmr_proteins       = round(($target_bmr_calories*43)/100,0); // 13 % proteins
			$target_bmr_proteins_mysql = quote_smart($link, $target_bmr_proteins);


			/* Loose or gain weight?: Sedentary */

			$target_sedentary_fat            = round(($target_sedentary_calories*13)/100,0); // 13 % fat
			$target_sedentary_fat_mysql      = quote_smart($link, $target_bmr_fat);
			$target_sedentary_carbs          = round(($target_sedentary_calories*44)/100,0); // 44 % carbs
			$target_sedentary_carbs_mysql    = round(($target_bmr_calories*44)/100,0);
			$target_sedentary_proteins       = round(($target_sedentary_calories*43)/100,0); // 13 % proteins
			$target_sedentary_proteins_mysql = quote_smart($link, $target_bmr_proteins);
			
			
			/* Loose or gain weight?:  My activity level */
			$target_with_activity_fat            = round(($target_with_activity_calories*13)/100,0); // 13 % fat
			$target_with_activity_fat_mysql      = quote_smart($link, $target_with_activity_fat);
			$target_with_activity_carbs          = round(($target_with_activity_calories*44)/100,0); // 44 % carbs
			$target_with_activity_carbs_mysql    = round(($target_with_activity_calories*44)/100,0);
			$target_with_activity_proteins       = round(($target_with_activity_calories*43)/100,0); // 13 % proteins
			$target_with_activity_proteins_mysql = quote_smart($link, $target_with_activity_proteins);


			// Update target weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_target_bmr_calories=$target_bmr_calories_mysql,
			goal_target_bmr_fat=$target_bmr_fat_mysql,
			goal_target_bmr_carbs=$target_bmr_carbs_mysql,
			goal_target_bmr_proteins=$target_bmr_proteins_mysql,
			goal_target_sedentary_calories=$target_sedentary_calories_mysql,
			goal_target_sedentary_fat=$target_sedentary_fat_mysql,
			goal_target_sedentary_carbs=$target_sedentary_carbs_mysql,
			goal_target_sedentary_proteins=$target_sedentary_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_target_with_activity_calories=$target_with_activity_calories_mysql,
			goal_target_with_activity_fat=$target_with_activity_fat_mysql,
			goal_target_with_activity_carbs=$target_with_activity_carbs_mysql,
			goal_target_with_activity_proteins=$target_with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$url = "my_goal_open.php?action=edit&goal_id=$goal_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h2>$l_edit</h2>
			<!-- My goal -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_current_weight\"]').focus();
				});
			</script>
			<form method=\"post\" action=\"my_goal_open.php?action=edit&amp;goal_id=$goal_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
			<!-- Current Weight -->
			<p><b>$l_current_weight</b>
			";
			if($get_my_user_measurement == "imperial"){
				// Convert weight to imperial
				$get_current_goal_current_weight = round($get_current_goal_current_weight/0.45359237,2);
				echo"
				(<a href=\"my_goal_open.php?action=$action&amp;goal_id=$goal_id&amp;measurement=metric&amp;l=$l\">$l_change_to_kg</a>):	<br />
				<input type=\"text\" name=\"inp_current_weight\" size=\"3\" value=\""; if($get_current_goal_current_weight != "0"){ echo"$get_current_goal_current_weight"; } echo"\" /> $l_lbs_lowercase
				</p>
				";
			}
			else{ 
				echo"
				(<a href=\"my_goal_open.php?action=$action&amp;goal_id=$goal_id&amp;measurement=imperial&amp;l=$l\">$l_change_to_lbs</a>):<br />
				<input type=\"text\" name=\"inp_current_weight\" size=\"3\" value=\"$get_current_goal_current_weight\" /> $l_kg_lowercase
				</p>
				";
			}
			echo"
			<!-- //Current Weight -->

			<!-- Target weight -->
			<p><b>$l_target_weight:</b><br />
			";
			if($get_my_user_measurement == "imperial"){
				// Convert weight to imperial
				$get_current_goal_target_weight = round($get_current_goal_target_weight/0.45359237,2);
				echo"
				<input type=\"text\" name=\"inp_target_weight\" size=\"3\" value=\"$get_current_goal_target_weight\" /> $l_lbs_lowercase
				";
			}
			else{ 
				echo"
				<input type=\"text\" name=\"inp_target_weight\" size=\"3\" value=\"$get_current_goal_target_weight\" /> $l_kg_lowercase
				";
			}
			echo"
			</p>
			<!-- //Target Weight -->

			<!-- Activity level -->
			<p><b>$l_activity_level</b><br />
			<select name=\"inp_activity_level\">
				<option value=\"1.2\""; if($get_current_goal_activity_level == "1.2"){ echo" selected=\"selected\""; } echo">$l_sedentary - $l_little_or_no_exercise_lowercase</option>
				<option value=\"1.375\""; if($get_current_goal_activity_level == "1.375"){ echo" selected=\"selected\""; } echo">$l_lightly_active - $l_exercise_sports_1_3_times_week_lowercase</option>
				<option value=\"1.55\""; if($get_current_goal_activity_level == "1.55"){ echo" selected=\"selected\""; } echo">$l_moderatetely_active - $l_exercise_sports_3_5_times_week_lowercase</option>
				<option value=\"1.725\""; if($get_current_goal_activity_level == "1.725"){ echo" selected=\"selected\""; } echo">$l_very_active - $l_hard_exercise_sports_6_7_times_week_lowercase</option>
				<option value=\"1.9\""; if($get_current_goal_activity_level == "1.9"){ echo" selected=\"selected\""; } echo">$l_extra_active - $l_very_hard_exercise_sports_or_physical_job_lowercase</option>
			</select>
			</p>
			<!-- //Target Weight -->

			<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn_default\" /></p>
		</form>	
		";
	} // edit
	elseif($action == "delete"){

		if($process == "1"){

			// Update target weight
			$result = mysqli_query($link, "DELETE FROM $t_food_diary_goals WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$url = "my_goal.php?l=$l&ft=success&fm=deleted";
			header("Location: $url");
			exit;
		}
		echo"
		<h2>$l_delete</h2>
		
		<p>$l_are_you_sure</p>

		<p>
		<a href=\"my_goal_open.php?action=$action&amp;goal_id=$goal_id&amp;l=$l&amp;process=1\">$l_delete</a>
		</p>
		";
	}
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login?l=$l&amp;referer=$root/food_diary/index.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>