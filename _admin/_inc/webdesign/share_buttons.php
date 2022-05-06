<?php
/**
*
* File: _admin/_inc/webdesign/share_buttons.php
* Version 15:16 19.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_webdesign_share_buttons	= $mysqlPrefixSav . "webdesign_share_buttons";


if($action == ""){
	echo"
	<h1>Share buttons</h1>


	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\" class=\"btn btn_default\">New</a>
	</p>

	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Editor language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	<!-- List all share buttons -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th scope=\"col\">
		<span>Title</span>
	   </th>
	   <th scope=\"col\">
		<span>URL</span>
	   </th>
	   <th scope=\"col\">
		<span>Image</span>
	   </th>
	   <th scope=\"col\">
		<span>Actions</span>
	   </th>
	  </tr>
	</thead>
	<tbody>
	";
	$editor_language = output_html($editor_language);
	$editor_language_mysql = quote_smart($link, $editor_language);
	$query = "SELECT button_id, button_title, button_url, button_code_preload, button_code_plugin, button_language, button_image_path, button_image_18x18 FROM $t_webdesign_share_buttons WHERE button_language=$editor_language_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_button_id, $get_button_title, $get_button_url, $get_button_code_preload, $get_button_code_plugin, $get_button_language, $get_button_image_path, $get_button_image_18x18) = $row;


		echo"
		<tr>
		  <td>
			<span>$get_button_title</span>
		  </td>
		  <td>
			<span>$get_button_url</span>
		  </td>
		  <td>
			<span>";
			if($get_button_image_18x18 != ""){
				echo"<img src=\"../$get_button_image_path/$get_button_image_18x18\" alt=\"$get_button_image_18x18\" />";
			}
			echo"</span>
		  </td>
		  <td>
			<span>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;button_id=$get_button_id&amp;editor_language=$editor_language\">Edit</a>
			&middot;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;button_id=$get_button_id&amp;editor_language=$editor_language\">Delete</a>
			</span>
		 </td>
		</tr>
		";
	}
	echo"
	 </tbody>
	</table>
	<!-- //List all buttons -->

	";
} // action == ""
elseif($action == "new"){
	if($process == "1"){
		$datetime = date("Y-m-d H:i:s");

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_url = $_POST['inp_url'];
		$inp_url = output_html($inp_url);
		$inp_url_mysql = quote_smart($link, $inp_url);

		$inp_code_preload = $_POST['inp_code_preload'];

		$inp_code_plugin = $_POST['inp_code_plugin'];

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);
		
		// Check empty
		if($inp_title == ""){
			$url = "index.php?open=webdesign&page=share_buttons&action=new&editor_language=$editor_language&ft=error&fm=title_cannot_be_empty";
			header("Location: $url");
			exit;
		}
		// Insert
		mysqli_query($link, "INSERT INTO $t_webdesign_share_buttons
		(button_id, button_title, button_url, button_code_preload, button_code_plugin, button_language, button_image_path, button_image_18x18, button_updated) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_url_mysql, '', '', $inp_language_mysql, '', '', '$datetime')")
		or die(mysqli_error($link));

		// Get slide id
		$query = "SELECT button_id FROM $t_webdesign_share_buttons WHERE button_updated='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_button_id) = $row;

		// Preload and plugin
		if($inp_code_preload != "" OR $inp_code_plugin != ""){
			
			$sql = "UPDATE $t_webdesign_share_buttons SET button_code_preload=?, button_code_plugin=? WHERE button_id='$get_current_button_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ss", $inp_code_preload, $inp_code_plugin);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}
		}

		// Check that directory exists
		if(!(is_dir("../_uploads/share_buttons"))){
			mkdir("../_uploads/share_buttons");
		}
		if(!(is_dir("../_uploads/share_buttons/$inp_language"))){
			mkdir("../_uploads/share_buttons/$inp_language");
		}


		// Upload image
		$img_ft = "";
		$img_fm = "";
		if (!empty($_FILES)) {
     
			// Sjekk filen
			$file_name = basename($_FILES['inp_image']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			$image_path = "../_uploads/share_buttons/$inp_language";
		

			// Sett variabler
			$new_name = $get_current_button_id . ".$file_type";
			$target_path = $image_path . "/" . $new_name;


			// Sjekk om det er en OK filendelse
			if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif"){
				if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

					// Sjekk om det faktisk er et bilde som er lastet opp
					$image_size = getimagesize($target_path);
					if(is_numeric($image_size[0]) && is_numeric($image_size[1])){
						// Dette bildet er OK

						// Insert into db
						$inp_path_mysql = quote_smart($link, "_uploads/share_buttons/$inp_language");
						$inp_image_mysql = quote_smart($link, $new_name);
						$result = mysqli_query($link, "UPDATE $t_webdesign_share_buttons SET button_image_path=$inp_path_mysql, button_image_18x18=$inp_image_mysql WHERE button_id=$get_current_button_id");
		
	
					}
					else{
						// Dette er en fil som har fått byttet filendelse...
						unlink("$target_path");

						$img_ft = "error";
						$img_fm = "file_is_not_an_image";
					}
				}
				else{
   					switch ($_FILES['inp_image'] ['error']){
					case 1:
					$img_ft = "error";
					$img_fm = "to_big_file";
					header("Location: $url");
					exit;
					break;
					case 2:
					$img_ft = "error";
					$img_fm = "to_big_file";
					header("Location: $url");
					exit;
					break;
					case 3:
					$img_ft = "error";
					$img_fm = "only_parts_uploaded";
					header("Location: $url");
					exit;
					break;
					case 4:
					$img_ft = "error";
					$img_fm = "no_file_uploaded";
					header("Location: $url");
					exit;
					break;
					}
				} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			}
			else{
				$img_ft = "error";
				$img_fm = "invalid_file_type&file_type=$file_type";
			}
		}
	

		header("Location: index.php?open=$open&page=$page&action=$action&editor_language=$inp_language&ft=success&fm=button_created&img_ft=$img_ft&img_fm=$img_fm");
		exit;

	} // process == 1
	echo"
	<h1>New share button</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Share buttons</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>

		</p>
	<!-- //Where am i? -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		if(isset($_GET['img_ft']) && isset($_GET['img_fm'])){

			$img_ft = $_GET['img_ft'];
			$img_ft = strip_tags(stripslashes($img_ft));

			$img_fm = $_GET['img_fm'];
			$img_fm = strip_tags(stripslashes($img_fm));

			if($img_fm == "changes_saved"){
				$img_fm = "$l_changes_saved";
			}
			else{
				$img_fm = ucfirst($img_fm);
			}
			echo"<div class=\"$img_ft\"><span>$img_fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- New Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;action=$action&amp;process=1\" enctype=\"multipart/form-data\">
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->


		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>


		<p><b>URL:</b><br />
		<span>You can use %url% and %title% in the URL for dynamic content</span><br />
		<input type=\"text\" name=\"inp_url\" size=\"25\" style=\"width: 98%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>


		<p><b>Code preload:</b><br />
		<span>If the service requres a JavaScript to be run before showing buttons</span><br />
		<textarea name=\"inp_code_preload\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		</p>

		<p><b>Code plugin:</b><br />
		<span>If a regular URL cannot be used then insert the code for the plugin here instead</span><br />
		<textarea name=\"inp_code_plugin\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		</p>

		<p><b>Language</b><br />
		<select name=\"inp_language\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
			echo"	<option value=\"$get_language_active_iso_two\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
						
		}
		echo"
		</select>

		<p><b>Image (18x18, png)</b><br />
		<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Create\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"btn_default\" /></p>

		</form>

	<!-- New Form -->
	";

} // action == new
elseif($action == "edit"){
	/*- Variables ------------------------------------------------------------------------ */
	if(isset($_GET['button_id'])) {
		$button_id = $_GET['button_id'];
		$button_id = strip_tags(stripslashes($button_id));
		if(!(is_numeric($button_id))){
			echo"Button id not numeric";
			die;
		}
	}
	else{
		echo"Missing button id"; 
		die;
	}

	// Get slide id
	$button_id_mysql = quote_smart($link, $button_id);
	$query = "SELECT button_id, button_title, button_url, button_code_preload, button_code_plugin, button_language, button_image_path, button_image_18x18 FROM $t_webdesign_share_buttons WHERE button_id=$button_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_button_id, $get_current_button_title, $get_current_button_url, $get_current_button_code_preload, $get_current_button_code_plugin, $get_current_button_language, $get_current_button_image_path, $get_current_button_image_18x18) = $row;
	
	if($get_current_button_id == ""){
		echo"<p>Button not found</p>";
	}
	else{

		if($process == "1"){
			$datetime = date("Y-m-d H:i:s");

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_url = $_POST['inp_url'];
			$inp_url = output_html($inp_url);
			$inp_url_mysql = quote_smart($link, $inp_url);

			$inp_code_preload = $_POST['inp_code_preload'];

			$inp_code_plugin = $_POST['inp_code_plugin'];

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);
		
			// Check empty
			if($inp_title == ""){
				$url = "index.php?open=webdesign&page=share_buttons&action=$action&button_id=$get_current_button_id&editor_language=$editor_language&ft=error&fm=title_cannot_be_empty";
				header("Location: $url");
				exit;
			}
			// Update
			mysqli_query($link, "UPDATE $t_webdesign_share_buttons SET
						button_title= $inp_title_mysql, 
						button_url=$inp_url_mysql, 
						button_code_preload='', 
						button_code_plugin='', 
						button_language=$inp_language_mysql, 
						button_updated='$datetime'
					     WHERE button_id=$get_current_button_id") or die(mysqli_error($link));

			// Preload and plugin
			if($inp_code_preload != "" OR $inp_code_plugin != ""){
			
				$sql = "UPDATE $t_webdesign_share_buttons SET button_code_preload=?, button_code_plugin=? WHERE button_id='$get_current_button_id'";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("ss", $inp_code_preload, $inp_code_plugin);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}
			}

			// Check that directory exists
			if(!(is_dir("../_uploads/share_buttons"))){
				mkdir("../_uploads/share_buttons");
			}
			if(!(is_dir("../_uploads/share_buttons/$inp_language"))){
				mkdir("../_uploads/share_buttons/$inp_language");
			}


			// Upload image
			$img_ft = "";
			$img_fm = "";
			if (!empty($_FILES)) {
     
				// Sjekk filen
				$file_name = basename($_FILES['inp_image']['name']);
				$file_exp = explode('.', $file_name); 
				$file_type = $file_exp[count($file_exp) -1]; 
				$file_type = strtolower("$file_type");

				// Finnes mappen?
				$image_path = "../_uploads/share_buttons/$inp_language";
		

				// Sett variabler
				$rand = date("ymdhis");
				$new_name = $get_current_button_id . "_" . $rand . ".$file_type";
				$target_path = $image_path . "/" . $new_name;


				// Sjekk om det er en OK filendelse
				if($file_type == "jpg" OR $file_type == "png" OR $file_type == "gif"){
					if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

						// Sjekk om det faktisk er et bilde som er lastet opp
						$image_size = getimagesize($target_path);
						if(is_numeric($image_size[0]) && is_numeric($image_size[1])){
							// Dette bildet er OK
	
							// Insert into db
							$inp_path_mysql = quote_smart($link, "_uploads/share_buttons/$inp_language");
							$inp_image_mysql = quote_smart($link, $new_name);
							$result = mysqli_query($link, "UPDATE $t_webdesign_share_buttons SET button_image_path=$inp_path_mysql, button_image_18x18=$inp_image_mysql WHERE button_id=$get_current_button_id");
		
							// Unlink old image
							if($get_current_button_image_18x18 != "" && file_exists("../$get_current_button_image_path/$get_current_button_image_18x18")){
								unlink("../$get_current_button_image_path/$get_current_button_image_18x18");
							}
						}
						else{
							// Dette er en fil som har fått byttet filendelse...
							unlink("$target_path");

							$img_ft = "error";
							$img_fm = "file_is_not_an_image";
						}
					}
					else{
   						switch ($_FILES['inp_image'] ['error']){
							case 1:
								$img_ft = "error";
								$img_fm = "to_big_file";
								break;
							case 2:
								$img_ft = "error";
								$img_fm = "to_big_file";
								break;
							case 3:
								$img_ft = "error";
								$img_fm = "only_parts_uploaded";
								break;
							case 4:
								$img_ft = "error";
								$img_fm = "no_file_uploaded";
								break;
							}
						} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
					}
					else{
						$img_ft = "error";
						$img_fm = "invalid_file_type&file_type=$file_type";
					}
				}
	

			header("Location: index.php?open=$open&page=$page&action=$action&button_id=$get_current_button_id&editor_language=$inp_language&ft=success&fm=button_changes_saved&img_ft=$img_ft&img_fm=$img_fm");
			exit;

		} // process == 1
		echo"
		<h1>Edit share button $get_current_button_title</h1>

		<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Share buttons</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;button_id=$get_current_button_id&amp;editor_language=$editor_language\">Edit $get_current_button_title</a>

		</p>
		<!-- //Where am i? -->

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		if(isset($_GET['img_ft']) && isset($_GET['img_fm'])){

			$img_ft = $_GET['img_ft'];
			$img_ft = strip_tags(stripslashes($img_ft));

			$img_fm = $_GET['img_fm'];
			$img_fm = strip_tags(stripslashes($img_fm));

			if($img_fm == "changes_saved"){
				$img_fm = "$l_changes_saved";
			}
			else{
				$img_fm = ucfirst($img_fm);
			}
			echo"<div class=\"$img_ft\"><span>$img_fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Edit button Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;action=$action&amp;button_id=$get_current_button_id&amp;process=1\" enctype=\"multipart/form-data\">
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->


		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_button_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>


		<p><b>URL:</b><br />
		<span>You can use %url% and %title% in the URL for dynamic content</span><br />
		<input type=\"text\" name=\"inp_url\" value=\"$get_current_button_url\" size=\"25\" style=\"width: 98%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>


		<p><b>Code preload:</b><br />
		<span>If the service requres a JavaScript to be run before showing buttons</span><br />
		<textarea name=\"inp_code_preload\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_button_code_preload</textarea>
		</p>

		<p><b>Code plugin:</b><br />
		<span>If a regular URL cannot be used then insert the code for the plugin here instead</span><br />
		<textarea name=\"inp_code_plugin\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_button_code_plugin</textarea>
		</p>

		<p><b>Language</b><br />
		<select name=\"inp_language\" tabindex=\""; $tabindex=$tabindex+1;echo"$tabindex\" />";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";
			echo"	<option value=\"$get_language_active_iso_two\"";if($get_language_active_iso_two == "$get_current_button_language"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
						
		}
		echo"
		</select>

		<p><b>Image (18x18, png)</b><br />
		";
		if($get_current_button_image_18x18 != "" && file_exists("../$get_current_button_image_path/$get_current_button_image_18x18")){
			echo"<img src=\"../$get_current_button_image_path/$get_current_button_image_18x18\" alt=\"$get_current_button_image_18x18\" /><br />\n";
		}
		echo"
		<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Create\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"btn_default\" /></p>

		</form>

		<!-- Edit button Form -->
		";
	} // found
} // action == edit
elseif($action == "delete"){
	/*- Variables ------------------------------------------------------------------------ */
	if(isset($_GET['button_id'])) {
		$button_id = $_GET['button_id'];
		$button_id = strip_tags(stripslashes($button_id));
		if(!(is_numeric($button_id))){
			echo"Button id not numeric";
			die;
		}
	}
	else{
		echo"Missing button id"; 
		die;
	}

	// Get button id
	$button_id_mysql = quote_smart($link, $button_id);
	$query = "SELECT button_id, button_title, button_url, button_code_preload, button_code_plugin, button_language, button_image_path, button_image_18x18 FROM $t_webdesign_share_buttons WHERE button_id=$button_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_button_id, $get_current_button_title, $get_current_button_url, $get_current_button_code_preload, $get_current_button_code_plugin, $get_current_button_language, $get_current_button_image_path, $get_current_button_image_18x18) = $row;
	
	if($get_current_button_id == ""){
		echo"<p>Button not found</p>";
	}
	else{

		if($process == "1"){
			
			// Delete
			mysqli_query($link, "DELETE FROM $t_webdesign_share_buttons WHERE button_id=$get_current_button_id") or die(mysqli_error($link));

			// Unlink old image
			if($get_current_button_image_18x18 != "" && file_exists("../$get_current_button_image_path/$get_current_button_image_18x18")){
				unlink("../$get_current_button_image_path/$get_current_button_image_18x18");
			}
			
			header("Location: index.php?open=$open&page=$page&editor_language=$inp_language&ft=success&fm=button_deleted&img_ft=$img_ft&img_fm=$img_fm");
			exit;

		} // process == 1
		echo"
		<h1>Delete share button $get_current_button_title</h1>

		<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Share buttons</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;button_id=$get_current_button_id&amp;editor_language=$editor_language\">Delete $get_current_button_title</a>
		</p>
		<!-- //Where am i? -->

		<!-- Delete button Form -->
			<p>
			Are you sure?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;action=$action&amp;button_id=$get_current_button_id&amp;process=1\" class=\"btn_default\">Confirm</a>
			</p>

		<!-- //Delete button Form -->
		";
	} // found
} // action == delete
?>