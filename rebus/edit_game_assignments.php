<?php
/**
*
* File: rebus/edit_game_assignments.php
* Version 1.0.0.
* Date 09:50 01.07.2021
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_rebus.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['game_id'])) {
	$game_id = $_GET['game_id'];
	$game_id = output_html($game_id);
	if(!(is_numeric($game_id))){
		echo"Game id not numeric";
		die;
	}
}
else{
	echo"Missing game id";
	die;
}

$tabindex = 0;

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_measurement, user_date_format, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_rank) = $row;


	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_image_thumb_570x321, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_latitude, game_longitude, game_number_of_assignments, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_278x156, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_latitude, $get_current_game_longitude, $get_current_game_number_of_assignments, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?ft=error&fm=game_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a owner of this game --------------------------------------------- */
	$query = "SELECT owner_id, owner_game_id, owner_user_id, owner_user_name, owner_user_email FROM $t_rebus_games_owners WHERE owner_game_id=$get_current_game_id AND owner_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_owner_id, $get_my_owner_game_id, $get_my_owner_user_id, $get_my_owner_user_name, $get_my_owner_user_email) = $row;
	if($get_my_owner_id == ""){
		$url = "index.php?ft=error&fm=your_not_a_owner_of_that_game&l=$l";
		header("Location: $url");
		exit;
	}


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_assignments - $get_current_game_title - $l_my_games";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	
	if($action == ""){
		echo"
		<!-- Headline -->
			<h1>$get_current_game_title</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"my_games.php?l=$l\">$l_my_games</a>
			&gt;
			<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
			&gt;
			<a href=\"edit_game_assignments.php?game_id=$get_current_game_id&amp;l=$l\">$l_assignments</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
			echo"<div class=\"$ft\"><p>$fm</p>";

			echo"</div>";
		}
		echo"
		<!-- //Feedback -->

		<!-- Actions -->
			<p>
			<a href=\"edit_game_add_assignment.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_add_assignment</a>
			</p>
		<!-- //Actions -->

		<!-- Assignments -->
			
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th colspan=\"2\">
				<span>$l_question</span>
			   </th>
			   <th>
				<span>$l_type</span>
			   </th>
			   <th>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";
			$x = 1;
			$count_number_of_assignments = 0;
			$query = "SELECT assignment_id, assignment_game_id, assignment_number, assignment_type, assignment_value_short FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id ORDER BY assignment_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_assignment_id, $get_assignment_game_id, $get_assignment_number, $get_assignment_type, $get_assignment_value_short) = $row;

				echo"
				 <tr>
				  <td>
					<span><a href=\"edit_game_assignments.php?action=edit_assignment&amp;game_id=$get_current_game_id&amp;assignment_id=$get_assignment_id&amp;l=$l\">$get_assignment_number</a></span>
				  </td>
				  <td>
					<span><a href=\"edit_game_assignments.php?action=edit_assignment&amp;game_id=$get_current_game_id&amp;assignment_id=$get_assignment_id&amp;l=$l\">$get_assignment_value_short</a></span>
				  </td>
				  <td>
					<span>";
					if($get_assignment_type == "answer_a_question"){
						echo"$l_answer_a_question";
					}
					elseif($get_assignment_type == "take_a_picture_with_coordinates"){
						echo"$l_take_a_picture_with_coordinates";
					}
					else{
						echo"?";
					}
					echo"</span>
				  </td>
				  <td>
					<span>
					<a href=\"edit_game_assignments.php?action=move_assignment_up&amp;game_id=$get_current_game_id&amp;assignment_id=$get_assignment_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/keyboard_arrow_up_black_18x18.png\" alt=\"keyboard_arrow_up_black_18x18.png\" /></a>
					<a href=\"edit_game_assignments.php?action=move_assignment_down&amp;game_id=$get_current_game_id&amp;assignment_id=$get_assignment_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/keyboard_arrow_down_black_18x18.png\" alt=\"keyboard_arrow_down_black_18x18.png\" /></a>
					</span>
				  </td>
				 </tr>
				";


				if($x != "$get_assignment_number"){
					mysqli_query($link, "UPDATE $t_rebus_games_assignments SET assignment_number=$x WHERE assignment_id=$get_assignment_id") or die(mysqli_error($link));
				}
				$x++;
				$count_number_of_assignments++;
			}
			if($count_number_of_assignments != "$get_current_game_number_of_assignments"){
				mysqli_query($link, "UPDATE $t_rebus_games_index SET game_number_of_assignments=$count_number_of_assignments WHERE game_id=$get_current_game_id") or die(mysqli_error($link));
			}
			echo"
			 </tbody>
			</table>
		<!-- //Assignments -->
		";

	} // action == ""
	elseif($action == "edit_assignment"){
		include("_assignments_includes/edit_assignment.php");
	} // action == "edit assignment
	elseif($action == "delete_assignment"){
		if(isset($_GET['assignment_id'])) {
			$assignment_id = $_GET['assignment_id'];
			$assignment_id = output_html($assignment_id);
			if(!(is_numeric($assignment_id))){
				echo"assignment id not numeric";
				die;
			}
		}
		else{
			echo"Missing assignment id";
			die;
		}

		// Get assignment
		$assignment_id_mysql = quote_smart($link, $assignment_id);
		$query = "SELECT assignment_id, assignment_game_id, assignment_number, assignment_type, assignment_value, assignment_address, assignment_answer_a, assignment_answer_a_clean, assignment_answer_b, assignment_answer_b_clean, assignment_answer_c, assignment_answer_c_clean, assignment_answer_d, assignment_answer_d_clean, assignment_correct_alternative, assignment_radius_metric, assignment_radius_imperial, assignment_radius_user_measurment, assignment_hint_a_value, assignment_hint_a_price, assignment_hint_b_value, assignment_hint_b_price, assignment_hint_c_value, assignment_hint_c_price, assignment_points, assignment_text_when_correct_answer, assignment_time_to_solve_seconds, assignment_time_to_solve_saying, assignment_created_by_user_id, assignment_created_by_ip, assignment_created_datetime, assignment_updated_by_user_id, assignment_updated_by_ip, assignment_updated_datetime FROM $t_rebus_games_assignments WHERE assignment_id=$assignment_id_mysql AND assignment_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_assignment_id, $get_current_assignment_game_id, $get_current_assignment_number, $get_current_assignment_type, $get_current_assignment_value, $get_current_assignment_address, $get_current_assignment_answer_a, $get_current_assignment_answer_a_clean, $get_current_assignment_answer_b, $get_current_assignment_answer_b_clean, $get_current_assignment_answer_c, $get_current_assignment_answer_c_clean, $get_current_assignment_answer_d, $get_current_assignment_answer_d_clean, $get_current_assignment_correct_alternative, $get_current_assignment_radius_metric, $get_current_assignment_radius_imperial, $get_current_assignment_radius_user_measurment, $get_current_assignment_hint_a_value, $get_current_assignment_hint_a_price, $get_current_assignment_hint_b_value, $get_current_assignment_hint_b_price, $get_current_assignment_hint_c_value, $get_current_assignment_hint_c_price, $get_current_assignment_points, $get_current_assignment_text_when_correct_answer, $get_current_assignment_time_to_solve_seconds, $get_current_assignment_time_to_solve_saying, $get_current_assignment_created_by_user_id, $get_current_assignment_created_by_ip, $get_current_assignment_created_datetime, $get_current_assignment_updated_by_user_id, $get_current_assignment_updated_by_ip, $get_current_assignment_updated_datetime) = $row;
		if($get_current_assignment_id == ""){
			echo"Assignment not found";
			exit;
		}
		if($process == "1"){
			
			// Delete
			mysqli_query($link, "DELETE FROM $t_rebus_games_assignments WHERE assignment_id=$get_current_assignment_id") or die(mysqli_error($link));

			// Sort all assignments
			$x = 1;
			$query = "SELECT assignment_id, assignment_number FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id ORDER BY assignment_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_assignment_id, $get_assignment_number) = $row;

				if($x != "$get_assignment_number"){
					mysqli_query($link, "UPDATE $t_rebus_games_assignments SET assignment_number=$x WHERE assignment_id=$get_assignment_id") or die(mysqli_error($link));
				}
				$x++;
			}


			// Header
			$url = "edit_game_assignments.php?game_id=$get_current_game_id&l=$l&ft=success&fm=assignment_deleted";
			header("Location: $url");
			exit;

		} // process == 1

		echo"
		<!-- Headline -->
			<h1>$get_current_game_title</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"my_games.php?l=$l\">$l_my_games</a>
			&gt;
			<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
			&gt;
			<a href=\"edit_game_assignments.php?game_id=$get_current_game_id&amp;l=$l\">$l_assignments</a>
			&gt;
			<a href=\"edit_game_assignments.php?action=delete_assignment&amp;game_id=$get_current_game_id&amp;assignment_id=$get_current_assignment_id&amp;l=$l\">$get_current_assignment_value</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Delete assignment form -->
			<p>
			$l_are_you_sure_you_want_to_delete_the_assignment
			</p>

			<p>
			<a href=\"edit_game_assignments.php?action=delete_assignment&amp;game_id=$get_current_game_id&amp;assignment_id=$get_current_assignment_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
			<a href=\"edit_game_assignments.php?action=edit_assignment&amp;game_id=$get_current_game_id&amp;assignment_id=$get_current_assignment_id&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
			</p>
			

		<!-- //Delete assignment form -->
		";
	} // action == "Delete assignment
	elseif($action == "move_assignment_up"){
		if(isset($_GET['assignment_id'])) {
			$assignment_id = $_GET['assignment_id'];
			$assignment_id = output_html($assignment_id);
			if(!(is_numeric($assignment_id))){
				echo"assignment id not numeric";
				die;
			}
		}
		else{
			echo"Missing assignment id";
			die;
		}

		// Get assignment
		$assignment_id_mysql = quote_smart($link, $assignment_id);
		$query = "SELECT assignment_id, assignment_game_id, assignment_number, assignment_type, assignment_value, assignment_address, assignment_answer_a, assignment_answer_a_clean, assignment_answer_b, assignment_answer_b_clean, assignment_answer_c, assignment_answer_c_clean, assignment_answer_d, assignment_answer_d_clean, assignment_correct_alternative, assignment_radius_metric, assignment_radius_imperial, assignment_radius_user_measurment, assignment_hint_a_value, assignment_hint_a_price, assignment_hint_b_value, assignment_hint_b_price, assignment_hint_c_value, assignment_hint_c_price, assignment_points, assignment_text_when_correct_answer, assignment_time_to_solve_seconds, assignment_time_to_solve_saying, assignment_created_by_user_id, assignment_created_by_ip, assignment_created_datetime, assignment_updated_by_user_id, assignment_updated_by_ip, assignment_updated_datetime FROM $t_rebus_games_assignments WHERE assignment_id=$assignment_id_mysql AND assignment_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_assignment_id, $get_current_assignment_game_id, $get_current_assignment_number, $get_current_assignment_type, $get_current_assignment_value, $get_current_assignment_address, $get_current_assignment_answer_a, $get_current_assignment_answer_a_clean, $get_current_assignment_answer_b, $get_current_assignment_answer_b_clean, $get_current_assignment_answer_c, $get_current_assignment_answer_c_clean, $get_current_assignment_answer_d, $get_current_assignment_answer_d_clean, $get_current_assignment_correct_alternative, $get_current_assignment_radius_metric, $get_current_assignment_radius_imperial, $get_current_assignment_radius_user_measurment, $get_current_assignment_hint_a_value, $get_current_assignment_hint_a_price, $get_current_assignment_hint_b_value, $get_current_assignment_hint_b_price, $get_current_assignment_hint_c_value, $get_current_assignment_hint_c_price, $get_current_assignment_points, $get_current_assignment_text_when_correct_answer, $get_current_assignment_time_to_solve_seconds, $get_current_assignment_time_to_solve_saying, $get_current_assignment_created_by_user_id, $get_current_assignment_created_by_ip, $get_current_assignment_created_datetime, $get_current_assignment_updated_by_user_id, $get_current_assignment_updated_by_ip, $get_current_assignment_updated_datetime) = $row;
		if($get_current_assignment_id == ""){
			echo"Assignment not found";
			exit;
		}
		
		$switch_assigment_number = $get_current_assignment_number-1;
		if($switch_assigment_number == "-1"){
			// No can do
			$url = "edit_game_assignments.php?game_id=$get_current_game_id&l=$l&ft=success&fm=cannot_move_it_futher_up";
			header("Location: $url");
			exit;
		}

		// Find the switch
		$query = "SELECT assignment_id, assignment_number FROM $t_rebus_games_assignments WHERE assignment_number=$switch_assigment_number AND assignment_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switched_assignment_id, $get_switched_assignment_number) = $row;
		if($get_switched_assignment_id == ""){
			// No can do
			$url = "edit_game_assignments.php?game_id=$get_current_game_id&l=$l&ft=success&fm=could_not_find_assignment_to_switch_with";
			header("Location: $url");
			exit;
		}

		// Switch
		mysqli_query($link, "UPDATE $t_rebus_games_assignments  SET assignment_number=$get_switched_assignment_number WHERE assignment_id=$get_current_assignment_id") or die(mysqli_error($link));
		mysqli_query($link, "UPDATE $t_rebus_games_assignments  SET assignment_number=$get_current_assignment_number WHERE assignment_id=$get_switched_assignment_id") or die(mysqli_error($link));

		$url = "edit_game_assignments.php?game_id=$get_current_game_id&l=$l";
		header("Location: $url");
		exit;
	} // move assignment up
	elseif($action == "move_assignment_down"){
		if(isset($_GET['assignment_id'])) {
			$assignment_id = $_GET['assignment_id'];
			$assignment_id = output_html($assignment_id);
			if(!(is_numeric($assignment_id))){
				echo"assignment id not numeric";
				die;
			}
		}
		else{
			echo"Missing assignment id";
			die;
		}

		// Get assignment
		$assignment_id_mysql = quote_smart($link, $assignment_id);
		$query = "SELECT assignment_id, assignment_game_id, assignment_number, assignment_type, assignment_value, assignment_address, assignment_answer_a, assignment_answer_a_clean, assignment_answer_b, assignment_answer_b_clean, assignment_answer_c, assignment_answer_c_clean, assignment_answer_d, assignment_answer_d_clean, assignment_correct_alternative, assignment_radius_metric, assignment_radius_imperial, assignment_radius_user_measurment, assignment_hint_a_value, assignment_hint_a_price, assignment_hint_b_value, assignment_hint_b_price, assignment_hint_c_value, assignment_hint_c_price, assignment_points, assignment_text_when_correct_answer, assignment_time_to_solve_seconds, assignment_time_to_solve_saying, assignment_created_by_user_id, assignment_created_by_ip, assignment_created_datetime, assignment_updated_by_user_id, assignment_updated_by_ip, assignment_updated_datetime FROM $t_rebus_games_assignments WHERE assignment_id=$assignment_id_mysql AND assignment_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_assignment_id, $get_current_assignment_game_id, $get_current_assignment_number, $get_current_assignment_type, $get_current_assignment_value, $get_current_assignment_address, $get_current_assignment_answer_a, $get_current_assignment_answer_a_clean, $get_current_assignment_answer_b, $get_current_assignment_answer_b_clean, $get_current_assignment_answer_c, $get_current_assignment_answer_c_clean, $get_current_assignment_answer_d, $get_current_assignment_answer_d_clean, $get_current_assignment_correct_alternative, $get_current_assignment_radius_metric, $get_current_assignment_radius_imperial, $get_current_assignment_radius_user_measurment, $get_current_assignment_hint_a_value, $get_current_assignment_hint_a_price, $get_current_assignment_hint_b_value, $get_current_assignment_hint_b_price, $get_current_assignment_hint_c_value, $get_current_assignment_hint_c_price, $get_current_assignment_points, $get_current_assignment_text_when_correct_answer, $get_current_assignment_time_to_solve_seconds, $get_current_assignment_time_to_solve_saying, $get_current_assignment_created_by_user_id, $get_current_assignment_created_by_ip, $get_current_assignment_created_datetime, $get_current_assignment_updated_by_user_id, $get_current_assignment_updated_by_ip, $get_current_assignment_updated_datetime) = $row;
		if($get_current_assignment_id == ""){
			echo"Assignment not found";
			exit;
		}
		
		$switch_assigment_number = $get_current_assignment_number+1;

		// Find the switch
		$query = "SELECT assignment_id, assignment_number FROM $t_rebus_games_assignments WHERE assignment_number=$switch_assigment_number AND assignment_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switched_assignment_id, $get_switched_assignment_number) = $row;
		if($get_switched_assignment_id == ""){
			// No can do
			$url = "edit_game_assignments.php?game_id=$get_current_game_id&l=$l&ft=success&fm=could_not_find_assignment_to_switch_with";
			header("Location: $url");
			exit;
		}

		// Switch
		mysqli_query($link, "UPDATE $t_rebus_games_assignments  SET assignment_number=$get_switched_assignment_number WHERE assignment_id=$get_current_assignment_id") or die(mysqli_error($link));
		mysqli_query($link, "UPDATE $t_rebus_games_assignments  SET assignment_number=$get_current_assignment_number WHERE assignment_id=$get_switched_assignment_id") or die(mysqli_error($link));

		$url = "edit_game_assignments.php?game_id=$get_current_game_id&l=$l";
		header("Location: $url");
		exit;
	} // move assignment down
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/my_games.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>