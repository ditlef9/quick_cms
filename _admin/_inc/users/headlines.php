<?php
/**
*
* File: _admin/_inc/users/headlines.php
* Version 1.0
* Date: 13:22 04.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables -------------------------------------------------------------------------- */





/*- MySQL Tables -------------------------------------------------- */
$t_users_profile_headlines		= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations	= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields			= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations	= $mysqlPrefixSav . "users_profile_fields_translations";



if($action == ""){
	echo"
	<h1>Headlines</h1>

	<p>
	Here you can control what information you want to get from the users and how it should be stored into the database.
	</p>

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "user_deleted"){
			$fm = "$l_user_deleted";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->


	<!-- Headlines list -->
		<p>
		<a href=\"index.php?open=$open&amp;page=headline_new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New headline</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>User can view headline</span>
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
		$query = "SELECT headline_id, headline_title, headline_title_clean, headline_weight,headline_user_can_view_headline, headline_show_on_profile FROM $t_users_profile_headlines ORDER BY headline_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_headline_id, $get_headline_title, $get_headline_title_clean, $get_headline_weight, $get_headline_user_can_view_headline, $get_headline_show_on_profile) = $row;

			echo"
			 <tr>
			  <td>
				<span><a href=\"index.php?open=$open&amp;page=headline_open&amp;headline_id=$get_headline_id&amp;editor_language=$editor_language&amp;l=$l\">$get_headline_title</a></span>
			  </td>
			  <td>
				<span>";
				if($get_headline_user_can_view_headline == "1"){
					echo"Yes";
				}
				else{
					echo"No";
				}
				echo"</span>
			  </td>
			  <td>
				<span>";
				if($get_headline_show_on_profile == "1"){
					echo"Yes";
				}
				else{
					echo"No";
				}
				echo"</span>
			  </td>
			  <td>
				<span>
				<a href=\"index.php?open=$open&amp;page=headline_open&amp;headline_id=$get_headline_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/folder_open_round_black_18x18.png\" alt=\"folder_open_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=headline_move_up&amp;headline_id=$get_headline_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_upward_round_black_18x18.png\" alt=\"arrow_upward_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=headline_move_down&amp;headline_id=$get_headline_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_downward_round_black_18x18.png\" alt=\"arrow_downward_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=headline_edit&amp;headline_id=$get_headline_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/edit_round_black_18x18.png\" alt=\"edit_round_black_18x18.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=headline_delete&amp;headline_id=$get_headline_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/delete_round_black_18x18.png\" alt=\"delete_round_black_18x18.png\" /></a>
				</span>
			  </td>
			 </tr>
			";

			// Weight
			if($y != "$get_headline_weight" OR $get_headline_weight == ""){
				mysqli_query($link, "UPDATE $t_users_profile_headlines SET headline_weight=$y WHERE headline_id=$get_headline_id") or die(mysqli_error($link));
			}
			$y++;

		}
		echo"
	
		 </tbody>
		</table>
	<!-- //Headlines list -->
	";
}
?>
