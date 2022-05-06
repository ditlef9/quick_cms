<?php
/**
*
* File: food_diary/consumed_day_has_worked_out_edit.php
* Version 1.0.0.
* Date 10:47 03.06.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['day_id'])) {
	$day_id = $_GET['day_id'];
	$day_id = output_html($day_id);
	if(!(is_numeric($day_id))){
		echo"Day not numeric";
		die;
	}
}
else{
	$day_id = "";
}
	

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "Process";
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_user_measurement, $get_my_user_dob) = $row;
	
	// Get day
	$day_id_mysql = quote_smart($link, $day_id);
	$query = "SELECT consumed_day_id, consumed_day_day_saying, consumed_day_date FROM $t_food_diary_consumed_days WHERE consumed_day_id=$day_id_mysql AND consumed_day_user_id=$get_my_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_consumed_day_id, $get_current_consumed_day_day_saying, $get_current_consumed_day_date) = $row;
	if($get_current_consumed_day_id == ""){
		echo"Error day not found";
		die;
	}
	else{
		$inp_lifestyle = $_POST['inp_lifestyle'];
		$inp_lifestyle = output_html($inp_lifestyle);
		$inp_lifestyle_mysql = quote_smart($link, $inp_lifestyle);
		
		// Update
		mysqli_query($link, "UPDATE $t_food_diary_consumed_days  SET consumed_day_lifestyle=$inp_lifestyle_mysql WHERE consumed_day_id=$get_current_consumed_day_id") or die(mysqli_error($link));

		// Get number of used 
		$query = "SELECT lifestyle_id, lifestyle_user_id, lifestyle_count_active_mon, lifestyle_count_active_tue, lifestyle_count_active_wed, lifestyle_count_active_thu, lifestyle_count_active_fri, lifestyle_count_active_sat, lifestyle_count_active_sun, lifestyle_count_sedentary_mon, lifestyle_count_sedentary_tue, lifestyle_count_sedentary_wed, lifestyle_count_sedentary_thu, lifestyle_count_sedentary_fri, lifestyle_count_sedentary_sat, lifestyle_count_sedentary_sun FROM $t_food_diary_lifestyle_selected_per_day WHERE lifestyle_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_lifestyle_id, $get_current_lifestyle_user_id, $get_current_lifestyle_count_active_mon, $get_current_lifestyle_count_active_tue, $get_current_lifestyle_count_active_wed, $get_current_lifestyle_count_active_thu, $get_current_lifestyle_count_active_fri, $get_current_lifestyle_count_active_sat, $get_current_lifestyle_count_active_sun, $get_current_lifestyle_count_sedentary_mon, $get_current_lifestyle_count_sedentary_tue, $get_current_lifestyle_count_sedentary_wed, $get_current_lifestyle_count_sedentary_thu, $get_current_lifestyle_count_sedentary_fri, $get_current_lifestyle_count_sedentary_sat, $get_current_lifestyle_count_sedentary_sun) = $row;
		if($get_current_lifestyle_id == ""){
			// Insert and get ID so we can update
			mysqli_query($link, "INSERT INTO $t_food_diary_lifestyle_selected_per_day 
			(lifestyle_id, lifestyle_user_id, lifestyle_count_active_mon, lifestyle_count_active_tue, lifestyle_count_active_wed, 
			lifestyle_count_active_thu, lifestyle_count_active_fri, lifestyle_count_active_sat, lifestyle_count_active_sun, lifestyle_count_sedentary_mon, 
			lifestyle_count_sedentary_tue, lifestyle_count_sedentary_wed, lifestyle_count_sedentary_thu, lifestyle_count_sedentary_fri, lifestyle_count_sedentary_sat, 
			lifestyle_count_sedentary_sun) 
			VALUES 
			(NULL, '$get_my_user_id', 0, 0, 0, 
			0, 0, 0, 0, 0, 
			0, 0, 0, 0, 0, 
			0)")
			or die(mysqli_error($link));

			$query = "SELECT lifestyle_id, lifestyle_user_id, lifestyle_count_active_mon, lifestyle_count_active_tue, lifestyle_count_active_wed, lifestyle_count_active_thu, lifestyle_count_active_fri, lifestyle_count_active_sat, lifestyle_count_active_sun, lifestyle_count_sedentary_mon, lifestyle_count_sedentary_tue, lifestyle_count_sedentary_wed, lifestyle_count_sedentary_thu, lifestyle_count_sedentary_fri, lifestyle_count_sedentary_sat, lifestyle_count_sedentary_sun FROM $t_food_diary_lifestyle_selected_per_day WHERE lifestyle_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_lifestyle_id, $get_current_lifestyle_user_id, $get_current_lifestyle_count_active_mon, $get_current_lifestyle_count_active_tue, $get_current_lifestyle_count_active_wed, $get_current_lifestyle_count_active_thu, $get_current_lifestyle_count_active_fri, $get_current_lifestyle_count_active_sat, $get_current_lifestyle_count_active_sun, $get_current_lifestyle_count_sedentary_mon, $get_current_lifestyle_count_sedentary_tue, $get_current_lifestyle_count_sedentary_wed, $get_current_lifestyle_count_sedentary_thu, $get_current_lifestyle_count_sedentary_fri, $get_current_lifestyle_count_sedentary_sat, $get_current_lifestyle_count_sedentary_sun) = $row;
		}

		if($get_current_consumed_day_day_saying == "Mon"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_mon + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_mon=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_mon + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_mon=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		elseif($get_current_consumed_day_day_saying == "Tue"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_tue + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_tue=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_tue + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_tue=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		elseif($get_current_consumed_day_day_saying == "Wed"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_wed + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_wed=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_wed + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_wed=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		elseif($get_current_consumed_day_day_saying == "Thu"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_thu + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_thu=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_thu + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_thu=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		elseif($get_current_consumed_day_day_saying == "Fri"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_fri + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_fri=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_fri + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_fri=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		elseif($get_current_consumed_day_day_saying == "Sat"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_sat + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_sat=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_sat + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_sat=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		elseif($get_current_consumed_day_day_saying == "Sun"){
			if($inp_lifestyle == "1"){
				$inp_count = $get_current_lifestyle_count_active_sun + 1;

				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_active_sun=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
			else{
				$inp_count = $get_current_lifestyle_count_sedentary_sun + 1;
				mysqli_query($link, "UPDATE $t_food_diary_lifestyle_selected_per_day SET lifestyle_count_sedentary_sun=$inp_count WHERE lifestyle_id=$get_current_lifestyle_id") or die(mysqli_error($link));
			}
		}
		else{
			echo"Unknown day saying";
			die;
		}

		// Header
		$url = "index.php?action=food_diary&date=$get_current_consumed_day_date&l=$l";
		header("Location: $url");
		exit;
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