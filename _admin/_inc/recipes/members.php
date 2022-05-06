<?php
/**
*
* File: _admin/_inc/stram/members.php
* Version 00.28 20.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_diet_food			= $mysqlPrefixSav . "diet_food";
$t_diet_goal			= $mysqlPrefixSav . "diet_goal";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['language'])){
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "en";
}


if($action == ""){
	echo"
	<h1>Members</h1>



	<!-- Members -->
		<table style=\"width: 100%;\">
		 <tr>
		  <td class=\"outline\">
			<table style=\"width: 100%; border-spacing: 1px;border-collapse: separate;\">
			 <tr>
			  <td class=\"headcell\">
				<span><b>ID</b></span>
			  </td>
			  <td class=\"headcell\">
				<span><b>E-mail</b></span>
			  </td>
			  <td class=\"headcell\">
				<span><b>Alias</b></span>
			  </td>
			  <td class=\"headcell\">
				<span><b>Actions</b></span>
			  </td>
			 </tr>";

			// Get all users
			$query = "SELECT user_id, user_email, user_name FROM $t_users ORDER BY user_last_online DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_user_id, $get_user_email, $get_user_name) = $row;
				
				// Style
				if(isset($style) && $style == "bodycell"){
					$style = "subcell";
				}
				else{
					$style = "bodycell";
				}
				
		
				echo"
				 <tr>
				  <td class=\"$style\">
					<span>$get_user_id</span>
				  </td>
				  <td class=\"$style\">
					<span>$get_user_email</span>
				  </td>
				  <td class=\"$style\">
					<span>$get_user_name</span>
				  </td>
				  <td class=\"$style\">
					<span><a href=\"?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id\">View</a></span>
				  </td>
				 </tr>";

			}

			echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Members -->
	";
}
elseif($action == "view_member" && isset($_GET['user_id'])){
	
	// Get variables
	if(isset($_GET['mode'])){
		$mode = $_GET['mode'];
		$mode = strip_tags(stripslashes($mode));
	}
	else{
		$mode = "";
	}

	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
	$user_id_mysql = quote_smart($link, $user_id);

	// Select user
	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_language, user_gender, user_height, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_notes FROM $t_users WHERE user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_password_replacement, $get_user_password_date, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_height, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized, $get_user_notes) = $row;

	if($get_user_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>User not found.</p>

		<p><a href=\"index.php?open=stram&amp;page=members\">Members</a></p>
		";
	}
	else{
		echo"
		<h1>$get_user_name</h1>

		<p>
		<a href=\"index.php?open=stram&amp;page=members\">Members</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id\""; if($mode == ""){ echo" style=\"font-weight:bold;\""; } echo">View</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id&amp;mode=categories\""; if($mode == "categories"){ echo" style=\"font-weight:bold;\""; } echo">Categories</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id&amp;mode=food\""; if($mode == "food"){ echo" style=\"font-weight:bold;\""; } echo">Food</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id&amp;mode=food_diary\""; if($mode == "food_diary"){ echo" style=\"font-weight:bold;\""; } echo">Food diary</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id&amp;mode=food_diary_cal_eaten\""; if($mode == "food_diary_cal_eaten"){ echo" style=\"font-weight:bold;\""; } echo">Food diary cal eaten</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id&amp;mode=food_diary_sum\""; if($mode == "food_diary_sum"){ echo" style=\"font-weight:bold;\""; } echo">Food diary sum</a>
		&middot;
		<a href=\"index.php?open=stram&amp;page=members&amp;action=view_member&amp;user_id=$get_user_id&amp;mode=goal\""; if($mode == "goal"){ echo" style=\"font-weight:bold;\""; } echo">Goal</a>
		</p>
		";

		if($mode == ""){
			echo"
			<h2>User</h2>
			<table>
			 <tr>
			  <td>
				<span>ID:</span>
			  </td>
			  <td>
				<span>$get_user_id</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>E-mail:</span>
			  </td>
			  <td>
				<span>$get_user_email</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Username:</span>
			  </td>
			  <td>
				<span>$get_user_name</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Alias:</span>
			  </td>
			  <td>
				<span>$get_user_alias</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Language:</span>
			  </td>
			  <td>
				<span>$get_user_language</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Gender:</span>
			  </td>
			  <td>
				<span>$get_user_gender</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Height:</span>
			  </td>
			  <td>
				<span>$get_user_height</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Measurement:</span>
			  </td>
			  <td>
				<span>$get_user_measurement</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>DOB:</span>
			  </td>
			  <td>
				<span>$get_user_dob</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Date format:</span>
			  </td>
			  <td>
				<span>$get_user_date_format</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Registered:</span>
			  </td>
			  <td>
				<span>$get_user_registered</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Last online:</span>
			  </td>
			  <td>
				<span>$get_user_last_online</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Rank:</span>
			  </td>
			  <td>
				<span>$get_user_rank</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Points:</span>
			  </td>
			  <td>
				<span>$get_user_points</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Likes:</span>
			  </td>
			  <td>
				<span>$get_user_likes</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Dislikes:</span>
			  </td>
			  <td>
				<span>$get_user_dislikes</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Status:</span>
			  </td>
			  <td>
				<span>$get_user_status</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Login tries:</span>
			  </td>
			  <td>
				<span>$get_user_login_tries</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Last IP:</span>
			  </td>
			  <td>
				<span>$get_user_last_ip</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Synchronized:</span>
			  </td>
			  <td>
				<span>$get_user_synchronized</span>
			  </td>
			 </tr>
			 <tr>
			  <td>
				<span>Notes:</span>
			  </td>
			  <td>
				<span>$get_user_notes</span>
			  </td>
			 </tr>
			</table>
			";
		} // mode == ""
		elseif($mode == "food"){
			echo"
			<h2>Food</h2>

			<!-- Food -->

				<table style=\"width: 100%;\">
				 <tr>
				  <td class=\"outline\">
					<table style=\"width: 100%; border-spacing: 1px;border-collapse: separate;\">
					 <tr>
					  <td class=\"headcell\">
						<span><b>ID</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Name</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Manufacturer name</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Store</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Description</b></span>
					  </td>
					 </tr>";

					// Get all food for user
					$query = "SELECT _id, food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_store, food_description, food_serving_size_gram, food_serving_size_gram_mesurment, food_serving_size_pcs, food_serving_size_pcs_mesurment, food_energy, food_proteins, food_carbohydrates, food_fat, food_energy_calculated, food_proteins_calculated, food_carbohydrates_calculated, food_fat_calculated, food_barcode, food_category_id, food_image_path, food_thumb, food_image_a, food_image_b, food_image_c, food_last_used, food_language, food_synchronized, food_notes FROM $t_diet_food WHERE food_user_id='$get_user_id'";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get__id, $get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_store, $get_food_description, $get_food_serving_size_gram, $get_food_serving_size_gram_mesurment, $get_food_serving_size_pcs, $get_food_serving_size_pcs_mesurment, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_fat, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_carbohydrates_calculated, $get_food_fat_calculated, $get_food_barcode, $get_food_category_id, $get_food_image_path, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_notes) = $row;
				
						// Style
						if(isset($style) && $style == "bodycell"){
							$style = "subcell";
						}
						else{
							$style = "bodycell";
						}
				
		
						echo"
						 <tr>
						  <td class=\"$style\">
							<span>$get_food_id</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_food_name</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_food_manufacturer_name</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_food_store</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_food_description</span>
						  </td>
						 </tr>";

			}

					echo"
					</table>
				  </td>
				 </tr>
				</table>
			<!-- //Food -->

			";
		}
		elseif($mode == "goal"){
			echo"
			<h2>Goal</h2>

			<!-- Goal -->

				<table style=\"width: 100%;\">
				 <tr>
				  <td class=\"outline\">
					<table style=\"width: 100%; border-spacing: 1px;border-collapse: separate;\">
					 <tr>
					  <td class=\"headcell\">
						<span><b>ID</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Current weight</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Target weight</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>I want to</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Weekly goal</b></span>
					  </td>
					  <td class=\"headcell\">
						<span><b>Date</b></span>
					  </td>
					 </tr>";

					// Get all goal for user
					$query = "SELECT goal_id, goal_user_id, goal_current_weight, goal_target_weight, goal_i_want_to, goal_weekly_goal, goal_date, goal_activity_level, goal_energy_bmr, goal_proteins_bmr, goal_carbs_bmr, goal_fat_bmr, goal_energy_diet, goal_proteins_diet, goal_carbs_diet, goal_fat_diet, goal_energy_with_activity, goal_proteins_with_activity, goal_carbs_with_activity, goal_fat_with_activity, goal_energy_with_activity_and_diet, goal_proteins_with_activity_and_diet, goal_carbs_with_activity_and_diet, goal_fat_with_activity_and_diet, goal_synchronized, goal_notes FROM $t_diet_goal WHERE goal_user_id='$get_user_id'";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_goal_id, $get_goal_user_id, $get_goal_current_weight, $get_goal_target_weight, $get_goal_i_want_to, $get_goal_weekly_goal, $get_goal_date, $get_goal_activity_level, $get_goal_energy_bmr, $get_goal_proteins_bmr, $get_goal_carbs_bmr, $get_goal_fat_bmr, $get_goal_energy_diet, $get_goal_proteins_diet, $get_goal_carbs_diet, $get_goal_fat_diet, $get_goal_energy_with_activity, $get_goal_proteins_with_activity, $get_goal_carbs_with_activity, $get_goal_fat_with_activity, $get_goal_energy_with_activity_and_diet, $get_goal_proteins_with_activity_and_diet, $get_goal_carbs_with_activity_and_diet, $get_goal_fat_with_activity_and_diet, $get_goal_synchronized, $get_goal_notes) = $row;
				
						// Style
						if(isset($style) && $style == "bodycell"){
							$style = "subcell";
						}
						else{
							$style = "bodycell";
						}
				
		
						echo"
						 <tr>
						  <td class=\"$style\">
							<span>$get_goal_id</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_goal_current_weight</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_goal_target_weight</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_goal_i_want_to</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_goal_weekly_goal</span>
						  </td>
						  <td class=\"$style\">
							<span>$get_goal_date</span>
						  </td>
						 </tr>";

			}

				echo"
				</table>
			  </td>
			 </tr>
			</table>
			<!-- //Goal -->

			";
		}
	} // user found
} // view_user
?>