<?php
/**
*
* File: _admin/_inc/settings/admin_navigation.php
* Version 1.0
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['navigation_id'])) {
	$navigation_id = $_GET['navigation_id'];
	$navigation_id  = strip_tags(stripslashes($navigation_id));
}
else{
	$navigation_id = "";
}

if($action == ""){
	echo"
	<h1>Admin navigation</h1>

	
	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">My navigation</a>
	</p>

	<!-- Navigation -->
		<div class=\"vertical\">
			<ul>
			";
			// Custom pages
			$filenames = "";
			$dir = "_inc/";
			if ($handle = opendir($dir)) {
				$files = array();   
				while (false !== ($file = readdir($handle))) {
					if ($file === '.') continue;
					if ($file === '..') continue;
					if ($file === "admin_cms") continue;
					if ($file === "login") continue;
					if ($file === "ucp") continue;
					if ($file === "setup") continue;

					array_push($files, $file);
				}
				
				sort($files);
				foreach ($files as $file){
					// $content_saying = 


					$admin_navigation_title = ucfirst($file);
					$admin_navigation_icon = "$file";
					$admin_navigation_icon_black_small = $file . "_black_18x18.png";
					$admin_navigation_icon_black_medium = $file . "_black_24x24.png";
					$admin_navigation_icon_white_small = $file . "_white_18x18.png";
					$admin_navigation_icon_white_medium = $file . "t_white_24x24.png";

					echo"
					<li><a href=\"index.php?open=$open&amp;page=default&amp;action=add_to_favorite_and_visit&amp;item=$file&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_inc/$file/_gfx/icons/$admin_navigation_icon_black_small\" alt=\"$admin_navigation_icon_black_small\" /> $admin_navigation_title</a></li>";
				}
				closedir($handle);
			}
			echo"

			
			</ul>
		</div>
	<!-- //Navigation -->
	";
}
elseif($action == "my_navigation"){
	echo"
	<h1>Admin navigation</h1>


	<!-- Where am I? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Admin navigation</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;editor_language=$editor_language&amp;l=$l\">My navigation</a>
		</p>
	<!-- //Where am I? -->

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->



	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=add_item&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/add_outline_black_18x18.png\" alt=\"add_outline_black_18x18.png\" /> Add item</a>
	</p>


	<!-- Admin navigation for me -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Navigation</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";

		$x = 0;
		$query = "SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_user_id=$my_user_id_mysql ORDER BY navigation_weight ASC";
		$result = $mysqli->query($query);
		while($row = $result->fetch_row()) {
			list($get_navigation_id, $get_navigation_url, $get_navigation_title, $get_navigation_icon_white_18, $get_navigation_icon_black_18, $get_navigation_weight) = $row;

			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}
	
			// Check order
			if($get_navigation_weight != "$x"){
				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$x WHERE navigation_id=$get_navigation_id") !== TRUE) {
					echo "Error updating record: " . $mysqli->error; die;
				}
			}

			echo"
			 <tr>
			  <td class=\"$style\">
				<span><img src=\"_inc/$get_navigation_url/_gfx/icons/$get_navigation_icon_black_18\" alt=\"$get_navigation_icon_black_18\" /> $get_navigation_title</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_up&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_upward_round_black_18x18.png\" alt=\"arrow_upward_round_black_18x18.png\" /></a> &nbsp;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=move_down&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_design/gfx/icons/18x18/arrow_downward_round_black_18x18.png\" alt=\"arrow_downward_round_black_18x18.png\" /></a>  &nbsp;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/edit_outline_black_18x18.png\" alt=\"edit_outline_black_18x18.png\" /></a>  &nbsp;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/18x18/delete_outline_black_18x18.png\" alt=\"delete_outline_black_18x18.png\" /></a>
				</span>
			  </td>
			 </tr>
			";


			$x++;
		}
		echo"
	
		 </tbody>
		</table>
	<!-- //Admin navigation for me -->
	";
}
elseif($action == "move_up"){
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);
	
	$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_id=? AND navigation_user_id=?"); 
	$stmt->bind_param("ss", $my_user_id, $navigation_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_navigation_id, $get_current_navigation_url, $get_current_navigation_title, $get_current_navigation_icon_white_18, $get_current_navigation_icon_black_18, $get_current_navigation_weight) = $row;

	if($get_current_navigation_id != ""){
		if($process == "1"){
			// Find the one we want to switch with
			$inp_current_navigation_weight = $get_current_navigation_weight-1;

			$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_weight=? AND navigation_user_id=?"); 
			$stmt->bind_param("ss", inp_current_navigation_weight, $my_user_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_switch_navigation_id, $get_switch_navigation_url, $get_switch_navigation_title, $get_switch_navigation_icon_white_18, $get_switch_navigation_icon_black_18, $get_switch_navigation_weight) = $row;

			
			if($get_switch_navigation_id != ""){
				$inp_current_navigation_weight = $get_switch_navigation_weight;
				$inp_switch_navigation_weight = $get_current_navigation_weight;

				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$inp_current_navigation_weight WHERE navigation_id=$get_current_navigation_id") !== TRUE) {
					echo "Error updating record: " . $mysqli->error; die;
				}
				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$inp_switch_navigation_weight WHERE navigation_id=$get_switch_navigation_id") !== TRUE) {
					echo "Error updating record: " . $mysqli->error; die;
				}


				$url = "index.php?open=$open&page=$page&action=my_navigation&ft=success&fm=moved";
				header("Location: $url");
				exit;
			}
			else{
				$url = "index.php?open=$open&page=$page&action=my_navigation&ft=info&fm=not_possible";
				header("Location: $url");
				exit;

			}
		}

	}
} // move_up
elseif($action == "move_down"){
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);

	$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_id=? AND navigation_user_id=?"); 
	$stmt->bind_param("ss", $navigation_id, $my_user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_navigation_id, $get_current_navigation_url, $get_current_navigation_title, $get_current_navigation_icon_white_18, $get_current_navigation_icon_black_18, $get_current_navigation_weight) = $row;

	if($get_current_navigation_id != ""){
		if($process == "1"){
			// Find the one we want to switch with
			$inp_current_navigation_weight = $get_current_navigation_weight+1;

			$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_weight=? AND navigation_user_id=?"); 
			$stmt->bind_param("ss", $inp_current_navigation_weight, $my_user_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_switch_navigation_id, $get_switch_navigation_url, $get_switch_navigation_title, $get_switch_navigation_icon_white_18, $get_switch_navigation_icon_black_18, $get_switch_navigation_weight) = $row;

			
			if($get_switch_navigation_id != ""){
				$inp_current_navigation_weight = $get_switch_navigation_weight;
				$inp_switch_navigation_weight = $get_current_navigation_weight;

				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$inp_current_navigation_weight WHERE navigation_id=$get_current_navigation_id") !== TRUE) {
					echo "Error updating record: " . $mysqli->error; die;
				}
				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$inp_switch_navigation_weight WHERE navigation_id=$get_switch_navigation_id") !== TRUE) {
					echo "Error updating record: " . $mysqli->error; die;
				}

				$url = "index.php?open=$open&page=$page&action=my_navigation&ft=success&fm=moved";
				header("Location: $url");
				exit;
			}
			else{
				$url = "index.php?open=$open&page=$page&action=my_navigation&ft=info&fm=not_possible";
				header("Location: $url");
				exit;

			}
		}

	}
} // move_down
elseif($action == "edit"){
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);

	$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_id=? AND navigation_user_id=?"); 
	$stmt->bind_param("ss", $navigation_id, $my_user_id_mysql);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_navigation_id, $get_current_navigation_url, $get_current_navigation_title, $get_current_navigation_icon, $get_current_navigation_icon_white_18, $get_current_navigation_icon_black_18, $get_current_navigation_weight) = $row;

	if($get_current_navigation_id != ""){
		if($process == "1"){
			$inp_url = $_POST['inp_url'];
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_icon = $_POST['inp_icon'];
			$inp_icon = output_html($inp_icon);
			$inp_icon_mysql = quote_smart($link, $inp_icon);

			$inp_icon_black_small = $inp_icon . "_black_18x18.png";
			$inp_icon_black_small_mysql = quote_smart($link, $inp_icon_black_small);

			$inp_icon_black_medium = $inp_icon . "_black_24x24.png";
			$inp_icon_black_medium_mysql = quote_smart($link, $inp_icon_black_medium);

			$inp_icon_white_small = $inp_icon . "_white_18x18.png";
			$inp_icon_white_small_mysql = quote_smart($link, $inp_icon_white_small);

			$inp_icon_white_medium = $inp_icon . "_white_24x24.png";
			$inp_icon_white_medium_mysql = quote_smart($link, $inp_icon_white_medium);

			$inp_icon_color_small = $inp_icon . "_orange_18x18.png";
			$inp_icon_color_small_mysql = quote_smart($link, $inp_icon_color_small);

			$inp_icon_color_medium = $inp_icon . "_orange_24x24.png";
			$inp_icon_color_medium_mysql = quote_smart($link, $inp_icon_color_medium);

			if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_url=$inp_url_mysql, navigation_title=$inp_title_mysql, navigation_icon=$inp_icon_mysql, 
			navigation_icon_black_18=$inp_icon_black_small_mysql, navigation_icon_black_24=$inp_icon_black_medium_mysql, 
			navigation_icon_white_18=$inp_icon_white_small_mysql, navigation_icon_white_24=$inp_icon_white_medium_mysql, 
			navigation_icon_color_18=$inp_icon_color_small_mysql, navigation_icon_color_24=$inp_icon_color_medium_mysql
			 WHERE navigation_id=$get_current_navigation_id") !== TRUE) {
				echo "Error updating record: " . $mysqli->error; die;
			}


			$url = "index.php?open=$open&page=$page&action=my_navigation&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
			
		}
		echo"
		<h1>Edit $get_current_navigation_title</h1>


		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l\">Admin navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;editor_language=$editor_language&amp;l=$l\">My navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;action=$action&amp;navigation_id=$get_current_navigation_id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_current_navigation_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "deleted"){
					$fm = "$l_deleted";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_text\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;navigation_id=$get_current_navigation_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p>URL<br />
			<input type=\"text\" name=\"inp_url\" value=\"$get_current_navigation_url\" size=\"25\" />
			</p>

			<p>Title<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_navigation_title\" size=\"25\" />
			</p>

			<p>Icon<br />
			<input type=\"text\" name=\"inp_icon\" value=\"$get_current_navigation_icon\" size=\"25\" />
			</p>

			<p><input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
		<!-- //Form -->

		";

	} // navigation found
} // edit
elseif($action == "delete"){
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);

	$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_id=? AND navigation_user_id=?"); 
	$stmt->bind_param("ss", $navigation_id, $my_user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_navigation_id, $get_current_navigation_url, $get_current_navigation_title, $get_current_navigation_icon, $get_current_navigation_icon_white_18, $get_current_navigation_icon_black_18, $get_current_navigation_weight) = $row;

	if($get_current_navigation_id != ""){
		if($process == "1"){
			if ($mysqli->query("DELETE FROM $t_admin_navigation WHERE navigation_id=$get_current_navigation_id") !== TRUE) {
				echo "Error MySQLi delete: " . $mysqli->error; die;
			}

			$url = "index.php?open=$open&page=$page&action=my_navigation&ft=success&fm=deleted";
			header("Location: $url");
			exit;
			
		}
		echo"
		<h1>Delete $get_current_navigation_title</h1>


		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;navigation_id=$get_navigation_id&amp;editor_language=$editor_language&amp;l=$l\">Admin navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;editor_language=$editor_language&amp;l=$l\">My navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;action=$action&amp;navigation_id=$get_current_navigation_id&amp;editor_language=$editor_language&amp;l=$l\">Delete $get_current_navigation_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "deleted"){
					$fm = "$l_deleted";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Form -->
			<p>Are you sure?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;navigation_id=$get_current_navigation_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Delete</a>
			</p>
		<!-- //Form -->

		";

	} // navigation found
} // delete
elseif($action == "add_item"){
	// Me
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);


	if($process == "1"){
		$inp_url = $_GET['item'];
		$inp_url = output_html($inp_url);

		$inp_title = ucfirst($inp_url);
		$inp_title = output_html($inp_title);

		$inp_icon = "$inp_url";
		$inp_icon = output_html($inp_icon);

		$inp_icon_black_small = $inp_icon . "_black_18x18.png";

		$inp_icon_black_medium = $inp_icon . "_black_24x24.png";

		$inp_icon_white_small = $inp_icon . "_white_18x18.png";

		$inp_icon_white_medium = $inp_icon . "_white_24x24.png";

		$inp_icon_color_small = $inp_icon . "_orange_18x18.png";

		$inp_icon_color_medium = $inp_icon . "_orange_24x24.png";

		$stmt = $mysqli->prepare("INSERT INTO $t_admin_navigation 
		(navigation_id, navigation_url, navigation_title, navigation_icon, navigation_icon_black_18, 
		navigation_icon_black_24, navigation_icon_white_18, navigation_icon_white_24, navigation_icon_color_18, navigation_icon_color_24, 
		navigation_user_id, navigation_show, navigation_weight) 
		VALUES 
		(NULL,?,?,?,?, 
		?,?,?,?,?, 
		?,'1','999')");
		$stmt->bind_param("ssssssssss", $inp_url, $inp_title, $inp_icon, $inp_icon_black_small, $inp_icon_black_medium, 
		$inp_icon_white_small, $inp_icon_white_medium, $inp_icon_color_small, $inp_icon_color_medium, 
		$my_user_id); 
		$stmt->execute();



		// Sort all alphabetically
		$x=0;
		$query = "SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_user_id=$my_user_id_mysql ORDER BY navigation_title ASC";
		$result = $mysqli->query($query);
		while($row = $result->fetch_row()) {
			list($get_navigation_id, $get_navigation_url, $get_navigation_title, $get_navigation_icon_white_18, $get_navigation_icon_black_18, $get_navigation_weight) = $row;
			if($get_navigation_weight != "$x"){
				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$x WHERE navigation_id=$get_navigation_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
			}
			$x++;
		}

		// Get ID of dashboard
		$query = "SELECT navigation_id FROM $t_admin_navigation WHERE navigation_url='dashboard' AND navigation_user_id=$my_user_id_mysql";
		$result = $mysqli->query($query);
		$row = $result->fetch_row();
		list($get_dashboard_navigation_id) = $row;

		// Set weight to -1
		if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=-1 WHERE navigation_id=$get_dashboard_navigation_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}

		

		$url = "index.php?open=$open&page=$page&action=add_item&ft=success&fm=item_added";
		header("Location: $url");
		exit;
			
	}
	echo"
	<h1>Add item</h1>


		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Admin navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;editor_language=$editor_language&amp;l=$l\">My navigation</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">Add item</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "deleted"){
					$fm = "$l_deleted";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
	<!-- //Feedback -->

	<!-- Form -->
		<p>
		Select the item to add.
		</p>

		<div class=\"vertical\">
			<ul>
			";
			// Custom pages
			$dir_files = array();
			$filenames = "";
			$dir = "_inc/";
			$x=0;
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file === '.') continue;
					if ($file === '..') continue;
					if ($file === "admin_cms") continue;
					if ($file === "login") continue;
					if ($file === "ucp") continue;
					if ($file === "setup") continue;


					$dir_files[] = $file;
				}
				closedir($handle);
			}

			sort($dir_files);
			foreach($dir_files as $file){
					$admin_navigation_title = ucfirst($file);
					$admin_navigation_icon = "$file";
					$admin_navigation_icon_black_small = $file . "_black_18x18.png";
					$admin_navigation_icon_black_medium = $file . "_black_24x24.png";
					$admin_navigation_icon_white_small = $file . "_white_18x18.png";
					$admin_navigation_icon_white_medium = $file . "_white_24x24.png";


					// Check if I have it
					$navigation_url = "$file";

					
					$stmt = $mysqli->prepare("SELECT navigation_id FROM $t_admin_navigation WHERE navigation_url=? AND navigation_user_id=?"); 
					$stmt->bind_param("ss", $navigation_url, $my_user_id);
					$stmt->execute();
					$result = $stmt->get_result();
					$row = $result->fetch_row();
					list($get_current_navigation_id) = $row;

					if($get_current_navigation_id == ""){
						echo"
						<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=my_navigation&amp;action=$action&amp;item=$file&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\"><img src=\"_inc/$file/_gfx/icons/$admin_navigation_icon_black_small\" alt=\"$admin_navigation_icon_black_small\" /> $admin_navigation_title</a></li>";
					}
			}
			echo"

			
			</ul>
		</div>
	<!-- //Form -->

	";

} // add

elseif($action == "my_navigation_auto_setup"){

	if($process == "1"){
		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$result = $mysqli->query("INSERT INTO $t_admin_navigation
		(navigation_id, navigation_url, navigation_title, navigation_icon, navigation_icon_black_18, navigation_icon_white_18, navigation_user_id, navigation_show, navigation_weight) 
		VALUES 
		(NULL, 'dashboard', 'Dashboard', 'dashboard', 'dashboard_black_18x18.png', 'dashboard_white_18x18.png', $my_user_id_mysql, 1, 1),
		(NULL, 'pages', 'Pages', 'pages', 'pages_black_18x18.png', 'pages_white_18x18.png', $my_user_id_mysql, 1, 2),
		(NULL, 'media', 'Media', 'media', 'media_black_18x18.png', 'media_white_18x18.png', $my_user_id_mysql, 1, 3),
		(NULL, 'users', 'Users', 'users', 'users_black_18x18.png', 'users_white_18x18.png', $my_user_id_mysql, 1, 4),
		(NULL, 'settings', 'Settings', 'settings', 'settings_black_18x18.png', 'settings_white_18x18.png', $my_user_id_mysql, 1, 5),
		(NULL, 'webdesign', 'Webdesign', 'webdesign', 'webdesign_black_18x18.png', 'webdesign_white_18x18.png', $my_user_id_mysql, 1, 6)
		");

			
		$url = "index.php?l=$l&editor_language=$editor_language";
		header("Location: $url");
		exit;
	}
} // my_navigation_auto_setup
elseif($action == "add_to_favorite_and_visit"){
	// Me
	$my_user_id = $_SESSION['admin_user_id'];
	$my_user_id = output_html($my_user_id);


	$inp_url = $_GET['item'];
	$inp_url = output_html($inp_url);

	$inp_title = ucfirst($inp_url);
	$inp_title = output_html($inp_title);

	$inp_icon = "$inp_url";
	$inp_icon = output_html($inp_icon);

	$inp_icon_black_small = $inp_icon . "_black_18x18.png";

	$inp_icon_black_medium = $inp_icon . "_black_24x24.png";

	$inp_icon_white_small = $inp_icon . "_white_18x18.png";

	$inp_icon_white_medium = $inp_icon . "_white_24x24.png";

	$inp_icon_color_small = $inp_icon . "_orange_18x18.png";

	$inp_icon_color_medium = $inp_icon . "_orange_24x24.png";

	// Check if I have this already
	$stmt = $mysqli->prepare("SELECT navigation_id FROM $t_admin_navigation WHERE navigation_url=? AND navigation_user_id=?"); 
	$stmt->bind_param("ss", $inp_url, $my_user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_navigation_id) = $row;
	if($get_current_navigation_id == ""){

		$inp_show = 1;
		$inp_weight = 999;

		$stmt = $mysqli->prepare("INSERT INTO $t_admin_navigation 
				(navigation_id, navigation_url, navigation_title, navigation_icon, navigation_icon_black_18, 
				navigation_icon_black_24, navigation_icon_white_18, navigation_icon_white_24, navigation_icon_color_18, navigation_icon_color_24, 
				navigation_user_id, navigation_show, navigation_weight) 
				VALUES 
				(NULL,?,?,?,?,
				?,?,?,?,?,
				?,?,?)");
		$stmt->bind_param("ssssssssssss", $inp_url, $inp_title, $inp_icon, $inp_icon_black_small, 
				$inp_icon_black_medium, $inp_icon_white_small, $inp_icon_white_medium, $inp_icon_color_small, $inp_icon_color_medium, 
				$my_user_id, $inp_show, $inp_weight); 
		$stmt->execute();


		// Sort all alphabetically
		$x=0;
		$stmt = $mysqli->prepare("SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18, navigation_icon_black_18, navigation_weight FROM $t_admin_navigation WHERE navigation_user_id=? ORDER BY navigation_title ASC"); 
		$stmt->bind_param("s", $my_user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_row()) {
			list($get_navigation_id, $get_navigation_url, $get_navigation_title, $get_navigation_icon_white_18, $get_navigation_icon_black_18, $get_navigation_weight) = $row;
			if($get_navigation_weight != "$x"){

				if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=$x WHERE navigation_id=$get_navigation_id") !== TRUE) {
					echo "Error MySQLi update: " . $mysqli->error; die;
				}
			}
			$x++;
		}

		// Get ID of dashboard
		$query = "SELECT navigation_id FROM $t_admin_navigation WHERE navigation_url='dashboard' AND navigation_user_id=$my_user_id_mysql";
		$result = $mysqli->query($query);
		$row = $result->fetch_row();
		list($get_dashboard_navigation_id) = $row;

		// Set weight to -1
		if ($mysqli->query("UPDATE $t_admin_navigation SET navigation_weight=-1 WHERE navigation_id=$get_dashboard_navigation_id") !== TRUE) {
			echo "Error MySQLi update: " . $mysqli->error; die;
		}

		
		$url = "index.php?open=$inp_url&editor_language=$editor_language&l=$l&ft=info&fm=added_to_quick_access_for_next_time";
		header("Location: $url");
		exit;
	}
	else{
		$url = "index.php?open=$inp_url&editor_language=$editor_language&l=$l";
		header("Location: $url");
		exit;
	}


} // action == add_to_favorite_and_visit
?>