<?php
/**
*
* File: _admin/_inc/pages/default.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ----------------------------------------------------------------------- */
function get_iso_weeks_in_year($year) {
    $date = new DateTime;
    $date->setISODate($year, 53);
    return ($date->format("W") === "53" ? 53 : 52);
}

/*- Variables ----------------------------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['moderator_of_the_week_id'])) {
	$moderator_of_the_week_id = $_GET['moderator_of_the_week_id'];
	$moderator_of_the_week_id = strip_tags(stripslashes($moderator_of_the_week_id));
}
else{
	$moderator_of_the_week_id = "";
}
$moderator_of_the_week_id_mysql = quote_smart($link, $moderator_of_the_week_id);

$year = date("Y");
$week = date("W");

if($action == ""){
	echo"
	<h1>$l_moderator_of_the_week</h1>

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=dashboard&amp;page=moderator_of_the_week&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Truncate</a>
		</p>
	<!-- //Actions -->

	<!-- List all weeks -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_week</span>
		   </th>
		   <th scope=\"col\">
			<span>Date</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_moderator</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_comment</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	
		$last_week_number = "";

		$query = "SELECT moderator_of_the_week_id, moderator_week, moderator_year, moderator_start_date_saying, moderator_user_id, moderator_user_email, moderator_user_name, moderator_user_alias, moderator_comment FROM $t_users_moderator_of_the_week ORDER BY moderator_of_the_week_id ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_moderator_of_the_week_id, $get_moderator_week, $get_moderator_year, $get_moderator_start_date_saying, $get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name, $get_moderator_user_alias, $get_moderator_comment) = $row;

			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}


			if($moderator_of_the_week_id == "$get_moderator_of_the_week_id"){
				$style = "important";
			}

			// Delete old
			if($get_moderator_year < "$year"){
				// Last Year
				$style = "danger";
				$result_delete = mysqli_query($link, "DELETE FROM $t_users_moderator_of_the_week WHERE moderator_of_the_week_id=$get_moderator_of_the_week_id") or die(mysqli_error($link));
			}
			if($get_moderator_week < "$week"){
				// Last week
				if($get_moderator_year == "$year" OR $get_moderator_year < "$year"){
					// Last Year
					$style = "danger";
					$result_delete = mysqli_query($link, "DELETE FROM $t_users_moderator_of_the_week WHERE moderator_of_the_week_id=$get_moderator_of_the_week_id") or die(mysqli_error($link));
				}
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<a id=\"moderator_of_the_week_id$get_moderator_of_the_week_id\"></a>
				<span>$get_moderator_week/$get_moderator_year</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_moderator_start_date_saying</span>
			  </td>
			  <td class=\"$style\">
				<span><a href=\"index.php?open=users&amp;page=users_edit_user&amp;user_id=$get_moderator_user_id&amp;editor_language=$editor_language\">$get_moderator_user_alias</a></span>
			  </td>
			  <td class=\"$style\">
				<span>$get_moderator_comment</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_moderator_of_the_week&amp;moderator_of_the_week_id=$get_moderator_of_the_week_id&amp;editor_language=$editor_language\">$l_edit</a>
				</span>
			 </td>
			</tr>
			";
			$last_week_number = "$get_moderator_week";
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all weeks -->
	";

	// Check that all weeks are present
	if($last_week_number == ""){
		echo"
		<div class=\"info\"><p>Setting this year</p></div>
		<meta http-equiv=refresh content=\"0; url=index.php?open=dashboard&amp;page=moderator_of_the_week&amp;editor_language=$editor_language\">
		";

		// Insert this week number to last week number of year
		$weeks_in_year = get_iso_weeks_in_year($year);
		for($x=$week;$x<$weeks_in_year+1;$x++){
			$year = "$year";
			$week = "$x";
			include("_functions/create_moderator_of_the_week.php");
		}
	}
	
	// Check that next year is present
	$next_year = $year+1;
	$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week='01' AND moderator_year=$next_year";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
	if($get_moderator_user_id == ""){
		echo"
		<div class=\"info\"><p>Setting up next year</p></div>
		<meta http-equiv=refresh content=\"1; url=index.php?open=dashboard&amp;page=moderator_of_the_week&amp;editor_language=$editor_language\">
		";
		
		$weeks_in_year = get_iso_weeks_in_year($next_year);
		for($x=1;$x<$weeks_in_year+1;$x++){
			$year = "$next_year";
			$week = "$x";
			include("_functions/create_moderator_of_the_week.php");
		}
	}

	echo"
	<p>&nbsp;</p>
	";
}
elseif($action == "edit_moderator_of_the_week"){
	
	$query = "SELECT moderator_of_the_week_id, moderator_week, moderator_year, moderator_user_id, moderator_user_email, moderator_user_name, moderator_user_alias, moderator_user_first_name, moderator_user_last_name, moderator_user_language, moderator_comment FROM $t_users_moderator_of_the_week WHERE moderator_of_the_week_id=$moderator_of_the_week_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_moderator_of_the_week_id, $get_moderator_week, $get_moderator_year, $get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name, $get_moderator_user_alias, $get_moderator_user_first_name, $get_moderator_user_last_name, $get_moderator_user_language, $get_moderator_comment) = $row;

	if($get_moderator_of_the_week_id == ""){
		echo"
		<h1>x</h1>

		<p>x</p>
		";
	}
	else{
		if($process == "1"){
			$inp_user_id = $_POST['inp_user_id'];
			$inp_user_id = output_html($inp_user_id);
			$inp_user_id_mysql = quote_smart($link, $inp_user_id);

			$inp_comment = $_POST['inp_comment'];
			$inp_comment = output_html($inp_comment);
			$inp_comment_mysql = quote_smart($link, $inp_comment);

			// Fetch user
			$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_id=$inp_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;



			// Fetch profile
			$query_p = "SELECT profile_id, profile_first_name, profile_last_name FROM $t_users_profile WHERE profile_user_id=$get_mod_user_id";
			$result_p = mysqli_query($link, $query_p);
			$row_p = mysqli_fetch_row($result_p);
			list($get_mod_profile_id, $get_mod_profile_first_name, $get_mod_profile_last_name) = $row_p;

			$inp_mod_email_mysql = quote_smart($link, $get_mod_user_email);
			$inp_mod_user_name_mysql = quote_smart($link, $get_mod_user_name);
			$inp_mod_user_alias = quote_smart($link, $get_mod_user_alias);
			$inp_mod_user_first_name = quote_smart($link, $get_mod_profile_first_name);
			$inp_mod_user_last_name = quote_smart($link, $get_mod_profile_last_name);
			$inp_mod_user_language = quote_smart($link, $get_user_language);

			// Update
			mysqli_query($link, "UPDATE $t_users_moderator_of_the_week SET 
			moderator_user_id=$inp_user_id_mysql, moderator_user_email=$inp_mod_email_mysql, moderator_user_name=$inp_mod_user_name_mysql,
			moderator_user_alias=$inp_mod_user_alias, moderator_user_first_name=$inp_mod_user_first_name, moderator_user_last_name=$inp_mod_user_last_name, 
			moderator_user_language=$inp_mod_user_language, moderator_comment=$inp_comment_mysql 
			WHERE moderator_of_the_week_id=$get_moderator_of_the_week_id")
			or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&moderator_of_the_week_id=$moderator_of_the_week_id&editor_language=$editor_language&ft=success&fm=changes_saved#moderator_of_the_week_id$moderator_of_the_week_id";
			header("Location: $url");
			exit;
			
		}

		echo"
		<h1>$get_moderator_week/$get_moderator_year</h1>


		

		<!-- Form -->

			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_user_id\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_moderator_of_the_week&amp;moderator_of_the_week_id=$moderator_of_the_week_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
				<p>$l_moderator:<br />
				<select name=\"inp_user_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;

					echo"				";
					echo"<option value=\"$get_mod_user_id\""; if($get_moderator_user_id == "$get_mod_user_id"){ echo" selected=\"selected\""; } echo">$get_mod_user_alias</option>\n";
				}
				echo"
				</select>	
				</p>

				<p>$l_comment:<br />
				<input type=\"text\" name=\"inp_comment\" value=\"$get_moderator_comment\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>


				<p><input type=\"submit\" value=\"$l_save\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		
			</form>
		<!-- //Form -->
		";
	}
}
elseif($action == "truncate"){
	if($process == "1"){
			mysqli_query($link, "TRUNCATE $t_users_moderator_of_the_week")
			or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&moderator_of_the_week_id=$moderator_of_the_week_id&editor_language=$editor_language&ft=success&fm=table_truncated";
			header("Location: $url");
			exit;
	}
	echo"
	<h1>Truncate moderator of the week</h1>


	<p>
	Are you sure you want to to truncate moderator of the week? This will delete all entries.
	</p>

	<p>
	<a href=\"index.php?open=dashboard&amp;page=moderator_of_the_week&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
	</p>
	";
} // truncate
?>