<?php
/**
*
* File: _admin/_inc/pages/footer.php
* Version 2
* Date 19:05 11.10.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Access check --------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables -------------------------------------------------------------------------- */
$t_webdesign_footer_link_groups = $mysqlPrefixSav . "webdesign_footer_link_groups";
$t_webdesign_footer_link_links  = $mysqlPrefixSav . "webdesign_footer_link_links";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = strip_tags(stripslashes($group_id));
	if(!(is_numeric($group_id))){
		echo"Group not numeric";
		die;
	}
}
else{
	$group_id = "";
}
if(isset($_GET['link_id'])) {
	$link_id = $_GET['link_id'];
	$link_id = strip_tags(stripslashes($link_id));
	if(!(is_numeric($link_id))){
		echo"Link not numeric";
		die;
	}
}
else{
	$link_id = "";
}
$tabindex = 0;

if($action == ""){
	echo"
	<h2>Footer</h2>

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		elseif($fm == "navgation_item_deleted"){
			$fm = "$l_navgation_item_deleted";
		}
		
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

		
	<!-- Menu: Editor language, Actions -->
		<script>
		\$(function(){
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

			<p>
			<select id=\"inp_l\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

	
				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>
	<!-- //Menu -->
		
	<!-- Left and right -->

		<table>
		 <tr>
		  <td style=\"width: 150px;vertical-align: top;padding: 0px 20px 0px 0px;\">
			<!-- Left -->


				<table class=\"hor-zebra\">
				 <tbody>
				  <tr>
				   <td>";
				// Groups
				$editor_language_mysql = quote_smart($link, $editor_language);
				$query = "SELECT group_id, group_title FROM $t_webdesign_footer_link_groups WHERE group_language=$editor_language_mysql ORDER BY group_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_group_id, $get_group_title) = $row;
					echo"
					<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_group_title</a><br /></span>
					";
				}
				echo"
				   </td>
				  </tr>
				 </tbody>
				</table>

				<table>
				 <tr>
				  <td style=\"padding-right: 4px;\">
					<p>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/create_new_folder_outline_black_18x18.png\" alt=\"create_new_folder_axb.png\" /></a>
					</p>
				  </td>
				  <td>
					<p>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\">New footer group</a>
					</p>
				  </td>
				 </tr>
				</table>
			<!-- //Left -->
		  </td>
		  <td style=\"vertical-align: top;\">
			<!-- Right -->
			<!-- //Right -->
		  </td>
		 </tr>
		</table>
	
	<!-- //Left and right -->
	";
}
elseif($action == "new_group"){
	if($process == "1"){

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);
		$editor_language = $inp_language;

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		if($inp_title == ""){
			header("Location: index.php?open=$open&page=$page&action=$action&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
			exit;
		}

		$inp_show_title = $_POST['inp_show_title'];
		$inp_show_title = output_html($inp_show_title);
		$inp_show_title_mysql = quote_smart($link, $inp_show_title);

		$inp_type = $_POST['inp_type'];
		$inp_type = output_html($inp_type);
		$inp_type_mysql = quote_smart($link, $inp_type);

		$inp_date = date("Y-m-d");
		$inp_date_mysql = quote_smart($link, $inp_date);

		$inp_created_by_user_id = $_SESSION['admin_user_id'];
		$inp_created_by_user_id = output_html($inp_created_by_user_id);
		$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

		// Get weight
		$query = "SELECT count(*) FROM $t_webdesign_footer_link_groups WHERE group_language=$inp_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_count_rows) = $row;

		// Insert
		mysqli_query($link, "INSERT INTO $t_webdesign_footer_link_groups 
		(group_id, group_title, group_type, group_show_title, group_language, group_weight, group_number_of_links, group_created, group_created_by_user_id) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_type_mysql, $inp_show_title_mysql, $inp_language_mysql, '$get_count_rows', 0, $inp_date_mysql, $inp_created_by_user_id_mysql)")
		or die(mysqli_error($link));


		header("Location: index.php?open=$open&page=$page&action=$action&focus=inp_name&ft=success&fm=group_created&editor_language=$editor_language");
		exit;
	}
	echo"
	<h1>New group</h1>

	
	<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- New group form -->
		<form method=\"post\" action=\"?open=$open&amp;page=$page&amp;action=$action&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
				

	
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->


		<p><b>$l_language</b>*<br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />";
		
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

			$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
			echo"	<option value=\"$get_language_active_iso_two\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>Title</b>*<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
	
		<p><b>Show title</b><br />
		<input type=\"radio\" name=\"inp_show_title\" value=\"1\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" checked=\"checked\" /> Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_show_title\" value=\"0\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" /> No
		</p>
	
		<p><b>Type of links</b><br />
		<select name=\"inp_type\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />
			<option value=\"text_links\">Text links</option>
			<option value=\"icons\">Icons</option>
			<option value=\"text_and_icons\">Text and icons</option>
		</select>
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
		</form>
	<!-- //New group form -->


	<!-- Back -->
		<table>
		 <tr>
		  <td style=\"padding-right: 4px;\">
			<p>
			<a href=\"index.php?open=$open&amp;page=$page\"><img src=\"_design/gfx/icons/18x18/go-previous.png\" alt=\"\" /></a>
			</p>
		  </td>
		  <td>
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Go back</a>
			</p>
		  </td>
		 </tr>
		</table>
	<!-- //Back -->
	";
}
elseif($action == "open_group"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;

	if($get_current_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Group item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		echo"
		<h1>$get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Footer</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			</p>
		<!-- //Where am I? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->


		<!-- Left and right -->

			<table>
			 <tr>
			  <td style=\"width: 150px;vertical-align: top;padding: 0px 20px 0px 0px;\">
				<!-- Left -->
					<table class=\"hor-zebra\">
					 <tbody>
					  <tr>
					   <td>";
					// Groups
					$editor_language_mysql = quote_smart($link, $editor_language);
					$x = 0;
					$query = "SELECT group_id, group_title, group_weight FROM $t_webdesign_footer_link_groups WHERE group_language=$editor_language_mysql ORDER BY group_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_group_id, $get_group_title, $get_group_weight) = $row;
						echo"
						<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\""; if($get_group_id == "$get_current_group_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_group_title</a><br /></span>
						";

						// Check weight
						if($get_group_weight != "$x"){
							$result_update = mysqli_query($link, "UPDATE $t_webdesign_footer_link_groups SET group_weight=$x WHERE group_id=$get_group_id") OR die(mysqli_error($link));
						}



						$x++;
					}
					echo"
					   </td>
					  </tr>
					 </tbody>
					</table>

					<table>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<p>
						<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/create_new_folder_outline_black_18x18.png\" alt=\"create_new_folder_outline_black_18x18.png\" /></a>
						</p>
					  </td>
					  <td>
						<p>
						<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\">New footer group</a>
						</p>
					  </td>
					 </tr>
					</table>
				<!-- //Left -->
			  </td>
			  <td style=\"vertical-align: top;\">
				<!-- Right -->
					<!-- Open group menu -->

						<table>
						 <tr>
						  <td style=\"padding: 0px 4px 0px 0px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_link&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/add_link_outline_black_18x18.png\" alt=\"add_link_outline_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td style=\"padding: 0px 10px 0px 0px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_link&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\">New link</a>
							</p>
						  </td>


						  <td style=\"padding: 0px 4px 0px 10px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_group_up&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_upward_outline_black_18x18.png\" alt=\"arrow_upward_outline_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td>
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_group_up&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;process=1\">Move group up</a>
							</p>
						  </td>

						  <td style=\"padding: 0px 4px 0px 10px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_group_down&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_downward_outline_black_18x18.png\" alt=\"arrow_downward_outline_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td>
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_group_down&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;process=1\">Move group down</a>
							</p>
						  </td>


						  <td style=\"padding: 0px 4px 0px 10px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/edit_outline_black_18x18.png\" alt=\"folder_open_outline_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td>
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\">Edit footer group</a>
							</p>
						  </td>

						  <td style=\"padding: 0px 4px 0px 10px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/folder_delete_outline_black_18x18.png\" alt=\"folder_delete_outline_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td>
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\">Delete group</a>
							</p>
						  </td>
						 </tr>
						</table>
					<!-- //Open group menu -->

					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th>
						<span>Title</span>
					   </th>
					   <th>
						<span>URL</span>
					   </th>
					   <th>
						<span>Actions</span>
					   </th>
					  </tr>
					 </thead>
					 <tbody>";
					// Links
					$x=0;
					$query = "SELECT link_id, link_title, link_url, link_weight FROM $t_webdesign_footer_link_links WHERE link_group_id=$get_current_group_id ORDER BY link_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_link_id, $get_link_title, $get_link_url, $get_link_weight) = $row;

						// Check weight
						if($get_link_weight != "$x"){
							$result_update = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET link_weight=$x WHERE link_id=$get_link_id") OR die(mysqli_error($link));
						}


						echo"
						  <tr>
						   <td>
							<span>$get_link_title</span>
						   </td>
						   <td>
							<span><a href=\"$get_link_url\">$get_link_url</a></span>
						   </td>
						   <td>
							<span>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_link_up&amp;link_id=$get_link_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Up</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_link_down&amp;link_id=$get_link_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Down</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_link&amp;link_id=$get_link_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_link&amp;link_id=$get_link_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
							</span>
						   </td>
						  </tr>
						";

						$x++;
					}
					echo"
					   </td>
					  </tr>
					 </tbody>
					</table>
				<!-- //Right -->
			  </td>
			 </tr>
			</table>
	
			<!-- //Left and right -->
		";
	} // found
} // open group
elseif($action == "move_group_up"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;

	if($get_current_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Group item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find the group we need to change with
		$inp_language_mysql = quote_smart($link, $get_current_group_language);
		$switch_weight = $get_current_group_weight-1;
		$query = "SELECT group_id, group_title, group_language, group_weight FROM $t_webdesign_footer_link_groups WHERE group_language=$inp_language_mysql AND group_weight=$switch_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_group_id, $get_switch_group_title, $get_switch_group_language, $get_switch_group_weight) = $row;
		if($get_switch_group_id == ""){
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_group_id&editor_language=$editor_language&l=$l&ft=info&fm=could_not_move_group_up_because_there_where_no_other_group_to_switch_with";
			header("Location: $url");
			exit;
		}
		else{
			// Update current
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_groups SET group_weight=$get_switch_group_weight WHERE group_id=$get_current_group_id") OR die(mysqli_error($link));
			

			// Update switch
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_groups SET group_weight=$get_current_group_weight WHERE group_id=$get_switch_group_id") OR die(mysqli_error($link));

			// Header
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_group_id&editor_language=$editor_language&l=$l&ft=success&fm=group_moved_up";
			header("Location: $url");
			exit;
		}
	}
} // move_group_up
elseif($action == "move_group_down"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;

	if($get_current_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Group item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find the group we need to change with
		$inp_language_mysql = quote_smart($link, $get_current_group_language);
		$switch_weight = $get_current_group_weight+1;
		$query = "SELECT group_id, group_title, group_language, group_weight FROM $t_webdesign_footer_link_groups WHERE group_language=$inp_language_mysql AND group_weight=$switch_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_group_id, $get_switch_group_title, $get_switch_group_language, $get_switch_group_weight) = $row;
		if($get_switch_group_id == ""){
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_group_id&editor_language=$editor_language&l=$l&ft=info&fm=could_not_move_group_down_because_there_where_no_other_group_to_switch_with";
			header("Location: $url");
			exit;
		}
		else{
			// Update current
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_groups SET group_weight=$get_switch_group_weight WHERE group_id=$get_current_group_id") OR die(mysqli_error($link));
			

			// Update switch
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_groups SET group_weight=$get_current_group_weight WHERE group_id=$get_switch_group_id") OR die(mysqli_error($link));

			// Header
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_group_id&editor_language=$editor_language&l=$l&ft=success&fm=group_moved_up";
			header("Location: $url");
			exit;
		}
	}
} // move_group_down
elseif($action == "edit_group"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_title, group_type, group_show_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_type, $get_current_group_show_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;

	if($get_current_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Group not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		if($process == "1"){

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);
			$editor_language = $inp_language;

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			if($inp_title == ""){
				header("Location: index.php?open=$open&page=$page&action=$action&group_id=$groupd_id&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
				exit;
			}

			$inp_show_title = $_POST['inp_show_title'];
			$inp_show_title = output_html($inp_show_title);
			$inp_show_title_mysql = quote_smart($link, $inp_show_title);

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_date = date("Y-m-d");
			$inp_date_mysql = quote_smart($link, $inp_date);

			$inp_my_user_id = $_SESSION['admin_user_id'];
			$inp_my_user_id = output_html($inp_my_user_id);
			$inp_my_user_id_mysql = quote_smart($link, $inp_my_user_id);
			
			// Update
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_groups SET 
							group_title=$inp_title_mysql, 
							group_type=$inp_type_mysql,
							group_show_title=$inp_show_title_mysql, 
							group_language=$inp_language_mysql,
							group_updated=$inp_date_mysql, 
							group_updated_by_user_id=$inp_my_user_id_mysql
							 WHERE group_id=$get_current_group_id") OR die(mysqli_error($link));

			header("Location: index.php?open=$open&page=$page&action=$action&group_id=$get_current_group_id&ft=success&fm=group_created&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Edit $get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Footer</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
			</p>
		<!-- //Where am I? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Edit group form -->
			<form method=\"post\" action=\"?open=$open&amp;page=$page&amp;action=$action&amp;group_id=$get_current_group_id&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
				

	
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			<!-- //Focus -->


			<p><b>$l_language</b>*<br />
			<select name=\"inp_language\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />";
		
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
				echo"	<option value=\"$get_language_active_iso_two\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($get_language_active_iso_two == "$get_current_group_language"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>
	
			<p><b>Title</b>*<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_group_title\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Show title</b><br />
			<input type=\"radio\" name=\"inp_show_title\" value=\"1\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_group_show_title == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_show_title\" value=\"0\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_group_show_title == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p><b>Type of links</b><br />
			<select name=\"inp_type\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />
				<option value=\"text_links\""; if($get_current_group_type == "text_links"){ echo" selected=\"selected\""; } echo">Text links</option>
				<option value=\"icons\""; if($get_current_group_type == "icons"){ echo" selected=\"selected\""; } echo">Icons</option>
				<option value=\"text_and_icons\""; if($get_current_group_type == "text_and_icons"){ echo" selected=\"selected\""; } echo">Text and icons</option>
			</select>
			</p>
	
			<p><input type=\"submit\" value=\"Update\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
			</form>
		<!-- //Edit group form -->


		";
	} // found
} // edit_group
elseif($action == "delete_group"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;

	if($get_current_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Group not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		if($process == "1"){
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_webdesign_footer_link_groups WHERE group_id=$get_current_group_id") OR die(mysqli_error($link));
			$result = mysqli_query($link, "DELETE FROM $t_webdesign_footer_link_links WHERE link_group_id=$get_current_group_id") OR die(mysqli_error($link));

			header("Location: index.php?open=$open&page=$page&ft=success&fm=group_deleted&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Delete $get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Footer</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Delete group form -->
			<p>
			Are you sure you want to delete the form and its links?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;group_id=$get_current_group_id&amp;process=1&amp;editor_language=$editor_language\" class=\"btn_danger\">Confirm</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\" class=\"btn_default\">Cancel</a>
			</p>
		<!-- //Delete group form -->


		";
	} // found
} // delete_group
elseif($action == "new_link"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;

	if($get_current_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Group not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			if($inp_title == ""){
				header("Location: index.php?open=$open&page=$page&action=$action&group_id=$groupd_id&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
				exit;
			}


			$inp_url = $_POST['inp_url'];
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			if(isset($inp_url_parsed['query'])){
				$inp_url_query = $inp_url_parsed['query'];
			}
			else{
				$inp_url_query = "";
			}		
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);



			$datetime = date("Y-m-d H:i:s");

			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);


			// Get weight
			$query = "SELECT count(*) FROM $t_webdesign_footer_link_links WHERE link_group_id=$get_current_group_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;

			// Insert
			$inp_language_mysql = quote_smart($link, $get_current_group_language);
			mysqli_query($link, "INSERT INTO $t_webdesign_footer_link_links 
			(link_id, link_group_id, link_title, link_url, link_internal_or_external, link_language, link_weight, link_created_datetime, link_created_by_user_id) 
			VALUES 
			(NULL, $get_current_group_id, $inp_title_mysql, $inp_url_mysql, '$inp_internal_or_external', $inp_language_mysql, '$get_count_rows', '$datetime', $my_user_id_mysql)")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT link_id FROM $t_webdesign_footer_link_links WHERE link_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_link_id) = $row;


			// Dir
			$upload_path = "../_uploads/webdesign_footer/$get_current_group_language";
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/webdesign_footer"))){
				mkdir("../_uploads/webdesign_footer");
			}
			if(!(is_dir("../_uploads/webdesign_footer/$get_current_group_language"))){
				mkdir("../_uploads/webdesign_footer/$get_current_group_language");
			}

			$icons_size_array = array("24x24", "32x32");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18)
				
				$file_name = basename($_FILES["inp_icon_" . $icons_size_array[$x]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $get_current_link_id . "_" . $icons_size_array[$x] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_" . $icons_size_array[$x]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/webdesign_footer/$get_current_group_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET 
											link_icon_path=$inp_path_mysql, 
											link_icon_$icons_size_array[$x]=$inp_icon_mysql
											WHERE link_id=$get_current_link_id") or die(mysqli_error($link));
						}
						else{
							// Not a image
							unlink("$upload_path/$new_name");
						}
					}
					else{
						// Could not upload
					}
				}
				else{
					// Wrong file type
				}

			}


			header("Location: index.php?open=$open&page=$page&action=$action&group_id=$get_current_group_id&ft=success&fm=link_created&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>New link in $get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Footer</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">New link</a>
			</p>
		<!-- //Where am I? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- New link form -->
			<form method=\"post\" action=\"?open=$open&amp;page=$page&amp;action=$action&amp;group_id=$get_current_group_id&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
				

	
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<p><b>Title</b>*<br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>URL</b>*<br />
			<input type=\"text\" name=\"inp_url\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
	
			<!- icons -->
			";
			$icons_size_array = array("24x24", "32x32");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18_inactive)
				$inp_name = "inp_icon_" . $icons_size_array[$x];

				echo"
				<p><b>Icon $icons_size_array[$x]:</b><br />
				<input type=\"file\" name=\"$inp_name\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>";
			}
			echo"


			<p><input type=\"submit\" value=\"Create link\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
			</form>
		<!-- //New link form -->


		";
	} // found
} // new_link
elseif($action == "move_link_up"){
	$link_id_mysql = quote_smart($link, $link_id);

	$query = "SELECT link_id, link_group_id, link_title, link_url, link_internal_or_external, link_language, link_weight FROM $t_webdesign_footer_link_links WHERE link_id=$link_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_link_id, $get_current_link_group_id, $get_current_link_title, $get_current_link_url, $get_current_link_internal_or_external, $get_current_link_language, $get_current_link_weight) = $row;

	if($get_current_link_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Link not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find switch
		$switch_weight = $get_current_link_weight-1;
		$query = "SELECT link_id, link_group_id, link_title, link_url, link_internal_or_external, link_language, link_weight FROM $t_webdesign_footer_link_links WHERE link_group_id=$get_current_link_group_id AND link_weight=$switch_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_link_id, $get_switch_link_group_id, $get_switch_link_title, $get_switch_link_url, $get_switch_link_internal_or_external, $get_switch_link_language, $get_switch_link_weight) = $row;
		if($get_switch_link_id == ""){
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_link_group_id&editor_language=$editor_language&l=$l&ft=info&fm=could_not_move_up_because_there_where_no_other_link_to_switch_with";
			header("Location: $url");
			exit;
		}
		else{
			// Update current
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET link_weight=$get_switch_link_weight WHERE link_id=$get_current_link_id") OR die(mysqli_error($link));
			

			// Update switch
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET link_weight=$get_current_link_weight WHERE link_id=$get_switch_link_id") OR die(mysqli_error($link));
		
			// Header
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_link_group_id&editor_language=$editor_language&l=$l&ft=success&fm=link_moved_up";
			header("Location: $url");
			exit;
		}
		
	} // found
} // move_link_up
elseif($action == "move_link_down"){
	$link_id_mysql = quote_smart($link, $link_id);

	$query = "SELECT link_id, link_group_id, link_title, link_url, link_internal_or_external, link_language, link_weight FROM $t_webdesign_footer_link_links WHERE link_id=$link_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_link_id, $get_current_link_group_id, $get_current_link_title, $get_current_link_url, $get_current_link_internal_or_external, $get_current_link_language, $get_current_link_weight) = $row;

	if($get_current_link_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Link not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find switch
		$switch_weight = $get_current_link_weight+1;
		$query = "SELECT link_id, link_group_id, link_title, link_url, link_internal_or_external, link_language, link_weight FROM $t_webdesign_footer_link_links WHERE link_group_id=$get_current_link_group_id AND link_weight=$switch_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_link_id, $get_switch_link_group_id, $get_switch_link_title, $get_switch_link_url, $get_switch_link_internal_or_external, $get_switch_link_language, $get_switch_link_weight) = $row;
		if($get_switch_link_id == ""){
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_link_group_id&editor_language=$editor_language&l=$l&ft=info&fm=could_not_move_down_because_there_where_no_other_link_to_switch_with";
			header("Location: $url");
			exit;
		}
		else{
			// Update current
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET link_weight=$get_switch_link_weight WHERE link_id=$get_current_link_id") OR die(mysqli_error($link));
			

			// Update switch
			$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET link_weight=$get_current_link_weight WHERE link_id=$get_switch_link_id") OR die(mysqli_error($link));
		
			// Header
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_link_group_id&editor_language=$editor_language&l=$l&ft=success&fm=link_moved_up";
			header("Location: $url");
			exit;
		}
		
	} // found
} // move_link_down
elseif($action == "edit_link"){
	$link_id_mysql = quote_smart($link, $link_id);

	$query = "SELECT link_id, link_group_id, link_title, link_url, link_icon_path, link_icon_24x24, link_icon_32x32, link_internal_or_external, link_language, link_weight FROM $t_webdesign_footer_link_links WHERE link_id=$link_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_link_id, $get_current_link_group_id, $get_current_link_title, $get_current_link_url, $get_current_link_icon_path, $get_current_link_icon_24x24, $get_current_link_icon_32x32, $get_current_link_internal_or_external, $get_current_link_language, $get_current_link_weight) = $row;

	if($get_current_link_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Link not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find group
		$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$get_current_link_group_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;


		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			if($inp_title == ""){
				header("Location: index.php?open=$open&page=$page&action=$action&group_id=$groupd_id&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
				exit;
			}


			$inp_url = $_POST['inp_url'];
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_url_parsed = parse_url($inp_url);
			$inp_url_scheme = "";
			$inp_url_host = "";
			if(isset($inp_url_parsed['scheme']) && isset($inp_url_parsed['host'])){
				$inp_url_scheme = $inp_url_parsed['scheme'];
				$inp_url_host = $inp_url_parsed['host'];
			}
			$inp_url_path = $inp_url_parsed['path'];
			$inp_url_query = $inp_url_parsed['query'];
			
			if($inp_url_query != ""){
				$inp_url_query = "?" . $inp_url_query;
			}
		
			if($inp_url_scheme == "http" OR $inp_url_scheme == "https"){
				$inp_url_path = "$inp_url_scheme://$inp_url_host$inp_url_path";
				$inp_url_query = "$inp_url_query";
				$inp_internal_or_external = "external";
			}
			else{
				$inp_internal_or_external = "internal";
			}
			$inp_url_path = output_html($inp_url_path);
			$inp_url_path_mysql = quote_smart($link, $inp_url_path);

			$inp_url_query = output_html($inp_url_query);
			$inp_url_query_mysql = quote_smart($link, $inp_url_query);



			$datetime = date("Y-m-d H:i:s");
			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);



			// UPDATE
			mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET
						link_title=$inp_title_mysql, 
						link_url=$inp_url_mysql, 
						link_internal_or_external='$inp_internal_or_external', 
						link_updated_datetime='$datetime',
						link_updated_by_user_id=$my_user_id_mysql
						WHERE link_id=$get_current_link_id") or die(mysqli_error($link));


			// Dir
			$upload_path = "../_uploads/webdesign_footer/$get_current_group_language";
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/webdesign_footer"))){
				mkdir("../_uploads/webdesign_footer");
			}
			if(!(is_dir("../_uploads/webdesign_footer/$get_current_group_language"))){
				mkdir("../_uploads/webdesign_footer/$get_current_group_language");
			}

			$icons_size_array = array("24x24", "32x32");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18)
				
				$file_name = basename($_FILES["inp_icon_" . $icons_size_array[$x]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $get_current_link_id . "_" . $icons_size_array[$x] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_" . $icons_size_array[$x]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/webdesign_footer/$get_current_group_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_webdesign_footer_link_links SET 
											link_icon_path=$inp_path_mysql, 
											link_icon_$icons_size_array[$x]=$inp_icon_mysql
											WHERE link_id=$get_current_link_id") or die(mysqli_error($link));
						}
						else{
							// Not a image
							unlink("$upload_path/$new_name");
						}
					}
					else{
						// Could not upload
					}
				}
				else{
					// Wrong file type
				}

			}

			header("Location: index.php?open=$open&page=$page&action=$action&link_id=$get_current_link_id&ft=success&fm=link_updated&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Edit link $get_current_link_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Footer</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_link_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_link&amp;link_id=$get_current_link_id&amp;editor_language=$editor_language&amp;l=$l\">Edit link</a>
			</p>
		<!-- //Where am I? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Edit link form -->
			<form method=\"post\" action=\"?open=$open&amp;page=$page&amp;action=$action&amp;link_id=$get_current_link_id&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
				

	
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<p><b>Title</b>*<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_link_title\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>URL</b>*<br />
			<input type=\"text\" name=\"inp_url\" value=\"$get_current_link_url\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<!- icons -->
			";
			$icons_size_array = array("24x24", "32x32");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18_inactive)
				$inp_name = "inp_icon_" . $icons_size_array[$x];

				if($icons_size_array[$x] == "24x24"){
					$icon = $get_current_link_icon_24x24;
				}
				else{
					$icon = $get_current_link_icon_32x32;
				}
				echo"
				<p><b>Icon $icons_size_array[$x]:</b><br />
				";
				if(file_exists("../$get_current_link_icon_path/$icon") && $icon != ""){
					echo"
					<img src=\"../$get_current_link_icon_path/$icon\" alt=\"$icon\" />
					<a href=\"../$get_current_link_icon_path/$icon\">$icon</a>
					</p>
					<p>New icon $icons_size_array[$x]:<br />
					";
				}
				else{
					echo"
					<a href=\"../$get_current_link_icon_path/$icon\" style=\"text-decoration: line-through\">$icon</a><br />
					";
				}

				echo"
				<input type=\"file\" name=\"$inp_name\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>";
			}
			echo"

	
			<p><input type=\"submit\" value=\"Update link\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
			</form>
		<!-- //Edit link form -->


		";
	} // found
} // edit_link
elseif($action == "delete_link"){
	$link_id_mysql = quote_smart($link, $link_id);

	$query = "SELECT link_id, link_group_id, link_title, link_url, link_internal_or_external, link_language, link_weight FROM $t_webdesign_footer_link_links WHERE link_id=$link_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_link_id, $get_current_link_group_id, $get_current_link_title, $get_current_link_url, $get_current_link_internal_or_external, $get_current_link_language, $get_current_link_weight) = $row;

	if($get_current_link_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Link not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find group
		$query = "SELECT group_id, group_title, group_language, group_weight, group_number_of_links FROM $t_webdesign_footer_link_groups WHERE group_id=$get_current_link_group_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_language, $get_current_group_weight, $get_current_group_number_of_links) = $row;


		if($process == "1"){
			
			// Deelte
			mysqli_query($link, "DELETE FROM $t_webdesign_footer_link_links WHERE link_id=$get_current_link_id") or die(mysqli_error($link));

			header("Location: index.php?open=$open&page=$page&action=open_group&group_id=$get_current_link_group_id&ft=success&fm=link_deleted&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Delete link $get_current_link_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Footer</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_link_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_link&amp;link_id=$get_current_link_id&amp;editor_language=$editor_language&amp;l=$l\">Delete link</a>
			</p>
		<!-- //Where am I? -->



		<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Delete link form -->
			<p>
			Are you sure you want to delete the link?
			</p>

			<p>
			<a href=\"?open=$open&amp;page=$page&amp;action=$action&amp;link_id=$get_current_link_id&amp;process=1&amp;editor_language=$editor_language\" class=\"btn_warning\">Delete</a>
			<a href=\"?open=$open&amp;page=$page&amp;action=$action&amp;link_id=$get_current_link_id&amp;process=1&amp;editor_language=$editor_language\" class=\"btn_default\">Cancel</a>
			</p>
		<!-- //Delete link form -->


		";
	} // found
} // delete _link
?>