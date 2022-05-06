<?php
/**
*
* File: _admin/_inc/pages/frontpage_grid_items.php
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
$t_grid_groups	= $mysqlPrefixSav . "grid_groups";
$t_grid_items	= $mysqlPrefixSav . "grid_items";

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
if(isset($_GET['item_id'])) {
	$item_id = $_GET['item_id'];
	$item_id = strip_tags(stripslashes($item_id));
	if(!(is_numeric($item_id))){
		echo"Item not numeric";
		die;
	}
}
else{
	$item_id = "";
}
$tabindex = 0;

if($action == ""){
	echo"
	<h2>Grids</h2>

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

	
	<!-- Grids menu -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=css_code&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">CSS</a>
		</p>
	<!-- //Grids menu -->

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
				$query = "SELECT group_id, group_title FROM $t_grid_groups WHERE group_language=$editor_language_mysql ORDER BY group_title ASC";
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
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/create_new_folder_outline_black_18x18.png\" alt=\"create_new_folder_outline_black_18x18.png\" /></a>
					</p>
				  </td>
				  <td>
					<p>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\">New group</a>
					</p>
				  </td>
				 </tr>
				</table>
			<!-- //Left -->
		  </td>
		  <td style=\"vertical-align: top;\">
			<!-- Right - Grid items -->

				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th>
					<span>Category</span>
				   </th>
				   <th>
					<span>Title</span>
		 		  </th>
				   <th>
					<span>URL</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>";
				$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_language=$editor_language_mysql ORDER BY item_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_item_id, $get_item_language, $get_item_group_id, $get_item_title, $get_item_url, $get_item_weight, $get_item_icon_path, $get_item_icon_18x18, $get_item_icon_24x24, $get_item_icon_36x36, $get_item_icon_48x48, $get_item_created_datetime, $get_item_created_user_id, $get_item_updated_datetime, $get_item_updated_user_id) = $row;
					echo"
					  <tr>
					   <td>
						<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_item_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_item_group_id</a></span>
					   </td>
					   <td>
						<span>$get_item_title</span>
					   </td>
					   <td>
						<span><a href=\"$get_item_url\">$get_item_url</a></span>
					   </td>
					  </tr>
					";
				}
				echo"
				   </td>
				  </tr>
				 </tbody>
				</table>
			<!-- //Right - Grid items -->
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

		$inp_title_english = $_POST['inp_title_english'];
		$inp_title_english = output_html($inp_title_english);
		$inp_title_english_mysql = quote_smart($link, $inp_title_english);

		if($inp_title == ""){
			header("Location: index.php?open=$open&page=$page&action=$action&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
			exit;
		}

		$inp_active = $_POST['inp_active'];
		$inp_active = output_html($inp_active);
		$inp_active_mysql = quote_smart($link, $inp_active);

		$datetime = date("Y-m-d H:i:s");

		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);


		// Insert
		mysqli_query($link, "INSERT INTO $t_grid_groups
		(group_id, group_language, group_title, group_title_english, group_active, group_preferred_icon_size, group_created_user_id, group_created_datetime) 
		VALUES 
		(NULL, $inp_language_mysql, $inp_title_mysql, $inp_title_english_mysql, $inp_active_mysql, '36x36', $my_user_id_mysql, '$datetime')")
		or die(mysqli_error($link));


		header("Location: index.php?open=$open&page=$page&action=$action&ft=success&fm=group_created&editor_language=$editor_language");
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
			echo"	<option value=\"$get_language_active_iso_two\" ";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>Title</b>*<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Title English</b>*<br />
		<input type=\"text\" name=\"inp_title_english\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /><br />
		<span class=\"small\">To make the grid appear on frontpage set english title to Frontpage.</span>
		</p>

		<p><b>Active</b>*<br />
		<input type=\"radio\" name=\"inp_active\" value=\"1\" checked=\"checked\" /> Yes
		<input type=\"radio\" name=\"inp_active\" value=\"0\" /> No
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
		</form>
	<!-- //New group form -->


	<!-- Back -->
		<table>
		 <tr>
		  <td style=\"padding-right: 4px;\">
			<p>
			<a href=\"index.php?open=$open&amp;page=$page\"><img src=\"_design/gfx/icons/18x18/arrow_back_outline_black_18x18.png\" alt=\"\" /></a>
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

	$query = "SELECT group_id, group_language, group_title, group_active, group_created_user_id, group_created_datetime, group_updated_user_id, group_updated_datetime FROM $t_grid_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_language, $get_current_group_title, $get_current_group_active, $get_current_group_created_user_id, $get_current_group_created_datetime, $get_current_group_updated_user_id, $get_current_group_updated_datetime) = $row;

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
		echo"
		<h1>$get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
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
					$query = "SELECT group_id, group_title FROM $t_grid_groups WHERE group_language=$editor_language_mysql ORDER BY group_title ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_group_id, $get_group_title) = $row;
						echo"
						<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\""; if($get_group_id == "$get_current_group_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_group_title</a><br /></span>
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
						<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/create_new_folder_outline_black_18x18.png\" alt=\"create_new_folder_outline_black_18x18.png\" /></a>
						</p>
					  </td>
					  <td>
						<p>
						<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_group&amp;editor_language=$editor_language\">New group</a>
						</p>
					  </td>
					 </tr>
					</table>
				<!-- //Left -->
			  </td>
			  <td style=\"vertical-align: top;\">
				<!-- Right -->
					<!-- Active check -->
						";
						if($get_current_group_active == "0"){
							echo"
							<p>
							The group is inactive: [<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\">Activate group</a>]
							</p>
							";
						}
						echo"
					<!-- //Active check -->
					<!-- Open group menu -->

						<table>
						 <tr>
						  <td style=\"padding: 0px 4px 0px 0px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/add_outline_black_18x18.png\" alt=\"add_outline_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td style=\"padding: 0px 10px 0px 0px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\">New item</a>
							</p>
						  </td>



						  <td style=\"padding: 0px 4px 0px 10px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/edit_round_black_18x18.png\" alt=\"edit_round_black_18x18.png\" /></a>
							</p>
						  </td>
						  <td>
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\">Edit group</a>
							</p>
						  </td>

						  <td style=\"padding: 0px 4px 0px 10px;\">
							<p>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/18x18/delete_round_black_18x18.png\" alt=\"delete_round_black_18x18.png\" /></a>
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
					$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_language=$editor_language_mysql AND item_group_id=$get_current_group_id ORDER BY item_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_item_id, $get_item_language, $get_item_group_id, $get_item_title, $get_item_url, $get_item_weight, $get_item_icon_path, $get_item_icon_18x18, $get_item_icon_24x24, $get_item_icon_36x36, $get_item_icon_48x48, $get_item_created_datetime, $get_item_created_user_id, $get_item_updated_datetime, $get_item_updated_user_id) = $row;
						// Check weight
						if($get_item_weight != "$x"){
							$result_update = mysqli_query($link, "UPDATE $t_grid_items SET item_weight=$x WHERE item_id=$get_item_id") OR die(mysqli_error($link));
						}


						echo"
						  <tr>
						   <td>
							<span>$get_item_title</span>
						   </td>
						   <td>
							<span><a href=\"$get_item_url\">$get_item_url</a></span>
						   </td>
						   <td>
							<span>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_item_up&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Up</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_item_down&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">Down</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_item&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_item&amp;item_id=$get_item_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
							</span>
						   </td>
						  </tr>
						";

						$x++;
					}
					echo"
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
elseif($action == "edit_group"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_language, group_title, group_title_english, group_active, group_preferred_icon_size, group_created_user_id, group_created_datetime, group_updated_user_id, group_updated_datetime FROM $t_grid_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_language, $get_current_group_title, $get_current_group_title_english, $get_current_group_active, $get_current_group_preferred_icon_size, $get_current_group_created_user_id, $get_current_group_created_datetime, $get_current_group_updated_user_id, $get_current_group_updated_datetime) = $row;

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

			$inp_title_english = $_POST['inp_title_english'];
			$inp_title_english = output_html($inp_title_english);
			$inp_title_english_mysql = quote_smart($link, $inp_title_english);

			if($inp_title == ""){
				header("Location: index.php?open=$open&page=$page&action=$action&group_id=$groupd_id&focus=inp_name&ft=warning&fm=please_enter_a_title&editor_language=$editor_language");
				exit;
			}

			$inp_active = $_POST['inp_active'];
			$inp_active = output_html($inp_active);
			$inp_active_mysql = quote_smart($link, $inp_active);

			$inp_preferred_icon_size = $_POST['inp_preferred_icon_size'];
			$inp_preferred_icon_size = output_html($inp_preferred_icon_size);
			$inp_preferred_icon_size_mysql = quote_smart($link, $inp_preferred_icon_size);



			$datetime = date("Y-m-d H:i:s");

			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			
			// Update
			$result = mysqli_query($link, "UPDATE $t_grid_groups SET 
							group_title=$inp_title_mysql, 
							group_title_english=$inp_title_english_mysql, 
							group_language=$inp_language_mysql,
							group_active=$inp_active_mysql,
							group_preferred_icon_size=$inp_preferred_icon_size_mysql,
							group_updated_user_id=$my_user_id_mysql, 
							group_updated_datetime='$datetime' 
							WHERE group_id=$get_current_group_id") OR die(mysqli_error($link));

			header("Location: index.php?open=$open&page=$page&action=$action&group_id=$get_current_group_id&ft=success&fm=group_created&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Edit $get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
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

			<p><b>Title English</b>*<br />
			<input type=\"text\" name=\"inp_title_english\" value=\"$get_current_group_title_english\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Active</b>*<br />
			<input type=\"radio\" name=\"inp_active\" value=\"1\""; if($get_current_group_active == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_active\" value=\"0\""; if($get_current_group_active == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p><b>Preferred icon size</b>*<br />
			<input type=\"text\" name=\"inp_preferred_icon_size\" value=\"$get_current_group_preferred_icon_size\" size=\"20\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"Update\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
			</form>
		<!-- //Edit group form -->


		";
	} // found
} // edit_group
elseif($action == "delete_group"){
	$group_id_mysql = quote_smart($link, $group_id);

	$query = "SELECT group_id, group_language, group_title, group_created_user_id, group_created_datetime, group_updated_user_id, group_updated_datetime FROM $t_grid_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_language, $get_current_group_title, $get_current_group_created_user_id, $get_current_group_created_datetime, $get_current_group_updated_user_id, $get_current_group_updated_datetime) = $row;

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
			$result = mysqli_query($link, "DELETE FROM $t_grid_groups WHERE group_id=$get_current_group_id") OR die(mysqli_error($link));
			$result = mysqli_query($link, "DELETE FROM $t_grid_items WHERE item_group_id=$get_current_group_id") OR die(mysqli_error($link));

			header("Location: index.php?open=$open&page=$page&ft=success&fm=group_deleted&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Delete $get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
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
elseif($action == "new_item"){
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_language, group_title, group_active, group_preferred_icon_size, group_created_user_id, group_created_datetime, group_updated_user_id, group_updated_datetime FROM $t_grid_groups WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_language, $get_current_group_title, $get_current_group_active, $get_current_group_preferred_icon_size, $get_current_group_created_user_id, $get_current_group_created_datetime, $get_current_group_updated_user_id, $get_current_group_updated_datetime) = $row;

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

			$datetime = date("Y-m-d H:i:s");

			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);


			// Get weight
			$query = "SELECT count(*) FROM $t_grid_items WHERE item_group_id=$get_current_group_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_rows) = $row;
			if($get_count_rows == ""){
				$get_count_rows = 0;
			}

			// Insert
			$inp_language_mysql = quote_smart($link, $get_current_group_language);
			mysqli_query($link, "INSERT INTO $t_grid_items 
			(item_id, item_language, item_group_id, item_title, item_url, item_weight, item_created_datetime, item_created_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_current_group_id, $inp_title_mysql, $inp_url_mysql, '$get_count_rows', '$datetime', $my_user_id_mysql)")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT item_id FROM $t_grid_items WHERE item_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_item_id) = $row;


			// Dir
			$upload_path = "../_uploads/grids/$get_current_group_language";
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/grids"))){
				mkdir("../_uploads/grids");
			}
			if(!(is_dir("../_uploads/grids/$get_current_group_language"))){
				mkdir("../_uploads/grids/$get_current_group_language");
			}

			$icons_size_array = array("18x18", "24x24", "36x36", "48x48");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				
				// 1. Icon normal
				// Name (inp_icon_18x18)
				$file_name = basename($_FILES["inp_icon_" . $icons_size_array[$x]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $get_current_item_id . "_" . $icons_size_array[$x] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_" . $icons_size_array[$x]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/grids/$get_current_group_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_grid_items SET 
											item_icon_path=$inp_path_mysql, 
											item_icon_$icons_size_array[$x]=$inp_icon_mysql
											WHERE item_id=$get_current_item_id") or die(mysqli_error($link));
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


				// 2. Icon hover
				// Name (inp_icon_18x18)
				$file_name = basename($_FILES["inp_icon_hover_" . $icons_size_array[$x]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $get_current_item_id . "_hover_" . $icons_size_array[$x] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_hover_" . $icons_size_array[$x]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/grids/$get_current_group_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_grid_items SET 
											item_icon_path=$inp_path_mysql, 
											item_icon_hover_$icons_size_array[$x]=$inp_icon_mysql
											WHERE item_id=$get_current_item_id") or die(mysqli_error($link));
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


			} // for icon sizes


			header("Location: index.php?open=$open&page=$page&action=$action&group_id=$get_current_group_id&ft=success&fm=created&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>New link in $get_current_group_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_item&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">New item</a>
			</p>
		<!-- //Where am I? -->

		<!-- Group Active? -->";
			if($get_current_group_active == "0"){
				echo"
				<p>
				The group is inactive: 
				[<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">Active group</a>]
				</p>
				";
			}
			echo"
		<!-- //Group Active? -->

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
				<h2>Item information</h2>

			<p><b>Title</b>*<br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>URL</b>*<br />
			<input type=\"text\" name=\"inp_url\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
	
			<!- icons -->
				<h2>Icons</h2>
				<p>Preferred icon size is $get_current_group_preferred_icon_size.</p>
			";
			$icons_size_array = array("18x18", "24x24", "36x36", "48x48");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18_inactive)
				$inp_name = "inp_icon_" . $icons_size_array[$x];
				$inp_name_hover = "inp_icon_hover_" . $icons_size_array[$x];

				echo"
				<hr />
				<p><b>Icon $icons_size_array[$x]:</b><br />
				<input type=\"file\" name=\"$inp_name\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Icon hover $icons_size_array[$x]:</b><br />
				<input type=\"file\" name=\"$inp_name_hover\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>";
			}
			echo"


			<p><input type=\"submit\" value=\"Create item\" class=\"btn btn-success btn-sm\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
			</form>
		<!-- //New link form -->


		";
	} // found
} // new_link
elseif($action == "move_item_up"){
	$item_id_mysql = quote_smart($link, $item_id);
	$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_language, $get_current_item_group_id, $get_current_item_title, $get_current_item_url, $get_current_item_weight, $get_current_item_icon_path, $get_current_item_icon_18x18, $get_current_item_icon_24x24, $get_current_item_icon_36x36, $get_current_item_icon_48x48, $get_current_item_created_datetime, $get_current_item_created_user_id, $get_current_item_updated_datetime, $get_current_item_updated_user_id) = $row;

	if($get_current_item_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find switch
		$switch_weight = $get_current_item_weight-1;
		$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_group_id=$get_current_item_group_id AND item_weight=$switch_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_item_id, $get_switch_item_language, $get_switch_item_group_id, $get_switch_item_title, $get_switch_item_url, $get_switch_item_weight, $get_switch_item_icon_path, $get_switch_item_icon_18x18, $get_switch_item_icon_24x24, $get_switch_item_icon_36x36, $get_switch_item_icon_48x48, $get_switch_item_created_datetime, $get_switch_item_created_user_id, $get_switch_item_updated_datetime, $get_switch_item_updated_user_id) = $row;
		if($get_switch_item_id == ""){
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_link_group_id&editor_language=$editor_language&l=$l&ft=info&fm=could_not_move_up_because_there_where_no_other_link_to_switch_with";
			header("Location: $url");
			exit;
		}
		else{
			// Update current
			$result = mysqli_query($link, "UPDATE $t_grid_items SET item_weight=$get_switch_item_weight WHERE item_id=$get_current_item_id") OR die(mysqli_error($link));
			

			// Update switch
			$result = mysqli_query($link, "UPDATE $t_grid_items SET item_weight=$get_current_item_weight WHERE item_id=$get_switch_item_id") OR die(mysqli_error($link));
		
			// Header
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_item_group_id&editor_language=$editor_language&l=$l&ft=success&fm=item_moved_up";
			header("Location: $url");
			exit;
		}
		
	} // found
} // move_item_up
elseif($action == "move_item_down"){
	$item_id_mysql = quote_smart($link, $item_id);
	$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_language, $get_current_item_group_id, $get_current_item_title, $get_current_item_url, $get_current_item_weight, $get_current_item_icon_path, $get_current_item_icon_18x18, $get_current_item_icon_24x24, $get_current_item_icon_36x36, $get_current_item_icon_48x48, $get_current_item_created_datetime, $get_current_item_created_user_id, $get_current_item_updated_datetime, $get_current_item_updated_user_id) = $row;

	if($get_current_item_id == ""){
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
		$switch_weight = $get_current_item_weight+1;
		$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_group_id=$get_current_item_group_id AND item_weight=$switch_weight";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_switch_item_id, $get_switch_item_language, $get_switch_item_group_id, $get_switch_item_title, $get_switch_item_url, $get_switch_item_weight, $get_switch_item_icon_path, $get_switch_item_icon_18x18, $get_switch_item_icon_24x24, $get_switch_item_icon_36x36, $get_switch_item_icon_48x48, $get_switch_item_created_datetime, $get_switch_item_created_user_id, $get_switch_item_updated_datetime, $get_switch_item_updated_user_id) = $row;
		if($get_switch_item_id == ""){
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_item_group_id&editor_language=$editor_language&l=$l&ft=info&fm=could_not_move_down_because_there_where_no_other_link_to_switch_with";
			header("Location: $url");
			exit;
		}
		else{
			// Update current
			$result = mysqli_query($link, "UPDATE $t_grid_items SET item_weight=$get_switch_item_weight WHERE item_id=$get_current_item_id") OR die(mysqli_error($link));
			

			// Update switch
			$result = mysqli_query($link, "UPDATE $t_grid_items SET item_weight=$get_current_item_weight WHERE item_id=$get_switch_item_id") OR die(mysqli_error($link));
		
			// Header
			$url = "index.php?open=$open&page=$page&action=open_group&group_id=$get_current_item_group_id&editor_language=$editor_language&l=$l&ft=success&fm=item_moved_up";
			header("Location: $url");
			exit;
		}
		
	} // found
} // move_item_down
elseif($action == "edit_item"){
	$item_id_mysql = quote_smart($link, $item_id);
	$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_hover_18x18, item_icon_24x24, item_icon_hover_24x24, item_icon_36x36, item_icon_hover_36x36, item_icon_48x48, item_icon_hover_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_language, $get_current_item_group_id, $get_current_item_title, $get_current_item_url, $get_current_item_weight, $get_current_item_icon_path, $get_current_item_icon_18x18, $get_current_item_icon_hover_18x18, $get_current_item_icon_24x24, $get_current_item_icon_hover_24x24, $get_current_item_icon_36x36, $get_current_item_icon_hover_36x36, $get_current_item_icon_48x48, $get_current_item_icon_hover_48x48, $get_current_item_created_datetime, $get_current_item_created_user_id, $get_current_item_updated_datetime, $get_current_item_updated_user_id) = $row;

	if($get_current_item_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find group
		$query = "SELECT group_id, group_language, group_title, group_preferred_icon_size, group_created_user_id, group_created_datetime, group_updated_user_id, group_updated_datetime FROM $t_grid_groups WHERE group_id=$get_current_item_group_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_language, $get_current_group_title, $get_current_group_preferred_icon_size, $get_current_group_created_user_id, $get_current_group_created_datetime, $get_current_group_updated_user_id, $get_current_group_updated_datetime) = $row;

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



			$datetime = date("Y-m-d H:i:s");
			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);



			// UPDATE
			mysqli_query($link, "UPDATE $t_grid_items SET
						item_title=$inp_title_mysql, 
						item_url=$inp_url_mysql, 
						item_updated_datetime='$datetime',
						item_updated_user_id=$my_user_id_mysql
						WHERE item_id=$get_current_item_id") or die(mysqli_error($link));


			// Dir
			$upload_path = "../_uploads/grids/$get_current_group_language";
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/grids"))){
				mkdir("../_uploads/grids");
			}
			if(!(is_dir("../_uploads/grids/$get_current_group_language"))){
				mkdir("../_uploads/grids/$get_current_group_language");
			}

			$icons_size_array = array("18x18", "24x24", "36x36", "48x48");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18)
				// 1. Normal
				$file_name = basename($_FILES["inp_icon_" . $icons_size_array[$x]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $get_current_item_id . "_" . $icons_size_array[$x] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_" . $icons_size_array[$x]]['tmp_name'], "$upload_path/$new_name")) {
						
						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/grids/$get_current_group_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_grid_items SET 
											item_icon_path=$inp_path_mysql, 
											item_icon_$icons_size_array[$x]=$inp_icon_mysql
											WHERE item_id=$get_current_item_id") or die(mysqli_error($link));
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


				// 2. Hover
				$file_name = basename($_FILES["inp_icon_hover_" . $icons_size_array[$x]]['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// New name
				$new_name = $get_current_item_id . "_hover_" . $icons_size_array[$x] . "." . $file_type;

				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES["inp_icon_hover_" . $icons_size_array[$x]]['tmp_name'], "$upload_path/$new_name")) {

						// Sjekk om det faktisk er et bilde som er lastet opp
						list($width,$height) = getimagesize("$upload_path/$new_name");
						if(is_numeric($width) && is_numeric($height)){
							// Update MySQL

							// path
							$inp_path = "_uploads/grids/$get_current_group_language";
							$inp_path_mysql = quote_smart($link, $inp_path);

							// icon
							$inp_icon = $new_name;
							$inp_icon_mysql = quote_smart($link, $inp_icon);
							

							// Update MySQL
							$result = mysqli_query($link, "UPDATE $t_grid_items SET 
											item_icon_path=$inp_path_mysql, 
											item_icon_hover_$icons_size_array[$x]=$inp_icon_mysql
											WHERE item_id=$get_current_item_id") or die(mysqli_error($link));
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


			} // for image sizes

			header("Location: index.php?open=$open&page=$page&action=$action&item_id=$get_current_item_id&ft=success&fm=link_updated&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Edit item $get_current_item_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_item_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_item&amp;link_id=$get_current_item_id&amp;editor_language=$editor_language&amp;l=$l\">Edit item</a>
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
			<form method=\"post\" action=\"?open=$open&amp;page=$page&amp;action=$action&amp;item_id=$get_current_item_id&amp;process=1&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\">
				

	
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			<!-- //Focus -->
				<h2>Item information</h2>

			<p><b>Title</b>*<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_item_title\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>URL</b>*<br />
			<input type=\"text\" name=\"inp_url\" value=\"$get_current_item_url\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<!- icons -->
				<h2>Icons</h2>
				<p>Preferred icon size is $get_current_group_preferred_icon_size.</p>
			";
			$icons_size_array = array("18x18", "24x24", "36x36", "48x48");
			for($x=0;$x<sizeof($icons_size_array);$x++){
				// Name (inp_icon_18x18_inactive)
				$inp_name = "inp_icon_" . $icons_size_array[$x];
				$inp_name_hover = "inp_icon_hover_" . $icons_size_array[$x];

				if($icons_size_array[$x] == "18x18"){
					$icon = $get_current_item_icon_18x18;
					$icon_hover = $get_current_item_icon_hover_18x18;
				}
				elseif($icons_size_array[$x] == "24x24"){
					$icon = $get_current_item_icon_24x24;
					$icon_hover = $get_current_item_icon_hover_24x24;
				}
				elseif($icons_size_array[$x] == "36x36"){
					$icon = $get_current_item_icon_36x36;
					$icon_hover = $get_current_item_icon_hover_36x36;
				}
				else{
					$icon = $get_current_item_icon_48x48;
					$icon_hover = $get_current_item_icon_hover_48x48;
				}

				// Normal
				echo"
				<hr />
				<p><b>Icon $icons_size_array[$x]:</b><br />
				";
				if(file_exists("../$get_current_item_icon_path/$icon") && $icon != ""){
					echo"
					<img src=\"../$get_current_item_icon_path/$icon\" alt=\"$icon\" />
					<a href=\"../$get_current_item_icon_path/$icon\">$icon</a>
					</p>
					<p>New icon $icons_size_array[$x]:<br />
					";
				}
				else{
					echo"
					<a href=\"../$get_current_item_icon_path/$icon\" style=\"text-decoration: line-through\">$icon</a><br />
					";
				}
				echo"
				<input type=\"file\" name=\"$inp_name\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>";


				// Hover
				echo"
				<p><b>Icon hover $icons_size_array[$x]:</b><br />
				";
				if(file_exists("../$get_current_item_icon_path/$icon_hover") && $icon_hover != ""){
					echo"
					<img src=\"../$get_current_item_icon_path/$icon_hover\" alt=\"$icon_hover\" />
					<a href=\"../$get_current_item_icon_path/$icon_hover\">$icon_hover</a>
					</p>
					<p>New icon hover $icons_size_array[$x]:<br />
					";
				}
				else{
					echo"
					<a href=\"../$get_current_item_icon_path/$icon_hover\" style=\"text-decoration: line-through\">$icon_hover</a><br />
					";
				}
				echo"
				<input type=\"file\" name=\"$inp_name_hover\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>";
			}
			echo"

	
			<p><input type=\"submit\" value=\"Update\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	 
			</form>
		<!-- //Edit item form -->


		";
	} // found
} // edit_item
elseif($action == "delete_item"){
	$item_id_mysql = quote_smart($link, $item_id);
	$query = "SELECT item_id, item_language, item_group_id, item_title, item_url, item_weight, item_icon_path, item_icon_18x18, item_icon_24x24, item_icon_36x36, item_icon_48x48, item_created_datetime, item_created_user_id, item_updated_datetime, item_updated_user_id FROM $t_grid_items WHERE item_id=$item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_item_id, $get_current_item_language, $get_current_item_group_id, $get_current_item_title, $get_current_item_url, $get_current_item_weight, $get_current_item_icon_path, $get_current_item_icon_18x18, $get_current_item_icon_24x24, $get_current_item_icon_36x36, $get_current_item_icon_48x48, $get_current_item_created_datetime, $get_current_item_created_user_id, $get_current_item_updated_datetime, $get_current_item_updated_user_id) = $row;

	if($get_current_item_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>
		Item not found.
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page\">Home</a>
		</p>
		";
	}
	else{
		// Find group
		$query = "SELECT group_id, group_language, group_title, group_created_user_id, group_created_datetime, group_updated_user_id, group_updated_datetime FROM $t_grid_groups WHERE group_id=$get_current_item_group_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_language, $get_current_group_title, $get_current_group_created_user_id, $get_current_group_created_datetime, $get_current_group_updated_user_id, $get_current_group_updated_datetime) = $row;

		if($process == "1"){
			
			// Deelte
			mysqli_query($link, "DELETE FROM $t_grid_items WHERE item_id=$get_current_item_id") or die(mysqli_error($link));

			header("Location: index.php?open=$open&page=$page&action=open_group&group_id=$get_current_item_group_id&ft=success&fm=deleted&editor_language=$editor_language");
			exit;
		}
		echo"
		<h1>Delete item $get_current_item_title</h1>
	
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_item_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_link&amp;item_id=$get_current_item_id&amp;editor_language=$editor_language&amp;l=$l\">Delete item</a>
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
			Are you sure you want to delete the item?
			</p>

			<p>
			<a href=\"?open=$open&amp;page=$page&amp;action=$action&amp;item_id=$get_current_item_id&amp;process=1&amp;editor_language=$editor_language\" class=\"btn_warning\">Delete</a>
			<a href=\"?open=$open&amp;page=$page&amp;action=open_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language\" class=\"btn_default\">Cancel</a>
			</p>
		<!-- //Delete link form -->


		";
	} // found
} // delete_item
elseif($action == "css_code"){
	echo"
	<h1>CSS code</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Grids</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=css_code&amp;editor_language=$editor_language&amp;l=$l\">CSS code</a>
		</p>
	<!-- //Where am I? -->

	<p>
	<textarea style=\"width: 100%;height: 600px;\">
/*- Grid on index ------------------------------------------------------------------ */
div.grid_wrapper{
	display: grid;
	grid-template-columns: 1fr 1fr 1fr;
}
div.grid_wrapper > div.grid_item{
	padding: 15px 0px 15px 0px;
}
div.grid_wrapper > div.grid_item > a > img{
	float: left;
}
div.grid_wrapper > div.grid_item > a > span{
	float: left;
	padding: 10px 0px 0px 0px;
	font-size: 110%;
	color: #474445;
}
@media screen and (max-width: 52.375em) {

	div.grid_wrapper > div.grid_item{
		text-align: center;
	}
	div.grid_wrapper > div.grid_item > a > img{
		float: none;
		padding: 0px 0px 4px 0px;
	}
	div.grid_wrapper > div.grid_item > a > span::before {
		content: &quot;\A&quot;;
		white-space: pre;
	}
	div.grid_wrapper > div.grid_item > a > span{
		float: none;
		padding: 0px 0px 0px 0px;
		font-size: 110%;
		color: #474445;
	}
}</textarea></p>
	
	";
} // css_code
?>