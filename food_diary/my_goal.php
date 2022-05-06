<?php
/**
*
* File: food_diary/my_goal.php
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

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
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
		</p>
	<!-- //You are here -->


	<!-- New goal -->
		<p>
		<a href=\"my_goal_new.php?l=$l\" class=\"btn btn_default\">$l_new_goal</a>
		</p>
	<!-- //New goal -->

	<!-- List my goals -->

		<h2>$l_your_goals</h2>
		<div class=\"vertical\">
			<ul>
		";

		
		$query = "SELECT goal_id, goal_user_id, goal_current_weight, goal_current_fat_percentage, goal_target_weight, goal_target_fat_percentage, goal_i_want_to, goal_weekly_goal, goal_date, goal_activity_level, goal_current_bmi, goal_target_bmi, goal_current_bmr_calories, goal_current_bmr_fat, goal_current_bmr_carbs, goal_current_bmr_proteins, goal_current_sedentary_calories, goal_current_sedentary_fat, goal_current_sedentary_carbs, goal_current_sedentary_proteins, goal_current_with_activity_calories, goal_current_with_activity_fat, goal_current_with_activity_carbs, goal_current_with_activity_proteins, goal_target_bmr_calories, goal_target_bmr_fat, goal_target_bmr_carbs, goal_target_bmr_proteins, goal_target_sedentary_calories, goal_target_sedentary_fat, goal_target_sedentary_carbs, goal_target_sedentary_proteins, goal_target_with_activity_calories, goal_target_with_activity_fat, goal_target_with_activity_carbs, goal_target_with_activity_proteins, goal_synchronized, goal_notes FROM $t_food_diary_goals WHERE goal_user_id=$get_my_user_id ORDER BY goal_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_goal_id, $get_goal_user_id, $get_goal_current_weight, $get_goal_current_fat_percentage, $get_goal_target_weight, $get_goal_target_fat_percentage, $get_goal_i_want_to, $get_goal_weekly_goal, $get_goal_date, $get_goal_activity_level, $get_goal_current_bmi, $get_goal_target_bmi, $get_goal_current_bmr_calories, $get_goal_current_bmr_fat, $get_goal_current_bmr_carbs, $get_goal_current_bmr_proteins, $get_goal_current_sedentary_calories, $get_goal_current_sedentary_fat, $get_goal_current_sedentary_carbs, $get_goal_current_sedentary_proteins, $get_goal_current_with_activity_calories, $get_goal_current_with_activity_fat, $get_goal_current_with_activity_carbs, $get_goal_current_with_activity_proteins, $get_goal_target_bmr_calories, $get_goal_target_bmr_fat, $get_goal_target_bmr_carbs, $get_goal_target_bmr_proteins, $get_goal_target_sedentary_calories, $get_goal_target_sedentary_fat, $get_goal_target_sedentary_carbs, $get_goal_target_sedentary_proteins, $get_goal_target_with_activity_calories, $get_goal_target_with_activity_fat, $get_goal_target_with_activity_carbs, $get_goal_target_with_activity_proteins, $get_goal_synchronized, $get_goal_notes) = $row;
			
			
			// Date
			$year = substr($get_goal_date, 0, 4);
			$month = substr($get_goal_date, 5, 2);
			$day = substr($get_goal_date, 8, 2);

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


			echo"				";
			echo"<li><a href=\"my_goal_open.php?goal_id=$get_goal_id&amp;l=$l\"><b>$day $month_saying $year</b><br />";

			if($get_goal_i_want_to == "loose_weight" OR $get_goal_i_want_to == "gain_weight"){
	
				// Weight from to
				echo"$l_from ";

				if($get_my_user_measurement == "imperial"){
					$get_goal_current_weight = round($get_goal_current_weight/0.45359237,2);
					echo"$get_goal_current_weight ";
				}
				else{
					echo"$get_goal_current_weight ";
				}

				echo" $l_to_lowercase ";

				if($get_my_user_measurement == "imperial"){
					$get_goal_target_weight = round($get_goal_target_weight/0.45359237,2);
					echo"$get_goal_target_weight $l_lbs_lowercase";
				}
				else{
					echo"$get_goal_target_weight $l_kg_lowercase";
				}

				// Calories
				echo"<br />
				$get_goal_target_sedentary_calories $l_calories_with_sedentary_activity_lowercase";
				if($get_goal_activity_level != "1.2"){
				
					echo"<br />
					$get_goal_target_with_activity_calories
					";
					if($get_goal_activity_level == "1.375"){
						echo"$l_calories_with_light_activity_lowercase";
					}
					elseif($get_goal_activity_level == "1.55"){
						echo"$l_calories_with_moderate_activity_lowercase";
					}
					elseif($get_goal_activity_level == "1.72"){
						echo"$l_calories_with_very_active_lifestyle_lowercase";
					}
					else{
						echo"$l_calories_with_extra_active_lifestyle_lowercase";
					}

				}
			}

			

			echo" </a></li>\n";
		}
		echo"
			</ul>
		</div>
	<!-- //List my goals -->


	";
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