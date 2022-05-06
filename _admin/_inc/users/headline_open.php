<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users_profile_headlines		= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations	= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields			= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations	= $mysqlPrefixSav . "users_profile_fields_translations";


/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Config ---------------------------------------------------------------------------- */

/*- Varialbes  ---------------------------------------------------- */
$tabindex = 0;

if (isset($_GET['headline_id'])) {
	$headline_id = $_GET['headline_id'];
	$headline_id = stripslashes(strip_tags($headline_id));
	if(!(is_numeric($headline_id))){
		echo"Headline id not numeric";
		die;
	}
}
else{
	echo"Missing headline id";
	die;
}
$headline_id_mysql = quote_smart($link, $headline_id);

// Get headline
$query = "SELECT headline_id, headline_title, headline_title_clean, headline_weight, headline_show_on_profile FROM $t_users_profile_headlines WHERE headline_id=$headline_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_headline_id, $get_current_headline_title, $get_current_headline_title_clean, $get_current_headline_weight, $get_current_headline_show_on_profile) = $row;

if($get_current_headline_id == ""){
	echo"
	<h1>Server error 404</h1>
	<p>Headline not found.</p>
	<p><a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a></p>
	";
}
else{
	echo"
	<h1>$get_current_headline_title</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Users</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=headlines&amp;editor_language=$editor_language&amp;l=$l\">Headlines</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=headline_open&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_headline_title</a>
		</p>
	<!-- //Where am I? -->

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->
	<!-- Fields -->
		<p>
		<a href=\"index.php?open=$open&amp;page=field_new&amp;headline_id=$get_current_headline_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New field</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Type</span>
		   </th>
		   <th scope=\"col\">
			<span>Show on profile</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		<tbody>


	";
		$y = 1;
		$query = "SELECT field_id, field_title, field_weight, field_type, field_show_on_profile FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id ORDER BY field_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_field_id, $get_field_title, $get_field_weight, $get_field_type, $get_field_show_on_profile) = $row;

			echo"
			 <tr>
			  <td>
				<span><a href=\"index.php?open=$open&amp;page=field_edit&amp;headline_id=$get_current_headline_id&amp;field_id=$get_field_id&amp;editor_language=$editor_language&amp;l=$l\">$get_field_title</a></span>
			  </td>
			  <td>
				<span>"; echo ucfirst($get_field_type); echo"</span>
			  </td>
			  <td>
				<span>";
				if($get_field_show_on_profile == "1"){
					echo"Yes";
				}
				else{
					echo"No";
				}
				echo"</span>
			  </td>
			  <td>
				<span>
				<a href=\"index.php?open=$open&amp;page=field_move_up&amp;headline_id=$get_current_headline_id&amp;field_id=$get_field_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_upward_round_black_18x18.png\" alt=\"arrow_upward_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=field_move_down&amp;headline_id=$get_current_headline_id&amp;field_id=$get_field_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_downward_round_black_18x18.png\" alt=\"arrow_downward_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=field_edit&amp;headline_id=$get_current_headline_id&amp;field_id=$get_field_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/edit_round_black_18x18.png\" alt=\"edit_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=field_delete&amp;headline_id=$get_current_headline_id&amp;field_id=$get_field_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/delete_round_black_18x18.png\" alt=\"delete_round_black_18x18.png\" /></a>
				</span>
			  </td>
			 </tr>
			";

			// Weight
			if($y != "$get_field_weight" OR $get_field_weight == ""){
				mysqli_query($link, "UPDATE $t_users_profile_fields SET field_weight=$y WHERE field_id=$get_field_id") or die(mysqli_error($link));
			}
			$y++;

		}
		echo"
	
		 </tbody>
		</table>
	<!-- //Fields -->
	";
} // headline found
?>